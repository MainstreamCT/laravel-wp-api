# laravel-wp-api

[![Latest Stable Version](https://poser.pugx.org/threesquared/laravel-wp-api/v/stable)](https://packagist.org/packages/mainstreamct/laravel-wp-api)

*Forked originally from [mattwilding/laravel-wp-api](https://github.com/mattwilding/laravel-wp-api)*

Laravel 6+ package for the [Wordpress JSON REST API](https://github.com/WP-API/WP-API), with support for MultiSite tenant management automation. Uses [multisite-json-api](https://github.com/remkade/multisite-json-api) on the WordPress side of things.

## Install

Simply add the following line to your `composer.json` and run install/update:

    "mainstreamct/laravel-wp-api": "~2.0"

## Configuration

You will need to add the service provider and optionally the facade alias to your `config/app.php`:

```php
'providers' => array(
  MainstreamCT\WordPressAPI\WordPressAPIServiceProvider::class
)

'aliases' => array(
  'WordPressAPI' => MainstreamCT\WordPressAPI\Facades\WordPressAPI::class
),
```

And publish the package config files to configure the location of your Wordpress install:

    php artisan vendor:publish

Provide the following in your `.env` file:
```.env
  WP_API_ENDPOINT=your_site_name
  WP_API_USERNAME=your_site_admin_username
  WP_API_PASSWORD=your_site_admin_password
```

Don't forget to re-cache your configuration!

    php artisan config:cache

## Usage

The package provides a simplified interface to some of the existing api methods documented [here](http://wp-api.org/).
You can either use the Facade provided or inject the `MainstreamCT\WordPressAPI\WordPressAPI` class.

### Getters
#### Posts
```php
WordPressAPI::getPosts($page);
```

#### Pages
```php
WordPressAPI::getPages($page);
```

#### Post
```php
WordPressAPI::getPostBySlug($slug);
```

```php
WordPressAPI::getPostByID($id);
```

#### Categories
```php
WordPressAPI::getCategories();
```

#### Tags
```php
WordPressAPI::getTags();
```

#### Category posts
```php
WordPressAPI::getPostsByCategory($slug, $page);
```

#### Author posts
```php
WordPressAPI::getPostsByAuthor($slug, $page);
```

#### Tag posts
```php
WordPressAPI::getPostsByTags($tags, $page);
```

#### Search
```php
WordPressAPI::searchPosts($query, $page);
```

#### Archive
```php
WordPressAPI::getPostsByDate($year, $month, $page);
```

### Other methods
#### Deploy Multisite tenant
```php
WordPressAPI::deploy($site_name, $blog_title, $email, $password);
```