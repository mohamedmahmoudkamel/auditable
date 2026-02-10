<?php

namespace Kamel\Auditable\Tests;

use ReflectionMethod;
use ReflectionException;
use Orchestra\Testbench\TestCase;
use Kamel\Auditable\Providers\AuditableServiceProvider;

class AuditableTestCase extends TestCase
{
    /**
     * @param $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('audit.drivers.database.connection', 'testing');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }


    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            AuditableServiceProvider::class,
        ];
    }


    /**
     * @param $class
     * @param $method
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function makePrivateMethodsAccessible($class, $method): ReflectionMethod
    {
        $method = new ReflectionMethod($class, $method);
        $method->setAccessible(true);

        return $method;
    }
}
