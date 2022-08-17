# HCaptcha plugin for CakePHP 4.x

![Build Status](https://github.com/Erwane/cakephp-hcaptcha/actions/workflows/ci.yml/badge.svg?branch=1.x)
[![codecov](https://codecov.io/gh/Erwane/cakephp-hcaptcha/branch/1.x/graph/badge.svg?token=NNY4FBXCEE)](https://codecov.io/gh/Erwane/cakephp-hcaptcha)
[![Total Downloads](https://img.shields.io/packagist/dt/Erwane/cakephp-hcaptcha?style=flat-square)](https://packagist.org/packages/Erwane/cakephp-hcaptcha/stats)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)

## Installation

With composer

```
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
