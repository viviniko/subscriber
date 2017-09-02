<?php

namespace Viviniko\Subscriber\Services;

use Viviniko\Subscriber\Contracts\SubscriberService as SubscriberServiceInterface;
use Viviniko\Subscriber\Events\SubscriberCanceled;
use Viviniko\Subscriber\Events\SubscriberCreated;
use Viviniko\Subscriber\Events\SubscriberRemoved;
use Viviniko\Subscriber\Events\SubscriberResubcribed;
use Viviniko\Subscriber\Repositories\Subscriber\SubscriberRepository;
use Illuminate\Contracts\Events\Dispatcher;

class SubscriberServiceImpl implements SubscriberServiceInterface
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

    public function getSubscriber($email)
    {
        return $this->subscribeUsers->findByEmail($email);
    }

    public function addSubscriber($email, $data = [])
    {
        $subscriber = $this->subscribeUsers->findByEmail($email);
        if ($subscriber) {
            if (!$subscriber->is_subscribe) {
                $subscriber = $this->subscribeUsers->update($subscriber->id, ['is_subscribe' => true]);
                $this->events->dispatch(new SubscriberResubcribed($email));
            } else {
                return false;
            }
        } else {
            $subscriber = $this->subscribeUsers->create(array_merge($data, ['email' => $email, 'is_subscribe' => true]));
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