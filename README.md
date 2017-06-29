# Comments for Laravel 5.4 and up

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]
[![Total Downloads][ico-downloads]][link-downloads]

[![GitHub stars][ico-github]][link-github]

* [Installation](#installation)
* [Usage](#usage)
* [License](#license)

## Installation

This package can be used in Laravel 5.4 or higher. You can install the package via composer:

```bash
composer require finagin/laravel-comment
```

Now add the service provider in config/app.php file:
```php
'providers' => [
    /*
     * Package Service Providers...
     */
    // ...
    Finagin\Comment\CommentServiceProvider::class,
    // ...
];
```

You must publish the migration with:
```bash
php artisan vendor:publish --provider="Finagin\Comment\CommentServiceProvider" --tag="migrations"
```

After the migration has been published you must create the settings-tables by running the migrations:
```bash
php artisan migrate
```

Also you can publish the config file with:
```bash
php artisan vendor:publish --provider="Finagin\Comment\CommentServiceProvider" --tag="config"
```

Add `CanComment` trait to your User model.
``` php
use Finagin\Comment\Traits\CanComment;
```

Add `Commentable` trait to your commentable model(s).
``` php
use Finagin\Comment\Traits\Commentable;
```

If you want to have your own Comment Model create a new one and extend my Comment model.
``` php
class Comment extends Finagin\Comment\Models\Comment
{
  ...
}
```

Comment package comes with several modes.

1) If you want to Users can rate your model(s) with comment set `canBeRated` to true in your `Commentable` model.
``` php
class Post extends Model {
  use Commentable;

  protected $canBeRated = true;

  ...
}
```

2) If you want to approve comments for your commentable models, you must set `mustBeApproved` to true in your `Commentable` model.
``` php
class Post extends Model {
  use Commentable;

  protected $mustBeApproved = true;

  ...
}
```

## Usage

``` php
$user = App\User::find(1);
$post = App\Post::find(1);

// CanComment->comment(Commentable|Commnet $commentable, string $commentText): Comment

// Anonimous first level comment
$comment = (new User(['name' => 'Anonymous']))->comment($post, 'Lorem ipsum ..');

// Users sub comment
$user
    ->comment($comment, 'Lorem ipsum ..');

// Anonimous sub comment
(new User(['name' => 'Anonymous']))
    ->comment($comment, 'Lorem ipsum ..');
```

## License

The MIT License ([MIT](https://opensource.org/licenses/MIT)). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/finagin/laravel-comment.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/finagin/laravel-comment

[ico-license]: https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square

[ico-travis]: https://img.shields.io/travis/finagin/laravel-comment/master.svg?style=flat-square
[link-travis]: https://travis-ci.org/finagin/laravel-comment

[ico-styleci]: https://styleci.io/repos/94550265/shield
[link-styleci]: https://styleci.io/repos/94550265

[ico-downloads]: https://img.shields.io/packagist/dt/finagin/laravel-comment.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/finagin/laravel-comment

[ico-github]: https://img.shields.io/github/stars/finagin/laravel-comment.svg?style=social&label=Star
[link-github]: https://github.com/finagin/laravel-comment
