<?php

namespace Kamel\Auditable\QueryBuilders;

use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Illuminate\Contracts\Container\BindingResolutionException;
use Kamel\Auditable\DTOs\AuditDTO;
use Kamel\Auditable\Events\AuditWasTriggered;

class AuditableQueryBuilder extends Builder
{
    /**
     * @var string
     */
    private string $modelName;


    /**
     * @param ConnectionInterface $connection
     * @param Grammar|null $grammar
     * @param Processor|null $processor
     * @param string $modelName
     */
    public function __construct(
        ConnectionInterface $connection,
                            $grammar = null,
        Processor           $processor = null,
        string              $modelName = ''
    )
    {
        parent::__construct($connection, $grammar, $processor);
        $this->modelName = $modelName;
    }


    /**
     * @param array $values
     * @return int
     * @throws BindingResolutionException
     */
    public function update(array $values): int
    {
        $models = $this->get();

        $audits = $this->getAudits($models, $values);

        if (!empty($audits)) {
            AuditWasTriggered::dispatch($audits);
        }

        return parent::update($values);
    }


    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param array $values
     * @param string|null $sequence
     * @return int
     * @throws UnknownProperties
     */
    public function insertGetId(array $values, $sequence = null): int
    {
        $id = parent::insertGetId($values, $sequence);
        $userType = is_object(auth()->user()) ? get_class(auth()->user()) : null;

        $audit = new AuditDTO([
            'model_name' => $this->modelName,
            'model_id' => $id,
            'old_values' => [],
            'new_values' => array_merge($values, ['id' => $id]),
            'user_type' => $userType,
            'user_id' => auth()->id(),
            'url' => request()->url()
        ]);

        AuditWasTriggered::dispatch([$audit]);

        return $id;
    }


    /**
     * @param array $values
     * @param Collection $models
     * @return array
     * @throws BindingResolutionException
     */
    protected function getAudits(Collection $models, array $values): array
    {
        $audits = array_map(function ($model) use ($values) {
            $valuesAfterChange = $this->removeUnwantedChanges($model, $values);
            return !empty($valuesAfterChange) ? $this->makeAudit($model, $valuesAfterChange) : null;
        }, $models->toArray());

        return array_filter($audits, function ($audit) {
            return !is_null($audit);
        });
    }


    /**
     * @param Object $originalModel
     * @param array $changedColumns
     * @return array
     */
    public function removeUnwantedChanges(object $originalModel, array $changedColumns): array
    {
        foreach ($changedColumns as $column => $value) {
            $wasColumnChanged = @($originalModel->$column !== $value);
            if (str_contains($column, '.') || !$wasColumnChanged) {
                unset($changedColumns[$column]);
            }
        }

        return $changedColumns;
    }


    /**
     * @param Object $originalModel
     * @param array $valuesAfterChanges
     * @return AuditDTO
     * @throws BindingResolutionException|UnknownProperties
     */
    protected function makeAudit(object $originalModel, array $valuesAfterChanges): AuditDTO
    {
        $valuesBeforeChanges = $this->getValuesBeforeChange($originalModel, $valuesAfterChanges);
        $userType = is_object(auth()->user()) ? get_class(auth()->user()) : null;
        $modelPrimaryKey = app()->make($this->modelName)->getKeyName();

        return new AuditDTO([
            'model_name' => $this->modelName,
            'model_id' => (int)$originalModel->$modelPrimaryKey,
            'old_values' => $valuesBeforeChanges,
            'new_values' => $valuesAfterChanges,
            'user_type' => $userType,
            'user_id' => auth()->id(),
            'url' => request()->url()
        ]);
    }


    /**
     * @param Object $originalModel
     * @param array $changedColumns
     * @return array
     */
    public function getValuesBeforeChange(object $originalModel, array $changedColumns): array
    {
        $changes = [];

        foreach ($changedColumns as $column => $value) {
            if (isset($originalModel->$column))
                $changes[$column] = $originalModel->$column;
        }

        return $changes;
    }


    /**
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }
}
