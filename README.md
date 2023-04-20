# QSchool FakerPhp Image Provider

[![Latest Version on Packagist](https://img.shields.io/packagist/v/qschool/fakerphp-image-provider.svg?style=flat-square)](https://packagist.org/packages/qschool/fakerphp-image-provider)
[![Total Downloads](https://img.shields.io/packagist/dt/qschool/fakerphp-image-provider.svg?style=flat-square)](https://packagist.org/packages/qschool/fakerphp-image-provider)

## Описание

Провайдер для получения фейковых для [fakerphp](https://github.com/fakerphp/faker) изображения с [qschool/faker-images-service](https://api.faker-images.academy.qsoft.ru/images)
 
## Установка

```bash
composer require --dev qschool/fakerphp-image-provider
```

## Использование

```php
$faker = \Faker\Factory::create();
$faker->addProvider(new \QSchool\FakerImageProvider\FakerImageProvider($faker));

// Для получения строки url 'https://api.faker-images.academy.qsoft.ru/image/car?w=800&h=600'
$faker->imageUrl($width = 800, $height = 600, $category = 'car'); 

// Для скачивания изображения во временную директорию получения строки url
$filename = $faker->image($dir = '/tmp', $width = 640, $height = 480, $category = 'car');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
