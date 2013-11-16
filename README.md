Orchestra Platform Asset Component
==============

`Orchestra\Asset` Component is a port of Laravel 3 Asset for Orchestra Platform.

[![Latest Stable Version](https://poser.pugx.org/orchestra/asset/v/stable.png)](https://packagist.org/packages/orchestra/asset) 
[![Total Downloads](https://poser.pugx.org/orchestra/asset/downloads.png)](https://packagist.org/packages/orchestra/asset) 
[![Build Status](https://travis-ci.org/orchestral/asset.png?branch=2.0)](https://travis-ci.org/orchestral/asset) 
[![Coverage Status](https://coveralls.io/repos/orchestral/asset/badge.png?branch=2.0)](https://coveralls.io/r/orchestral/asset?branch=2.0) 
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/asset/badges/quality-score.png?s=3f3515804e4acb3e93c56f62559ac0b96ee74f24)](https://scrutinizer-ci.com/g/orchestral/asset/) 

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

### Aliases

You might want to add `Orchestra\Support\Facades\Asset` to class aliases in `app/config/app.php`:

```php
'aliases' => array(

	// ...

	'Orchestra\Asset' => 'Orchestra\Support\Facades\Asset',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/2.0/components/asset)
* [Change Log](http://orchestraplatform.com/docs/2.0/components/asset/changes#v2.0)
