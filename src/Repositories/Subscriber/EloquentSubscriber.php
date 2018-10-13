<?php

namespace Viviniko\Subscriber\Repositories\Subscriber;

use Viviniko\Repository\EloquentRepository;

class EloquentSubscriber extends EloquentRepository implements SubscriberRepository
{
    public function __construct()
    {
        parent::__construct('subscriber.subscribe_user');
    }

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function hasClientId($clientId)
    {
        return $this->exists('client_id', $clientId);
    }
}