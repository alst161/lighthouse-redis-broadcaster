<?php

use alst161\LighthouseRedisBroadcaster\Routing\SubscriptionRouter;

return [
    'driver' => 'redis',
    'connection' => env('LIGHTHOUSE_REDIS_CONNECTION', 'default'),
    'routes' => SubscriptionRouter::class . '@routes',
];
