<?php

namespace Viviniko\Subscriber\Models;

use Viviniko\Support\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $tableConfigKey = 'subscriber.subscribes_table';

    protected $fillable = [
        'email', 'client_id', 'user_agent', 'is_subscribe'
    ];

    protected $casts = [
        'is_subscribe' => 'boolean'
    ];
}