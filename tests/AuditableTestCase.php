<?php

namespace Kamel\Auditable\Tests;

use ReflectionMethod;
use ReflectionException;
use Orchestra\Testbench\TestCase;
use Kamel\Auditable\AuditableServiceProvider;

class AuditableTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('audit.drivers.database.connection', 'testing');
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $this->withFactories(__DIR__ . '/Factories');
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
