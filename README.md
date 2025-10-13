# Magento Admin Activity Log

Created by [KiwiCommerce](https://kiwicommerce.co.uk/), maintained by [Mage-OS](https://mage-os.org)

- Easily track every admin activity like add, edit, delete, print, view, mass update etc.
- Failed attempts of admin login are recorded as well. You get access to the user’s login information and IP address.
- Track page visit history of admin.
- Track fields that have been changed from the backend.
- Allow administrator to revert the modification.

## **Installation**

```bash
composer require mage-os/module-admin-activity-log
bin/magento setup:upgrade
```

## Where will it appear in the Admin Panel

### Admin Activity Log

Go to **System > Admin Activity > Admin Activity Logs**. Here you can see the list of admin activity logs and page visit
history.

### Login Activity

Go to **System > Admin Activity > Admin Login Logs**. Here you can See the list of login activity logs.

## Configuration

Go to **Stores > Configuration > Advanced > Admin > Admin Activity**

### Compatibility

- Magento 2.4
