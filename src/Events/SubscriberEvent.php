<?php

namespace Viviniko\Subscriber\Events;

class SubscriberEvent
{
    public $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }
}