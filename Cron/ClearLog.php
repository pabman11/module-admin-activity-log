<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @copyright  Copyright (C) 2025 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Cron;

use Exception;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use MageOS\AdminActivityLog\Api\ActivityConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Cron job for cleaning up old activity logs
 *
 * Uses batch deletion to avoid memory issues with large datasets.
 * Related tables (admin_activity_log, admin_activity_detail) are cleaned
 * automatically via CASCADE DELETE foreign key constraints.
 */
class ClearLog
{
    /**
     * Default date format
     */
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Batch size for deletion operations
     */
    protected const BATCH_SIZE = 1000;

    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly DateTime $dateTime,
        protected readonly ActivityConfigInterface $activityConfig,
        protected readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * Return cron cleanup date
     */
    public function getCleanupDate(): ?string
    {
        $timestamp = $this->dateTime->gmtTimestamp();
        $day = $this->activityConfig->getClearLogDays();
        if ($day) {
            $timestamp -= $day * 24 * 60 * 60;
            return $this->dateTime->gmtDate(self::DATE_FORMAT, $timestamp);
        }
        return null;
    }

    /**
     * Delete records older than the configured retention period
     *
     * Uses batch deletion to prevent memory exhaustion and long-running queries.
     * CASCADE DELETE on foreign keys handles related table cleanup automatically.
     */
    public function execute(): void
    {
        try {
            if (!$this->activityConfig->isEnabled()) {
                return;
            }

            $date = $this->getCleanupDate();
            if ($date === null) {
                return;
            }

            // Delete activity logs in batches
            $this->deleteInBatches('admin_activity', $date);

            // Delete login logs in batches if enabled
            if ($this->activityConfig->isLoginEnabled()) {
                $this->deleteInBatches('admin_login_log', $date);
            }
        } catch (Exception $e) {
            $this->logger->error('Failed to clear admin activity logs', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Delete records from table in batches
     *
     * This approach:
     * - Limits memory usage by not loading all records
     * - Reduces lock contention with smaller transactions
     * - Uses direct SQL DELETE with LIMIT for efficiency
     *
     * @param string $tableName Table name without prefix
     * @param string $endDate Cutoff date for deletion
     */
    protected function deleteInBatches(string $tableName, string $endDate): void
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName($tableName);

        $totalDeleted = 0;

        do {
            // Use subquery with LIMIT to get IDs to delete
            // This is more efficient than DELETE with LIMIT on large tables
            $select = $connection->select()
                ->from($table, ['entity_id'])
                ->where('created_at <= ?', $endDate)
                ->limit(self::BATCH_SIZE);

            $ids = $connection->fetchCol($select);

            if (empty($ids)) {
                break;
            }

            $deleted = $connection->delete(
                $table,
                ['entity_id IN (?)' => $ids]
            );

            $totalDeleted += $deleted;

            // Small delay to reduce database load on high-traffic systems
            if ($deleted === self::BATCH_SIZE) {
                usleep(10000); // 10ms
            }
        } while (count($ids) === self::BATCH_SIZE);

        if ($totalDeleted > 0) {
            $this->logger->info("Cleared {$totalDeleted} records from {$tableName}");
        }
    }
}
