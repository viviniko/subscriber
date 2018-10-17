<?php

namespace Viviniko\Subscriber\Repositories\Subscriber;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;

class EloquentSubscriber extends EloquentRepository implements SubscriberRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('subscriber.subscribe_user'));
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