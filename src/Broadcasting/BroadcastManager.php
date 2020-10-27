<?php


namespace alst161\LighthouseRedisBroadcaster\Broadcasting;

use Illuminate\Contracts\Container\BindingResolutionException;
use Nuwave\Lighthouse\Subscriptions\BroadcastManager as BaseBroadcastManager;
use alst161\LighthouseRedisBroadcaster\Contracts\Broadcaster;

class BroadcastManager extends BaseBroadcastManager
{
    /**
     * @return string
     */
    public function interface(): string
    {
        return Broadcaster::class;
    }

    /**
     * @param array $config
     * @return Broadcaster
     * @throws BindingResolutionException
     */
    public function createRedisDriver(array $config): Broadcaster
    {
        return $this->app->make(Broadcaster::class);
    }
}
