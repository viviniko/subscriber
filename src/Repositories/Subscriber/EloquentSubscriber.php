<?php

namespace Viviniko\Subscriber\Repositories\Subscriber;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;

class EloquentSubscriber extends EloquentRepository implements SubscriberRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('subscriber.subscriber'));
    }
}