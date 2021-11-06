<?php

namespace Kamel\Auditable;

use Illuminate\Contracts\Container\BindingResolutionException;

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
        return app()->make('auditable.query_builder', [
            'connection' => $connection,
            'grammar' => $connection->getQueryGrammar(),
            'processor' => $connection->getPostProcessor(),
            'modelName' => static::class
        ]);
    }
}
