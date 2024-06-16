<?php
return [
    'NET_WAREHOUSE_ID' => 1,

    'PRICE_RANGES' => [
        '0-1500' => [0, 1500],
        '1500-5000' => [1500, 5000],
        '5000-10000' => [5000, 10000],
        '10000-30000' => [10000, 30000],
        '30000-' => [30000, null],
    ],

    'SORT_OPTIONS' => [
        'price_asc' => ['price_excluding_tax', 'asc'],
        'price_desc' => ['price_excluding_tax', 'desc'],
        'favorites_asc' => ['favorites_count', 'asc'],
        'favorites_desc' => ['favorite_count', 'desc'],
        'newest' => ['created_at', 'desc'],
    ],
];