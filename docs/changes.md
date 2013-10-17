Asset Change Log
==============

## Version 2.1

### v2.1.0@dev

* Add `Orchestra\Asset\Container::show()` to return both `Orchestra\Asset\Container::styles()` and `Orchestra\Asset\Container::scripts()`.

## Version 2.0

### v2.0.2@dev

* Reduce complexity of `Orchestra\Asset\Container::evaluateAsset()`.

### v2.0.1

* Code improvements.

### v2.0.0

* Fork `Orchestra\Asset` from Laravel 3.
* Allow last modified versioning to be added via `Orchestra\Asset\Container::addVersioning()` and reversal via `Orchestra\Asset\Container::removeVersioning()`.
