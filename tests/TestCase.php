<?php

namespace Finagin\Comment\Tests;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Finagin\Comment\CommentServiceProvider;
use Finagin\Comment\Tests\Models\Post;
use Finagin\Comment\Tests\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    protected $log;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->setLogger();
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $application
     */
    protected function setUpDatabase($application)
    {
        $application['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        $application['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        include_once __DIR__.'/../database/migrations/create_comments_tables.php.stub';

        (new \CreateCommentsTables())->up();

        User::create(['name' => 'Tester']);
        Post::create(['name' => 'Article']);
    }

    protected function setLogger()
    {
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('build/monolog.log', Logger::WARNING));

        $this->log = $log;
    }

    /**
     * @param \Illuminate\Foundation\Application $application
     *
     * @return array
     */
    public function getPackageProviders($application)
    {
        return [
            CommentServiceProvider::class,
//            ConsoleServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $application
     *
     * @return void
     */
    protected function getEnvironmentSetUp($application)
    {
        $application['config']->set('database.default', 'testing');
        $application['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $application['config']->set('auth.providers.users.model', User::class);
    }
}
