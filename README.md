# Laravel File Utility Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stats4sd/fileutil.svg?style=flat-square)](https://packagist.org/packages/stats4sd/fileutil)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/stats4sd/fileutil/run-tests?label=tests)](https://github.com/stats4sd/fileutil/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/stats4sd/fileutil/Check%20&%20fix%20styling?label=code%20style)](https://github.com/stats4sd/fileutil/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/stats4sd/fileutil.svg?style=flat-square)](https://packagist.org/packages/stats4sd/fileutil)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require stats4sd/fileutil
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Stats4sd\FileUtil\FileUtilServiceProvider" --tag="fileutil-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Stats4sd\FileUtil\FileUtilServiceProvider" --tag="fileutil-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$fileutil = new Stats4sd\FileUtil();
echo $fileutil->echoPhrase('Hello, Stats4sd!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dan Tang](https://github.com/stats4sd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
