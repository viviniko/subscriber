<?php

namespace Viviniko\Subscriber\Repositories\Subscriber;

interface SubscriberRepository
{
    public function findByEmail($email);
}