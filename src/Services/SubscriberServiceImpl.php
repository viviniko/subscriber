<?php

namespace Viviniko\Subscriber\Services;

use Viviniko\Agent\Facades\Agent;
use Viviniko\Subscriber\Events\SubscriberCanceled;
use Viviniko\Subscriber\Events\SubscriberCreated;
use Viviniko\Subscriber\Events\SubscriberRemoved;
use Viviniko\Subscriber\Events\SubscriberResubcribed;
use Viviniko\Subscriber\Repositories\Subscriber\SubscriberRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;

class SubscriberServiceImpl implements SubscriberService
{
    /**
     * @var \Viviniko\Subscriber\Repositories\Subscriber\SubscriberRepository
     */
    protected $subscribeUsers;

    /**
     * Instance of the event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    public function __construct(SubscriberRepository $subscribeUsers, Dispatcher $events)
    {
        $this->subscribeUsers = $subscribeUsers;
        $this->events = $events;
    }

    public function isClientSubscribed($clientId = null)
    {
        return $this->subscribeUsers->hasClientId($clientId ?? Agent::clientId());
    }

    public function getSubscriber($email)
    {
        return $this->subscribeUsers->findByEmail($email);
    }

    public function addSubscriber($email, $data = [])
    {
        $subscriber = $this->subscribeUsers->findByEmail($email);
        if ($subscriber) {
            if (!$subscriber->is_subscribe) {
                $subscriber = $this->subscribeUsers->update($subscriber->id, [
                    'is_subscribe' => true,
                    'user_id' => Auth::id(),
                    'client_id' => Agent::clientId()
                ]);
                $this->events->dispatch(new SubscriberResubcribed($email));
            } else {
                return false;
            }
        } else {
            $subscriber = $this->subscribeUsers->create(array_merge([
                'user_id' => Auth::id(),
                'client_id' => Agent::clientId()
            ], $data, [
                'email' => $email,
                'is_subscribe' => true
            ]));
            $this->events->dispatch(new SubscriberCreated($email));
        }

        return $subscriber;
    }

    public function removeSubscriber($email)
    {
        $subscriber = $this->subscribeUsers->findByEmail($email);
        if ($subscriber) {
            $this->subscribeUsers->delete($subscriber->id);
            $this->events->dispatch(new SubscriberRemoved($email));
        }
    }

    public function unsubscribe($email)
    {
        $subscriber = $this->subscribeUsers->findByEmail($email);
        if ($subscriber && $subscriber->is_subscribe) {
            $this->subscribeUsers->update($subscriber->id, ['is_subscribe' => false]);
            $this->events->dispatch(new SubscriberCanceled($email));
        }
    }
}