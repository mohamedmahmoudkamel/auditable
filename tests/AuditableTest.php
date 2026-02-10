<?php

namespace Kamel\Auditable\Tests;

use Kamel\Auditable\QueryBuilders\AuditableQueryBuilder;
use ReflectionException;
use Kamel\Auditable\Tests\Models\Driver;

class AuditableTest extends AuditableTestCase
{
  /**
   * @test
   * @throws ReflectionException
   */
  public function model_should_use_auditable_query_builder_as_base_query_builder()
  {
    $method = $this->makePrivateMethodsAccessible(Driver::class, 'newBaseQueryBuilder');
    $driver = Driver::factory()->create();

    $queryBuilder = $method->invokeArgs($driver, []);

    $this->assertEquals(
      AuditableQueryBuilder::class,
      get_class($queryBuilder)
    );
  }
}
