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

Examples:

| Example Definition                                                                                      | Description                                              |
|---------------------------------------------------------------------------------------------------------|----------------------------------------------------------|
| **integer&vert;unsigned&vert;autoincrement** <br/><small>(short: **int&vert;unsigned&vert;ai**)</small> | A common definition of a primary key.                    | 
| **varchar&vert;length:50**                                                                              | A required column which stores a string up to 50 chars.  | 
| **varchar&vert;length:50&vert;nullable**                                                                | An optional column which stores a string up to 50 chars. | 

List of all data types:

| Data Type Keywords                                       | Description                                                                                                                                                                                                                                                                                                                                                  |
|----------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Numeric data types**                                   | Each date and time data type will be casted to *int* inside the model.                                                                                                                                                                                                                                                                                       |
| *tinyInteger* <br/>(<small>short: *tinyint*</small>)     | A very small integer. The signed range is -128 to 127. The unsigned range is 0 to 255.                                                                                                                                                                                                                                                                       |
| *smallInteger* <br/>(<small>short: *smallint*</small>)   | A small integer. The signed range is -32,768 to 32,767. The unsigned range is 0 to 65,535.                                                                                                                                                                                                                                                                   |
| *mediumInteger* <br/>(<small>short: *mediumint*</small>) | A medium-sized integer. The signed range is -8,388,608 to 8,388,607. The unsigned range is 0 to 16,777,215.                                                                                                                                                                                                                                                  |
| *integer* <br/>(<small>short: *int*</small>)             | A normal-size integer. The signed range is -2,147,483,648 to 2,147,483,647. The unsigned range is 0 to 4,294,967,295.                                                                                                                                                                                                                                        |
| *bigInteger* <br/>(<small>short: *bigint*</small>)       | A large integer. The signed range is -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807. The unsigned range is 0 to 18,446,744,073,709,551,615.                                                                                                                                                                                                         |
| *boolean* <br/>(<small>short: *bool*</small>)            | These types are synonyms for TINYINT(1). A value of zero is considered false. Nonzero values are considered true.                                                                                                                                                                                                                                            |
| *decimal*                                                | A packed “exact” fixed-point number. The maximum number of digits for decimal is 65. The maximum number of supported decimals is 30.                                                                                                                                                                                                                         |
| *float*                                                  | A floating-point number.                                                                                                                                                                                                                                                                                                                                     |
| **Date and Time Data Types**                             | Each date and time data type will be casted to [*Carbon*](https://carbon.nesbot.com/docs/) object inside the model.                                                                                                                                                                                                                                          |
| *date*                                                   | A date. The supported range is '1000-01-01' to '9999-12-31'. It is displayed in 'YYYY-MM-DD' format. Inside the model it is casted to .                                                                                                                                                                                                                      |
| *datetime*                                               | A date and time combination. The supported range is '1000-01-01 00:00:00.000000' to '9999-12-31 23:59:59.999999'.                                                                                                                                                                                                                                            |
| *timestamp*                                              | A timestamp. The range is '1970-01-01 00:00:01.000000' UTC to '2038-01-19 03:14:07.999999' UTC. <br/>TIMESTAMP values are stored as the number of seconds since the epoch ('1970-01-01 00:00:00' UTC).                                                                                                                                                       |
| *time*                                                   | A time. The range is '-838:59:59.000000' to '838:59:59.000000'.                                                                                                                                                                                                                                                                                              |
| *year*                                                   | A year in 4-digit format.                                                                                                                                                                                                                                                                                                                                    |
| **String**                                               | String Data Types                                                                                                                                                                                                                                                                                                                                            |
| *varchar* <br/>(<small>short: *string*</small>)          | Values in VARCHAR columns are variable-length strings. The length can be specified as a value from 0 to 65,535. <br/>The effective maximum length of a VARCHAR is subject to the maximum row size (65,535 bytes, which is shared among all columns) and the character set used.                                                                              |
| *char*                                                   | The length of a CHAR column is fixed to the length that you declare when you create the table. The length can be any value from 0 to 255. When CHAR values are stored, they are right-padded with spaces to the specified length. <br/>(When CHAR values are retrieved, trailing spaces are removed unless the PAD_CHAR_TO_FULL_LENGTH SQL mode is enabled.) |
| *binary*                                                 | The BINARY type is similar to CHAR, except that it stores binary strings rather than non-binary strings. That is, they store byte strings rather than character strings. This means they have the binary character set and collation, and comparison and sorting are based on the numeric values of the bytes in the values.                                 |
| *varbinary*                                              | The VARBINARY type is similar to VARCHAR, except that it stores binary strings rather than non-binary strings. That is, they store byte strings rather than character strings. This means they have the binary character set and collation, and comparison and sorting are based on the numeric values of the bytes in the values.                           |
| *tinyblob*                                               | A binary large object that can store 255 (2^8 - 1) bytes.                                                                                                                                                                                                                                                                                                    | 
| *blob*                                                   | A binary large object that can store 65,535 (2^16 - 1) bytes (64 KB).                                                                                                                                                                                                                                                                                        | 
| *mediumblob*                                             | A binary large object that can store 16,777,215 (2^24 - 1) bytes (16 MB).                                                                                                                                                                                                                                                                                    | 
| *longblob*                                               | A binary large object that can store 4,294,967,295 (2^32 - 1) bytes (4 GB).                                                                                                                                                                                                                                                                                  | 
| *tinytext*                                               | A non-binary string (character strings) up to 255 (2^8 - 1) bytes.                                                                                                                                                                                                                                                                                           | 
| *text*                                                   | A non-binary string (character strings) up to 65,535 (2^16 - 1) bytes (64 KB).                                                                                                                                                                                                                                                                               | 
| *mediumtext*                                             | A non-binary string (character strings) up to 16,777,215 (2^24 - 1) bytes (16 MB).                                                                                                                                                                                                                                                                           | 
| *longtext*                                               | A non-binary string (character strings) up to 4,294,967,295 (2^32 - 1) bytes (4 GB).                                                                                                                                                                                                                                                                         | 



### UUID Usage
