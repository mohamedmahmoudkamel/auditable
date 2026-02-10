<?php

namespace Kamel\Auditable\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Kamel\Auditable\DTOs\AuditDTO;

class AuditWasTriggered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var array
     */
    public array $audits;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $audits)
    {
        $this->audits = $audits;
    }
}
