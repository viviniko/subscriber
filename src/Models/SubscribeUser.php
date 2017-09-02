<?php

namespace Viviniko\Subscriber\Models;

use Viviniko\Support\Database\Eloquent\Model;

class SubscribeUser extends Model
{
    protected $tableConfigKey = 'subscriber.subscribe_users_table';

    protected $fillable = [
        'email', 'user_id', 'client_id', 'is_subscribe'
    ];

    protected $casts = [
        'is_subscribe' => 'boolean'
    ];
}