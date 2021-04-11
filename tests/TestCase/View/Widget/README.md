# HCaptcha plugin for CakePHP 4.x

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
Add the captcha to your form
```html
<?= $this->Form->control('h-captcha-response', ['type' => 'hcaptcha']) ?>
```
In your form validation, could be a model or modelless form, add Validation provider and use `hcaptcha` rule

```php
use Cake\Validation\Validator;

public function validationDefault(Validator $validator): Validator
{
    $validator->setProvider('HCaptcha', '\HCaptcha\Validation');

    return parent::validationDefault($validator)
        ->add('h-captcha-response', 'hcaptcha', ['provider' => 'HCaptcha', 'rule' => 'hcaptcha']);
}
```
