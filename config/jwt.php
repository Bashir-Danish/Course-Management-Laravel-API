<?php

return [
    'secret' => env('JWT_SECRET'),
    'ttl' => env('JWT_TTL', 60), // Time in minutes
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Time in minutes
    'algo' => 'HS256',
    'required_claims' => [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],
]; 