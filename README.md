Orchestra Platform Asset Component
==============

Orchestra\Asset is a port of Laravel 3 Asset for Orchestra Platform.

[![Build Status](https://travis-ci.org/orchestral/asset.png?branch=master)](https://travis-ci.org/orchestral/asset) [![Coverage Status](https://coveralls.io/repos/orchestral/asset/badge.png?branch=master)](https://coveralls.io/r/orchestral/asset?branch=master)

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/asset": "2.0.*"
	}
}
```

Add `Orchestra\Asset\AssetServiceProvider` service provider in `app/config/app.php`.

```php
'providers' => array(
	
	// ...
	
	'Orchestra\Asset\AssetServiceProvider',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/2.0/components/asset)
* [Change Logs](https://github.com/orchestral/asset/wiki/Change-Logs)
