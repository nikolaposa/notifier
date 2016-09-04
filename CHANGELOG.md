# Change Log
All notable changes to this project will be documented in this file.

## [Unreleased]

### Backwards-incompatible changes
- Channels concept for notifications and notify strategies
- Removed `NotificationInterface::__invoke()` method
- StrategyInterface::notify() replaces handle() method.
- `MessageSender` in favor of `SendService` naming for message sender implementations.
- Removed `GenericNotification`
- Removed `GenericContact`

## 2.2.x
This release is abandoned, please consider upgrading to 3.x.

[Unreleased]: https://github.com/nikolaposa/notify/compare/2.2.0...HEAD