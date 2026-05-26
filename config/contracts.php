<?php

return [
    'companions' => [
        'max' => (int) env('CONTRACT_COMPANIONS_MAX', 5),
        'multi_sector_id' => (int) env('CONTRACT_COMPANIONS_MULTI_SECTOR_ID', 3),
    ],
];
