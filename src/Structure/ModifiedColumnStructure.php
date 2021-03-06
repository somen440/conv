<?php

namespace Howyi\Conv\Structure;

use Howyi\Conv\Structure\ColumnStructure\ColumnStructureInterface;

class ModifiedColumnStructure
{
    public $beforeField;
    public $column;
    public $modifiedAfter;

    /**
     * @param string $beforeField
     * @param ColumnStructureInterface $column
     */
    public function __construct(
        string $beforeField,
        ColumnStructureInterface $column
    ) {
        $this->beforeField = $beforeField;
        $this->column = clone $column;
    }

    /**
     * @return string
     */
    public function generateAddQuery(): string
    {
        $query = ['ADD', 'COLUMN', "`{$this->column->field}`"];
        $query[] = $this->column->generateBaseQuery();
        if ($this->isOrderChanged()) {
            $query[] = $this->modifiedAfter;
        }
        return implode(' ', $query);
    }

    /**
     * @return string
     */
    public function generateChangeQuery(): string
    {
        $query = ['CHANGE', "`{$this->beforeField}`", "`{$this->column->field}`"];
        $query[] = $this->column->generateBaseQuery();
        if ($this->isOrderChanged()) {
            $query[] = $this->modifiedAfter;
        }
        return implode(' ', $query);
    }

    /**
     * @return ColumnStructureInterface
     */
    public function getColumn(): ColumnStructureInterface
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getBeforeField(): string
    {
        return $this->beforeField;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->column->field;
    }

    /**
     * @return bool
     */
    public function isRenamed(): bool
    {
        return $this->beforeField !== $this->column->field;
    }

    /**
     * @return bool
     */
    public function isOrderChanged(): bool
    {
        return !is_null($this->modifiedAfter);
    }

    /**
     * @param string|null $modifiedAfter
     */
    public function setModifiedAfter($modifiedAfter)
    {
        if (is_null($modifiedAfter)) {
            $this->modifiedAfter = 'FIRST';
        } else {
            $this->modifiedAfter = "AFTER `$modifiedAfter`";
        }
    }
}
