Using Asset
==============

* [Registering Assets](#registering-assets)
* [Dumping Assets](#dumping-assets)
* [Asset Dependencies](#asset-dependencies)
* [Asset Containers](#asset-containers)
* [Asset Versioning](#asset-versioning)

## Registering Assets

The Asset class provides a simple way to manage the CSS and JavaScript used by your application. To register an asset just call the add method on the Asset class:

### Registering an asset:

```php
Orchestra\Asset::add('jquery', 'js/jquery.js');
```

The add method accepts three parameters. The first is the name of the asset, the second is the path to the asset relative to the public directory, and the third is a list of asset dependencies (more on that later). Notice that we did not tell the method if we were registering JavaScript or CSS. The add method will use the file extension to determine the type of file we are registering.

## Dumping Assets

When you are ready to place the links to the registered assets on your view, you may use the styles or scripts methods:

Dumping assets into a view:

```html
<head>
	<?php echo Orchestra\Asset::styles(); ?>
	<?php echo Orchestra\Asset::scripts(); ?>
</head>
```

## Asset Dependencies

Sometimes you may need to specify that an asset has dependencies. This means that the asset requires other assets to be declared in your view before it can be declared. Managing asset dependencies couldn't be easier in Laravel. Remember the "names" you gave to your assets? You can pass them as the third parameter to the add method to declare dependencies:

Registering a bundle that has dependencies:

```php
Orchestra\Asset::add('jquery-ui', 'js/jquery-ui.js', 'jquery');
```

In this example, we are registering the jquery-ui asset, as well as specifying that it is dependent on the jquery asset. Now, when you place the asset links on your views, the jQuery asset will always be declared before the jQuery UI asset. Need to declare more than one dependency? No problem:

Registering an asset that has multiple dependencies:

```php
Orchestra\Asset::add('jquery-ui', 'js/jquery-ui.js', array('first', 'second'));
```

## Asset Containers

To increase response time, it is common to place JavaScript at the bottom of HTML documents. But, what if you also need to place some assets in the head of your document? No problem. The asset class provides a simple way to manage asset containers. Simply call the container method on the Asset class and mention the container name. Once you have a container instance, you are free to add any assets you wish to the container using the same syntax you are used to:

Retrieving an instance of an asset container:

```php
Orchestra\Asset::container('footer')->add('example', 'js/example.js');
```

Dumping that assets from a given container:

```php
echo Orchestra\Asset::container('footer')->scripts();
```

## Asset Versioning

Another option to increase response time is by utilizing browser caching, while there few ways to do this we pick last modified time as our way to version the Asset.

```php
Orchestra\Asset::container()->addVersioning();

// or possibility
Orchestra\Asset::addVersioning();
```

> Note: this would only work with local asset.
