# Laravel Crafting Table
This package provides several tools to build models, including the Repository Pattern, 
extended ModelQuery, API and CRUD.

## Installation
Require this package with composer.
```bash
composer require simklee/laravel-crafting-table
```
Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

> If you use this package as a dev requirement you have export some classes into your namespace: 
> 
> ```bash
> php artisan crafter:install --dev 
> ```

## Commands

## Model Definitions
Each model is defined in the model configuration. You can export a sample configuration by publishing the 
configuration file from vendor: 
```bash
php artisan vendor:publish --provider="SimKlee\LaravelCraftingTable\LaravelCraftingTableServiceProvider" --tag="config"
```

Published config file (```config/models.php```):
```php
<?php
return [
    'Model' => [
        'table'      => null,
        'columns'    => [],
        'values'     => [],
        'defaults'   => [],
        'timestamps' => false,
        'softDelete' => false,
        'uuid'       => false,
    ],
];
```
The model name is defined by the key. All parts of each model definition are listed bellow.

| Key        | Description                                                                                                                                                                          |
|------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| table      | The table name of the model. Usually the plural of the model name.                                                                                                                   | 
| columns    | A list of the columns of the model. The used keywords to specify the column are documented bellow (https://github.com/SimKlee/laravel-crafting-table/blob/master/README.md#columns). |
| values     | The possible values of the columns. <br/>Example: ```'values' => ['col1' => ['val1', 'val2'], ...]```)                                                                               |
| defaults   | The default values of the columns. <br/>Example: ``` 'defaults' => ['col1' => 'val1', ...] ```                                                                                       |
| timestamps | Defines, if the model uses the timestamps feature [*boolean*] (https://laravel.com/docs/master/eloquent#timestamps).                                                                 |                                                                                                                 
| softDelete | Defines, if the model uses the soft delete feature [*boolean*] (https://laravel.com/docs/master/eloquent#soft-deleting).                                                             |
| uuid       | Defines, if the model uses the timestamps feature [*boolean*] (https://github.com/SimKlee/laravel-crafting-table/blob/master/README.md#uuid-usage).                                  |

### Columns 
Each column can be specified by several keywords.

### UUID Usage
