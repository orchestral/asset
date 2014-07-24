Asset Component for Orchestra Platform 2
==============

Asset Component is a port of Laravel 3 Asset for Orchestra Platform 2. The component main functionality is to allow asset declaration to be handle dynamically and asset dependencies can be resolve directly from the container. It however is not intended to becoma an asset pipeline package for Laravel, for such purpose we would recommend to use Grunt or Gulp.

[![Latest Stable Version](https://poser.pugx.org/orchestra/asset/v/stable.png)](https://packagist.org/packages/orchestra/asset) 
[![Total Downloads](https://poser.pugx.org/orchestra/asset/downloads.png)](https://packagist.org/packages/orchestra/asset) 
[![Build Status](https://travis-ci.org/orchestral/asset.svg?branch=2.1)](https://travis-ci.org/orchestral/asset)
[![Coverage Status](https://coveralls.io/repos/orchestral/asset/badge.png?branch=2.1)](https://coveralls.io/r/orchestral/asset?branch=2.1) 
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/asset/badges/quality-score.png?b=2.1)](https://scrutinizer-ci.com/g/orchestral/asset/) 


## Version Compatibility

Laravel    | Asset
:----------|:----------
 4.0.x     | 2.0.x
 4.1.x     | 2.1.x
 

## Installation

To install through composer, simply put the following in your `composer.json` file:

	{
		"require": {
			"orchestra/asset": "2.1.*"
		}
	}

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

	composer require "orchestra/asset=2.1.*"

## Configuration

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

* [Documentation](http://orchestraplatform.com/docs/latest/components/asset)
  - [Using Asset Component](http://orchestraplatform.com/docs/latest/components/asset/usage)
* [Change Log](http://orchestraplatform.com/docs/latest/components/asset/changes#v2-1)
