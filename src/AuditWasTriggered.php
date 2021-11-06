<?php

namespace Kamel\Auditable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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
