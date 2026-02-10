<?php

namespace Kamel\Auditable\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class AuditDTO extends DataTransferObject
{
    /**
     * @var string
     */
    public string $model_name;


    /**
     * @var int
     */
    public int $model_id;


    /**
     * @var array
     */
    public array $old_values;


    /**
     * @var array
     */
    public array $new_values;


    /**
     * @var string|null
     */
    public ?string $user_type;


    /**
     * @var int|null
     */
    public ?int $user_id;


    /**
     * @var string|null
     */
    public ?string $url;
}
