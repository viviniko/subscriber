<?php

namespace Viviniko\Subscriber\Contracts;

interface SubscriberService
{
    public function isClientSubscribed($clientId = null);

    public function addSubscriber($email, $data = []);

    public function removeSubscriber($email);

    public function getSubscriber($email);

    public function unsubscribe($email);
}