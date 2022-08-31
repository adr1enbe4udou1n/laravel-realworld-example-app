<?php

return [
    'secret_key' => env('JWT_SECRET_KEY', 'abcdefghijklmnopqrstuvwxyz1234567890'),
    'expire' => env('JWT_EXPIRE', '+1 hour'),
];
