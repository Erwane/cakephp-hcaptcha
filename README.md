# HCaptcha plugin for CakePHP 4.x

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![codecov](https://codecov.io/gh/Erwane/cakephp-hcaptcha/branch/2.x/graph/badge.svg?token=NNY4FBXCEE)](https://codecov.io/gh/Erwane/cakephp-hcaptcha)
![Build Status](https://github.com/Erwane/cakephp-hcaptcha/actions/workflows/ci.yml/badge.svg?branch=2.x)
[![Packagist Downloads](https://img.shields.io/packagist/dt/Erwane/cakephp-hcaptcha)](https://packagist.org/packages/Erwane/cakephp-hcaptcha)
[![Packagist Version](https://img.shields.io/packagist/v/Erwane/cakephp-hcaptcha)](https://packagist.org/packages/Erwane/cakephp-hcaptcha)

## Version map

| branch | CakePHP core | PHP min |
|--------|--------------|---------|
| 1.x    | ^4.0         | PHP 7.2 |
| 2.x    | ^5.0         | PHP 8.1 |

## Installation

```sh
composer require erwane/cakephp-hcaptcha
```

Load plugin in your `src/Application::bootstrap()`

```php
    public function bootstrap(): void
    {
        $this->addPlugin('HCaptcha');
    }
```

## Configuration

In your `config/app.php`, insert this default values:

```php
    // If you use .env file:
    'HCaptcha' => [
        'key' => env('HCAPTCHA_KEY'),
        'secret' => env('HCAPTCHA_SECRET'),
    ],

    // If you use config/app_local.php
    'HCaptcha' => [
        'key' => null,
        'secret' => null,
    ],
```

HCaptcha key and secret can be found in your [HCaptcha dashboard](https://dashboard.hcaptcha.com/sites?page=1)

## Usage

### In your templates

Add the captcha to your form

```php
<?= $this->Form->control('h-captcha-response', ['type' => 'hcaptcha']) ?>
```

You can pass options to hCaptcha.

```php
<?= $this->Form->control('h-captcha-response', [
    'type' => 'hcaptcha',
    'lang' => 'fr_FR',
    'onload' => 'myFunction',
    'render' => 'explicit',
    'recaptchacompat' => false,
]) ?>
```

### Validation

In your `Model` or `Form` validation, add hCaptcha validation provider and define your rule.

```php
use Cake\Validation\Validator;

public function validationDefault(Validator $validator): Validator
{
    $validator->setProvider('HCaptcha', '\HCaptcha\Validation');

    return parent::validationDefault($validator)
        ->add('h-captcha-response', 'hcaptcha', ['provider' => 'HCaptcha', 'rule' => 'hcaptcha']);
}
```
