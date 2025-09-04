<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QR Code Backend
    |--------------------------------------------------------------------------
    |
    | This option controls the default backend that will be used by the QR
    | code generator. You may set this to any of the backends defined in
    | the "backends" array below.
    |
    */

    'backend' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | QR Code Backends
    |--------------------------------------------------------------------------
    |
    | Here you may configure the backends that will be used by the QR code
    | generator. You may add additional backends as needed.
    |
    */

    'backends' => [
        'gd' => [
            'class' => \BaconQrCode\Renderer\Image\GdImageBackEnd::class,
        ],
        'imagick' => [
            'class' => \BaconQrCode\Renderer\Image\ImagickImageBackEnd::class,
        ],
    ],
];
