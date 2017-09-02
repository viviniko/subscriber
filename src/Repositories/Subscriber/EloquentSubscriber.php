<?php

namespace Viviniko\Subscriber\Repositories\Subscriber;

use Viviniko\Repository\SimpleRepository;

class EloquentSubscriber extends SimpleRepository implements SubscriberRepository
{
    protected $modelConfigKey = 'subscriber.subscribe_user';

    public function findByEmail($email)
    {
        return $this->findBy('email', $email)->first();
    }
}