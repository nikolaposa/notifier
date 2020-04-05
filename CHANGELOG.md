# Change Log
All notable changes to this project will be documented in this file.

## [Unreleased][link-unreleased]

### Changed
- PHPUnit 7.2 is now the minimum required version
- Introduce `Channel` sub-component that deals with notification delivery channels (email, sms, and similar)
- Replace `AbstractNotification` class with channel-specific Notification interfaces
- Rename `NotificationRecipientInterface` to `Recipient\Recipient`

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


[link-unreleased]: https://github.com/nikolaposa/rate-limit/compare/3.0.1...HEAD
