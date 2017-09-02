<?php

namespace Viviniko\Subscriber\Events;

class SubscriberEvent
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
}