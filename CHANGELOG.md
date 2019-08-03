# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/asset`.

## 3.8.1

Released: 2019-08-03

### Changes

* Use `static function` rather than `function` whenever possible, the PHP
engine does not need to instantiate and later GC a `$this` variable for said closure.

## 3.8.0

Released: 2018-03-02

### Changes

* Update support for Laravel Framework v5.8.

## 3.7.1

Released: 2019-02-25

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

## 3.7.0

Released: 2018-08-16

### Changes

* Update support for Laravel Framework v5.7.

## 3.6.1

Released: 2018-05-02

### Changes

* return `self` should only be used when method is marked as `final`.

## 3.6.0

Released: 2018-02-18

### Changes

* Update support for Laravel Framework v5.6.
