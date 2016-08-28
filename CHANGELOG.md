# Change Log
All notable changes to this project will be documented in this file.

## [Unreleased]

### Backwards-incompatible changes
- Removed `NotificationInterface::__invoke()` method
- StrategyInterface::notify() replaces handle() method.
- `MessageSender` in favor of `SendService` naming for message sender implementations.