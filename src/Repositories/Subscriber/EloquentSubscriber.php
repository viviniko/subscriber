<?php

namespace Viviniko\Subscriber\Repositories\Subscriber;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;
use Viviniko\Subscriber\Events\SubscriberCanceled;
use Viviniko\Subscriber\Events\SubscriberCreated;
use Viviniko\Subscriber\Events\SubscriberRemoved;
use Viviniko\Subscriber\Events\SubscriberResubcribed;

class EloquentSubscriber extends EloquentRepository implements SubscriberRepository
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;


    public function __construct(Dispatcher $events)
    {
        parent::__construct(Config::get('subscriber.subscriber'));
        $this->events = $events;
    }

    protected function postCreate($item)
    {
        $this->events->dispatch(new SubscriberCreated($item));
    }

    protected function postUpdate($item)
    {
        $this->events->dispatch($item->is_subscribe ? new SubscriberResubcribed($item) : new SubscriberCanceled($item));
    }

    protected function postDelete($item)
    {
        $this->events->dispatch(new SubscriberRemoved($item));
    }
}