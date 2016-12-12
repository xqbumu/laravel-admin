<?php

namespace Encore\Admin\Commands;

use Encore\Admin\Facades\Admin;
use Illuminate\Console\Command;

class CreateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:create';

    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'admin:create
                            {intendant : Wath the intendant member should be named}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the admin package';

    /**
     * Create directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $intendant = $this->argument('intendant');

        $this->initIntendantDirectory($intendant);

        $this->publishDatabase($intendant);
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function publishDatabase($intendant = 'admin')
    {
        $connection = config('intendant.'.$intendant.'.database.connection') ?: config('database.default');
        
        $this->call('migrate', [
            '--database' => $connection,
            '--path' => str_replace(base_path(), '', __DIR__).'/../../migrations/',
        ]);

        $this->call('db:seed', [
            '--database' => $connection,
            '--class' => config('intendant.'.$intendant.'.database.users_seed')
        ]);
    }

    /**
     * Initialize the admin directory.
     *
     * @return void
     */
    protected function initIntendantDirectory($intendant = 'admin')
    {
        $this->call('admin:intendant', [
            'intendant' => $intendant,
        ]);
    }
}
