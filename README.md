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

| Key        | Description                                                                                                                                                                                        |
|------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| table      | The table name of the model. Usually the plural of the model name.                                                                                                                                 | 
| columns    | A list of the columns of the model. The used keywords to specify the column are documented bellow ([see bellow](https://github.com/SimKlee/laravel-crafting-table/blob/master/README.md#columns)). |
| values     | The possible values of the columns. <br/>Example: ```'values' => ['col1' => ['val1', 'val2'], ...]```)                                                                                             |
| defaults   | The default values of the columns. <br/>Example: ``` 'defaults' => ['col1' => 'val1', ...] ```                                                                                                     |
| timestamps | Defines, if the model uses the timestamps feature of Laravel [*boolean*] ([Laravel documentation](https://laravel.com/docs/master/eloquent#timestamps)).                                           |                                                                                                                 
| softDelete | Defines, if the model uses the soft delete feature of Laravel [*boolean*] ([Laravel documentation](https://laravel.com/docs/master/eloquent#soft-deleting)).                                       |
| uuid       | Defines, if the model uses the timestamps feature of this package [*boolean*] ([see bellow](https://github.com/SimKlee/laravel-crafting-table/blob/master/README.md#uuid-usage)).                  |

### Columns 
Each column can be specified by several keywords.

List of all data types:

| Data Type Keywords           | Description                                                                                                                                                                                            |
|------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Numeric data types**       | Each date and time data type will be casted to *int* inside the model.                                                                                                                                 |
| tinyInteger or tinyint       | A very small integer. The signed range is -128 to 127. The unsigned range is 0 to 255.                                                                                                                 |
| smallInteger or smallint     | A small integer. The signed range is -32,768 to 32,767. The unsigned range is 0 to 65,535.                                                                                                             |
| mediumInteger or mediumint   | A medium-sized integer. The signed range is -8,388,608 to 8,388,607. The unsigned range is 0 to 16,777,215.                                                                                            |
| integer or int               | A normal-size integer. The signed range is -2,147,483,648 to 2,147,483,647. The unsigned range is 0 to 4,294,967,295.                                                                                  |
| bigInteger or bigint         | A large integer. The signed range is -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807. The unsigned range is 0 to 18,446,744,073,709,551,615.                                                   |
| boolean or bool              | These types are synonyms for TINYINT(1). A value of zero is considered false. Nonzero values are considered true.                                                                                      |
| decimal                      | A packed “exact” fixed-point number. The maximum number of digits for decimal is 65. The maximum number of supported decimals is 30.                                                                   |
| float                        | A floating-point number.                                                                                                                                                                               |
| **Date and Time Data Types** | Each date and time data type will be casted to [*Carbon*](https://carbon.nesbot.com/docs/) object inside the model.                                                                                    |
| date                         | A date. The supported range is '1000-01-01' to '9999-12-31'. It is displayed in 'YYYY-MM-DD' format. Inside the model it is casted to .                                                                |
| datetime                     | A date and time combination. The supported range is '1000-01-01 00:00:00.000000' to '9999-12-31 23:59:59.999999'.                                                                                      |
| timestamp                    | A timestamp. The range is '1970-01-01 00:00:01.000000' UTC to '2038-01-19 03:14:07.999999' UTC. <br/>Timestamp values are stored as the number of seconds since the epoch ('1970-01-01 00:00:00' UTC). |
| time                         | A time. The range is '-838:59:59.000000' to '838:59:59.000000'.                                                                                                                                        |
| year                         | A year in 4-digit format.                                                                                                                                                                              |
| **String**                   | String Data Types                                                                                                                                                                                      |



### UUID Usage
