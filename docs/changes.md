---
title: Asset Change Log

---

## Version 3.1 {#v3-1}

### v3.1.1 {#v3-1-1}

* Improved performances by reducing call within `Illuminate\Container\Container`.

### v3.1.0 {#v3-1-0}

* Update support for Laravel Framework v5.1.

## Version 3.0 {#v3-0}

### v3.0.0 {#v3-0-0}

* Update support for Laravel Framework v5.0.
* Simplify PSR-4 path.
* Rename `Orchestra\Asset\Container` to `Orchestra\Asset\Asset`.
* Allow assets to be replaces within dependency resolver.

## Version 2.2 {#v2-2}

### v2.2.1@dev {#v2-2-0}

* Suggest Illuminate packages for installation outside of Laravel app.

### v2.2.0 {#v2-2-0}

* Bump minimum version to PHP v5.4.0.
* Rename `Orchestra\Asset\Environment` to `Orchestra\Asset\Factory`.

## Version 2.1 {#v2-1}

### v2.1.2@dev {#v2-1-2}

* Suggest Illuminate packages for installation outside of Laravel app.

### v2.1.1 {#v2-1-1}

* Implement [PSR-4](https://github.com/php-fig/fig-standards/blob/master/proposed/psr-4-autoloader/psr-4-autoloader.md) autoloading structure.
* Add `Orchestra\Asset\Container::prefix()` to support prefixing external URL such as CDN.

### v2.1.0 {#v2-1-0}

* Add `Orchestra\Asset\Container::show()` to return both `Orchestra\Asset\Container::styles()` and `Orchestra\Asset\Container::scripts()`.
* Add `Orchestra\Asset\Dispatcher` class for dispatching asset from `Orchestra\Asset\Container`.

## Version 2.0 {#v2-0}

### v2.0.2 {#v2-0-2}

* Reduce complexity of `Orchestra\Asset\Container::evaluateAsset()`.
* Implement [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard.

### v2.0.1 {#v2-0-1}

* Code improvements.

### v2.0.0 {#v2-0-0}

* Fork `Orchestra\Asset` from Laravel 3.
* Allow last modified versioning to be added via `Orchestra\Asset\Container::addVersioning()` and reversal via `Orchestra\Asset\Container::removeVersioning()`.
