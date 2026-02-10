<?php

namespace Kamel\Auditable\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Kamel\Auditable\QueryBuilders\AuditableQueryBuilder;

/**
 * @method getConnection()
 */
trait Auditable
{
    /**
     * @return AuditableQueryBuilder
     * @throws BindingResolutionException
     */
    protected function newBaseQueryBuilder(): AuditableQueryBuilder
    {
        $connection = $this->getConnection();

        return new AuditableQueryBuilder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor(),
            static::class
        );
    }
}
