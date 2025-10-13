# Mage-OS Admin Activity Log

[![Latest Stable Version](https://poser.pugx.org/mage-os/module-admin-activity-log/v/stable)](https://packagist.org/packages/mage-os/module-admin-activity-log)
[![License](https://poser.pugx.org/mage-os/module-admin-activity-log/license)](https://packagist.org/packages/mage-os/module-admin-activity-log)
[![Total Downloads](https://poser.pugx.org/mage-os/module-admin-activity-log/downloads)](https://packagist.org/packages/mage-os/module-admin-activity-log)

A comprehensive admin activity logging extension for Mage-OS and Magento 2 that provides complete audit trails for
administrative actions, login attempts, and page visits.

Huge thanks to KiwiCommerce for publishing this extension, and for their support and contributions. This extension is a 
fork of the original [Magento Admin Activity Log](https://github.com/kiwicommerce/magento2-admin-activity) by KiwiCommerce.

## ✨ Key Features

- **Admin Action Logging**: Track all admin activities including add, edit, delete, print, view, and mass update operations
- **Login Monitoring**: Record successful and failed login attempts with detailed information
- **Field-Level Changes**: Track specific field modifications with before/after values
- **IP Address Logging**: Capture IP addresses and user agent information for security analysis

## 📋 Requirements

| Component | Version |
|-----------|---------|
| **Magento Open Source** | 2.4.x |
| **Mage-OS** | 1.0+ |
| **PHP** | 8.1+ |

## 🚀 Installation

```bash
composer require mage-os/module-admin-activity-log
php bin/magento setup:upgrade
```

## ⚙️ Configuration

### Basic Configuration

Navigate to **Stores → Configuration → Advanced → Admin → Admin Activity**

#### General Settings
- **Enable Module**: Enable/disable the extension
- **Enable Login Logging**: Track login and logout activities
- **Enable Page Visit History**: Monitor admin page navigation
- **Log Retention Period**: Set how many days to keep logs (default: 90 days)

#### Module-Specific Logging
Configure which modules to monitor:
- ✅ **Orders**: Track order-related activities
- ✅ **Products**: Monitor product changes
- ✅ **Categories**: Log category modifications
- ✅ **Customers**: Track customer data changes
- ✅ **System Configuration**: Monitor config changes
- ✅ **Admin Users**: Log user management activities
- ✅ **CMS Pages & Blocks**: Track content changes

## 📖 Usage Guide

### Viewing Activity Logs

Navigate to **System → Admin Activity → Admin Activity Logs**

Click View on any log entry to see detailed info.

### Login Activity Monitoring

Go to **System → Admin Activity → Admin Login Logs**
