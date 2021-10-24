<?php

return [
    'secret_key' => env('JWT_SECRET_KEY', 'super-secret-key'),
    'expire' => env('JWT_EXPIRE', '+1 hour'),
];
