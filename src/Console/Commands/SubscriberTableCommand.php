<?php

namespace Viviniko\Subscriber\Console\Commands;

use Viviniko\Support\Console\CreateMigrationCommand;

class SubscriberTableCommand extends CreateMigrationCommand
{
    /**
     * @var string
     */
    protected $name = 'subscriber:table';

    /**
     * @var string
     */
    protected $description = 'Create a migration for the subscriber service table';

    /**
     * @var string
     */
    protected $stub = __DIR__.'/stubs/subscriber.stub';

    /**
     * @var string
     */
    protected $migration = 'create_subscriber_table';
}
