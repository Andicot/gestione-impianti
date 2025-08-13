<?php

return [


    'tag_title' => env('APP_NAME'),
    'sviluppo' => true,

    'mostra_accessi_test' => true,
    'accessi_test' => [
        ['descrizione' => 'Admin', 'email' => 'admin@admin.com', 'password' => 'password'],
    ],
    'url_online' => null,

    'primoAnno' => 2023,

    'cartella_progetto' => env('APP_NAME'),

    'aliquota_iva' => 22,

    'cssCKEditor' => [
    ],

    'immagini' => [
        'cartella' => '/immagini',
        'dimensioni' => [
            'width' => 1024,
            'height' => 1024
        ]
    ],


];
