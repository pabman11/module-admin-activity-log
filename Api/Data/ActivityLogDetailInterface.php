<?php
declare(strict_types=1);

namespace MageOS\AdminActivityLog\Api\Data;

/**
 * Activity Log Detail entity data interface (admin_activity_detail table)
 */
interface ActivityLogDetailInterface
{
    public const ACTIVITY_ID = 'activity_id';
    public const MODEL_CLASS = 'model_class';
    public const ITEM_ID     = 'item_id';
    public const STATUS      = 'status';
    public const RESPONSE    = 'response';

    public function getActivityId(): int;
    public function setActivityId(int $activityId): self;

    public function getModelClass(): string;
    public function setModelClass(string $modelClass): self;

    public function getItemId(): ?int;
    public function setItemId(?int $itemId): self;

    public function getStatus(): string;
    public function setStatus(string $status): self;

    public function getResponse(): string;
    public function setResponse(string $response): self;
}
