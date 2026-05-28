<?php

namespace Kamel\Auditable\Tests;

use Kamel\Auditable\DTOs\AuditDTO;

class AuditDTOTest extends AuditableTestCase
{
    /** @test */
    public function audit_dto_should_accept_string_model_id()
    {
        $uuid = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';

        $dto = new AuditDTO([
            'model_name' => 'App\Models\Driver',
            'model_id' => $uuid,
            'old_values' => [],
            'new_values' => ['name' => 'kamel'],
            'user_type' => null,
            'user_id' => null,
            'url' => null,
        ]);

        $this->assertEquals($uuid, $dto->model_id);
    }

    /** @test */
    public function audit_dto_should_accept_string_user_id()
    {
        $uuid = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';

        $dto = new AuditDTO([
            'model_name' => 'App\Models\Driver',
            'model_id' => 1,
            'old_values' => [],
            'new_values' => ['name' => 'kamel'],
            'user_type' => 'App\Models\User',
            'user_id' => $uuid,
            'url' => null,
        ]);

        $this->assertEquals($uuid, $dto->user_id);
    }
}
