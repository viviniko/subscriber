<?php

namespace Viviniko\Subscriber\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Viviniko\Client\Facades\Client;
use Viviniko\Subscriber\Events\SubscriberCanceled;
use Viviniko\Subscriber\Events\SubscriberCreated;
use Viviniko\Subscriber\Events\SubscriberRemoved;
use Viviniko\Subscriber\Events\SubscriberResubcribed;
use Viviniko\Subscriber\Repositories\Subscriber\SubscriberRepository;

class SubscriberServiceImpl implements SubscriberService
{
    /**
     * @var \Viviniko\Subscriber\Repositories\Subscriber\SubscriberRepository
     */
    protected $subscribers;

    public function __construct(SubscriberRepository $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    public function isClientSubscribed($clientId = null)
    {
        return $this->subscribeUsers->hasClientId($clientId ?? Client::id());
    }

    public function getSubscriber($email)
    {
        return $this->subscribers->findBy('email', $email);
    }

    public function addSubscriber($email, $data = [])
    {
        $subscriber = $this->subscribers->findBy('email', $email);
        if ($subscriber) {
            if (!$subscriber->is_subscribe) {
                $subscriber = $this->subscribers->update($subscriber->id, [
                    'is_subscribe' => true,
                    'user_id' => Auth::id(),
                    'client_id' => Client::id()
                ]);
            } else {
                return false;
            }
        } else {
            $subscriber = $this->subscribers->create(array_merge([
                'user_id' => Auth::id(),
                'client_id' => Client::id()
            ], $data, [
                'email' => $email,
                'is_subscribe' => true
            ]));
        }

        return $subscriber;
    }

    public function removeSubscriber($email)
    {
        $subscriber = $this->subscribers->findBy('email', $email);
        if ($subscriber) {
            $this->subscribers->delete($subscriber->id);
        }
    }

    public function unsubscribe($email)
    {
        $subscriber = $this->subscribers->findBy('email', $email);
        if ($subscriber && $subscriber->is_subscribe) {
            $this->subscribers->update($subscriber->id, ['is_subscribe' => false]);
        }
    }
}