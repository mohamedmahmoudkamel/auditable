<?php

namespace Kamel\Auditable\Tests;

use ReflectionException;
use Kamel\Auditable\Tests\Models\Driver;
use Kamel\Auditable\AuditableQueryBuilder;

class AuditableTest extends AuditableTestCase
{
    /**
     * @test
     * @throws ReflectionException
     */
    public function model_should_use_auditable_query_builder_as_base_query_builder()
    {
        $method = $this->makePrivateMethodsAccessible(Driver::class, 'newBaseQueryBuilder');
        $driver = factory(Driver::class)->create();

        $queryBuilder = $method->invokeArgs($driver, []);

        $this->assertEquals(
            AuditableQueryBuilder::class,
            get_class($queryBuilder)
        );
    }
}
