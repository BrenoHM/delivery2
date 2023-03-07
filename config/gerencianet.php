<?php

return [
    "client_id" => env('GERENCIANET_CLIENT_ID'),
    "client_secret" => env('GERENCIANET_CLIENT_SECRET'),
    "certificate" => resource_path("certificates/".env('GERENCIANET_CERTIFICATE')), // Absolute path to the certificate in .pem or .p12 format
    "sandbox" => env('GERENCIANET_SANDBOX'),
    "debug" => env('GERENCIANET_DEBUG'),
    "timeout" => env('GERENCIANET_TIMEOUT'),
];