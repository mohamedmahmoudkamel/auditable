<?php

namespace Kamel\Auditable\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Query\Builder;
use Kamel\Auditable\Events\AuditWasTriggered;
use Kamel\Auditable\Tests\Models\Driver;
use Kamel\Auditable\QueryBuilders\AuditableQueryBuilder;
use Kamel\Auditable\DTOs\AuditDTO;


class AuditableQueryBuilderTest extends AuditableTestCase
{
  /** @test */
  public function class_should_exist()
  {
    $this->assertTrue(class_exists(AuditableQueryBuilder::class));
  }


  /** @test */
  public function class_should_extend_laravel_query_builder()
  {
    $this->assertTrue(is_subclass_of(AuditableQueryBuilder::class, Builder::class));
  }


  /** @test */
  public function it_should_remove_updated_at_attribute_change()
  {
    $auditableQueryBuilder = $this->app->make(AuditableQueryBuilder::class);

    $originalModel = (object)Driver::factory()->create()->toArray();
    $changes = ['name' => 'kamel', 'driver.updated_at' => now()->toDateTimeString()];

    $wantedChanges = $auditableQueryBuilder->removeUnwantedChanges($originalModel, $changes);

    $this->assertIsArray($wantedChanges);
    $this->assertCount(1, $wantedChanges);
    $this->assertArrayHasKey('name', $wantedChanges);
    $this->assertEquals('kamel', $wantedChanges['name']);
  }


  /** @test */
  public function it_should_remove_unchanged_values()
  {
    $auditableQueryBuilder = $this->app->make(AuditableQueryBuilder::class);

    $originalModel = (object)Driver::factory()->create(['name' => 'kamel'])->toArray();
    $changes = ['name' => 'kamel', 'email' => 'm.kamel@dailymealz.com', 'driver.updated_at' => now()->toDateTimeString()];

    $wantedChanges = $auditableQueryBuilder->removeUnwantedChanges($originalModel, $changes);

    $this->assertIsArray($wantedChanges);
    $this->assertCount(1, $wantedChanges);

    $this->assertArrayHasKey('email', $wantedChanges);
    $this->assertEquals('m.kamel@dailymealz.com', $wantedChanges['email']);

    $this->assertArrayNotHasKey('name', $wantedChanges);
  }


  /** @test */
  public function it_should_get_values_before_changes()
  {
    $auditableQueryBuilder = $this->app->make(AuditableQueryBuilder::class);

    $originalModel = (object)Driver::factory()->create(['name' => 'kamel'])->toArray();
    $changes = ['email' => 'm.kamel@dailymealz.com'];

    $valuesBeforeChanges = $auditableQueryBuilder->getValuesBeforeChange($originalModel, $changes);

    $this->assertIsArray($valuesBeforeChanges);
    $this->assertCount(1, $valuesBeforeChanges);
    $this->assertArrayHasKey('email', $valuesBeforeChanges);
    $this->assertEquals($originalModel->email, $valuesBeforeChanges['email']);
  }


  /**
   * @test
   * @throws \ReflectionException
   */
  public function it_should_get_model_name()
  {
    $method = $this->makePrivateMethodsAccessible(Driver::class, 'newBaseQueryBuilder');
    $driver = Driver::factory()->create();

    $queryBuilder = $method->invokeArgs($driver, []);

    $modelName = $queryBuilder->getModelName();

    $this->assertEquals(Driver::class, $modelName);
  }


  /** @test */
  public function audit_was_triggered_event_should_be_fired_when_model_is_created()
  {
    Event::fake();

    Driver::create(['name' => 'kamel', 'email' => 'm.kamel@dailymealz.com', 'age' => 40]);

    Event::assertDispatched(AuditWasTriggered::class, function (AuditWasTriggered $event) {
      /** @var AuditDTO $audit */
      $audit = $event->audits[0];

      return empty($audit->old_values) &&
        $audit->model_name === Driver::class &&
        $audit->new_values['age'] == 40 &&
        $audit->new_values['name'] == 'kamel' &&
        $audit->new_values['email'] == 'm.kamel@dailymealz.com';
    });
  }


  /** @test */
  public function audit_was_triggered_event_should_be_fired_when_model_is_updated()
  {
    Event::fake();

    $changes = ['name' => 'kamel', 'email' => 'm.kamel@dailymealz.com'];
    $driver = Driver::factory()->create();

    $driver->update($changes);

    Event::assertDispatched(AuditWasTriggered::class);
  }
}
