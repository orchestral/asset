Asset Component for Orchestra Platform
==============

Asset Component is a port of Laravel 3 Asset for Orchestra Platform. The component main functionality is to allow asset declaration to be handle dynamically and asset dependencies can be resolve directly from the container. It however is not intended to becoma an asset pipeline package for Laravel, for such purpose we would recommend to use Grunt or Gulp.

[![Build Status](https://travis-ci.org/orchestral/asset.svg?branch=4.x)](https://travis-ci.org/orchestral/asset)
[![Latest Stable Version](https://poser.pugx.org/orchestra/asset/version)](https://packagist.org/packages/orchestra/asset)
[![Total Downloads](https://poser.pugx.org/orchestra/asset/downloads)](https://packagist.org/packages/orchestra/asset)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/asset/v/unstable)](//packagist.org/packages/orchestra/asset)
[![License](https://poser.pugx.org/orchestra/asset/license)](https://packagist.org/packages/orchestra/asset)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/asset/badge.svg?branch=4.x)](https://coveralls.io/github/orchestral/asset?branch=4.x)

## Table of Content

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
  - [Registering Assets](#registering-assets)
  - [Dumping Assets](#dumping-assets)
  - [Asset Dependencies](#asset-dependencies)
  - [Asset Containers](#asset-containers)
  - [Asset Versioning](#asset-versioning)
* [Changelog](https://github.com/orchestral/asset/releases)

## Version Compatibility

Laravel    | Asset
:----------|:----------
 5.5.x     | 3.5.x
 5.6.x     | 3.6.x
 5.7.x     | 3.7.x
 5.8.x     | 3.8.x
 6.x       | 4.x
 
## Installation

To install through composer, simply run the following command:

    composer require "orchestra/asset=^4.0"

### Manual Installation

You can also manually put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/asset": "^4.0"
    }
}
```

And then run `composer install` from the terminal.

## Configuration

Add following service providers in `config/app.php`.

```php
'providers' => [

    // ...

    Orchestra\Asset\AssetServiceProvider::class,
    Collective\Html\HtmlServiceProvider::class,
    
],
```

### Aliases

You might want to add `Orchestra\Support\Facades\Asset` to class aliases in `config/app.php`:

```php
'aliases' => [

    // ...

    'Asset' => Orchestra\Support\Facades\Asset::class,

],
```

## Usage

### Registering Assets

The Asset class provides a simple way to manage the CSS and JavaScript used by your application. To register an asset just call the add method on the Asset class:

#### Registering an asset:

```php
Asset::add('jquery', 'js/jquery.js');
```

The add method accepts three parameters. The first is the name of the asset, the second is the path to the asset relative to the public directory, and the third is a list of asset dependencies (more on that later). Notice that we did not tell the method if we were registering JavaScript or CSS. The add method will use the file extension to determine the type of file we are registering.

### Dumping Assets

When you are ready to place the links to the registered assets on your view, you may use the styles or scripts methods:

Dumping assets into a view:

```html
<head>
    {!! Asset::styles() !!}
    {!! Asset::scripts() !!}
</head>
```

Above code can also be simplified as:

```html
<head>
    {!! Asset::show() !!}
</head>
```

### Asset Dependencies

Sometimes you may need to specify that an asset has dependencies. This means that the asset requires other assets to be declared in your view before it can be declared. Managing asset dependencies couldn't be easier in Laravel. Remember the "names" you gave to your assets? You can pass them as the third parameter to the add method to declare dependencies:

Registering a bundle that has dependencies:

```php
Asset::add('jquery-ui', 'js/jquery-ui.js', 'jquery');
```

In this example, we are registering the jquery-ui asset, as well as specifying that it is dependent on the jquery asset. Now, when you place the asset links on your views, the jQuery asset will always be declared before the jQuery UI asset. Need to declare more than one dependency? No problem:

Registering an asset that has multiple dependencies:

```php
Asset::add('jquery-ui', 'js/jquery-ui.js', ['first', 'second']);
```

### Asset Containers

To increase response time, it is common to place JavaScript at the bottom of HTML documents. But, what if you also need to place some assets in the head of your document? No problem. The asset class provides a simple way to manage asset containers. Simply call the container method on the Asset class and mention the container name. Once you have a container instance, you are free to add any assets you wish to the container using the same syntax you are used to:

Retrieving an instance of an asset container:

```php
Asset::container('footer')->add('example', 'js/example.js');
```

Dumping that assets from a given container:

```php
{!! Asset::container('footer')->scripts() !!}
```

### Asset Versioning

Another option to increase response time is by utilizing browser caching, while there few ways to do this we pick last modified time as our way to version the Asset.

```php
Asset::container()->addVersioning();

// or alternatively
Asset::addVersioning();
```

> Note: this would only work with local asset.

You can remove adding versioning number by using:

```php
Asset::container()->removeVersioning();

// or alternatively
Asset::removeVersioning();
```
