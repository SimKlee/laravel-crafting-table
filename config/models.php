<?php
/** @see https://github.com/SimKlee/laravel-crafting-table for full documentation of the model definitions */
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
    'Booking' => [
        'table'      => 'bookings',
        'columns'    => [
            'id' => 'integer|unsigned|ai',
            'name' => 'string|length:20|nullable',
        ],
        'values'     => [],
        'defaults'   => [],
        'timestamps' => true,
        'softDelete' => true,
        'uuid'       => true,
    ],
];
