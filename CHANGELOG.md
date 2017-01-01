# Change Log
All notable changes to this project will be documented in this file.

## 3.0.1 - 2017-01-01
### Improved
- Updated PHP-CS-fixer.

## 3.0.0 - 2016-09-06
### Backwards-incompatible changes
- Channels concept for notifications and notify strategies
- Removed `NotificationInterface::__invoke()` method
- StrategyInterface::notify() replaces handle() method.
- `MessageSender` in favor of `SendService` naming for message sender implementations.
- Renamed `StrategyInterface` to `NotifyStrategyInterface`.
- Removed `GenericNotification`
- Removed `GenericContact`

## 2.2.x
This release is abandoned, please consider upgrading to 3.x.
