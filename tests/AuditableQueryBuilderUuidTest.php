<?php

namespace Kamel\Auditable\Tests;

use Illuminate\Support\Facades\Event;
use Kamel\Auditable\DTOs\AuditDTO;
use Kamel\Auditable\Events\AuditWasTriggered;
use Kamel\Auditable\Tests\Models\DriverWithUuid;

class AuditableQueryBuilderUuidTest extends AuditableTestCase
{
    /** @test */
    public function audit_was_triggered_event_should_contain_uuid_model_id_when_uuid_model_is_updated()
    {
        $driver = DriverWithUuid::factory()->create();

        Event::fake();

        $driver->update(['name' => 'updated']);

        Event::assertDispatched(AuditWasTriggered::class, function (AuditWasTriggered $event) use ($driver) {
            /** @var AuditDTO $audit */
            $audit = $event->audits[0];

            return $audit->model_name === DriverWithUuid::class &&
                $audit->model_id === $driver->id;
        });
    }


    /** @test */
    public function audit_was_triggered_event_should_contain_uuid_model_id_when_uuid_model_is_created()
    {
        Event::fake();

        DriverWithUuid::create(['name' => 'kamel', 'email' => 'm.kamel@gmail.com', 'age' => 40]);

        Event::assertDispatched(AuditWasTriggered::class, function (AuditWasTriggered $event) {
            /** @var AuditDTO $audit */
            $audit = $event->audits[0];

            return $audit->model_name === DriverWithUuid::class &&
                is_string($audit->model_id) &&
                strlen($audit->model_id) === 36;
        });
    }
}
