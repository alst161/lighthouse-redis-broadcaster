<?php

namespace thekonz\LighthouseRedisBroadcaster;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Symfony\Component\HttpFoundation\Response;
use thekonz\LighthouseRedisBroadcaster\Contracts\Broadcaster;
use thekonz\LighthouseRedisBroadcaster\Events\SubscriptionEvent;

class RedisBroadcaster implements Broadcaster
{
    /**
     * @var \Illuminate\Contracts\Broadcasting\Broadcaster
     */
    private $broadcaster;

    public function __construct(\Illuminate\Broadcasting\BroadcastManager $broadcaster)
    {
        $this->broadcaster = $broadcaster;
    }

    /**
     * @param Subscriber $subscriber
     * @param array $data
     */
    public function broadcast(Subscriber $subscriber, array $data)
    {
        $this->broadcaster->event(
            new SubscriptionEvent($subscriber->channel, $data)
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function authorized(Request $request)
    {
        return new JsonResponse(['message' => 'Authorized'], 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function unauthorized(Request $request)
    {
        return new JsonResponse(['message' => 'Unauthorized'], 403);
    }
}
