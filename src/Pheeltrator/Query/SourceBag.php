<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 16.04.2017
 * Time: 1:55
 */

namespace TopoTrue\Pheeltrator\Query;

use TopoTrue\Pheeltrator\Query\Column\ColumnCollection;
use TopoTrue\Pheeltrator\Query\Column\ColumnCollectionInterface;
use TopoTrue\Pheeltrator\Query\Column\ColumnInterface;
use TopoTrue\Pheeltrator\Query\Source\Join;
use TopoTrue\Pheeltrator\Query\Source\Source;
use TopoTrue\Pheeltrator\Query\Source\SourceInterface;

/**
 * Class SourceBag
 * @package TopoTrue\Pheeltrator\Query
 */
class SourceBag
{
    /**
     * @var SourceInterface
     */
    protected $source;

    /**
     * @var ColumnCollectionInterface
     */
    protected $columns;

    /**
     * @var Join[]
     */
    protected $joins = [];

    /**
     * SourceBag constructor.
     * @param SourceInterface $source
     * @param ColumnCollectionInterface $columns
     */
    public function __construct(SourceInterface $source, ColumnCollectionInterface $columns)
    {
        $this->source  = $source;
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getSelect()
    {
        $out = $this->getSourceSelect($this->source);

        foreach ($this->getJoins() as $join) {
            $out = array_merge($out, $this->getSourceSelect($join->getSource()));
        }

        return $out;

    }

    /**
     * @param SourceInterface $source
     * @return array
     */
    protected function getSourceSelect(SourceInterface $source)
    {
        $out = [];

        if ($source->getSelectFields() == Source::SELECT_ALL) {
            $out[] = $source->aliased('*');
        } else {
            /** @var ColumnInterface $column */
            foreach ($this->getSourceColumns($source) as $column) {
                $fields = [];
                foreach ($column->getFields() as $field) {
                    $alias = $column->getAlias($field);
                    if ($column->hasAggregate()) {
                        $fields[] = "{$column->getFullAggregateExpr()} as {$alias}";
                    } elseif ($column->hasFunc()) {
                        $fields[] = "{$column->getFunc()} as {$alias}";
                    } else {
                        $fields[] = "{$column->getSource()->aliased($field)} as {$alias}";
                    }
                }
                $out[$column->getName()] = implode(',', $fields);
            }
        }
        return $out;
    }

    /**
     * @param SourceInterface $source
     * @return ColumnCollectionInterface
     */
    public function getSourceColumns(SourceInterface $source)
    {
        $columns = new ColumnCollection();
        foreach ($this->getColumns() as $column) {
            if ($column->getSource() === $source) {
                $columns->addColumn($column);
            }
        }
        return $columns;
    }

    /**
     * @param SourceInterface $source
     * @return bool
     */
    public function sourceHasAggregates(SourceInterface $source)
    {
        /** @var ColumnInterface $column */
        foreach ($this->getSourceColumns($source) as $column) {
            if ($column->hasAggregate()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Join $join
     * @return SourceBag
     */
    public function join(Join $join)
    {
        $this->joins[$join->getSource()->getAlias()] = $join;
        return $this;
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return ColumnCollectionInterface|ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return Join[]
     */
    public function getJoins()
    {
        return $this->joins;
    }

    /**
     * @param string $name
     * @return null|Join
     */
    public function getJoinBySourceName($name)
    {
        return isset($this->joins[$name]) ? $this->joins[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasJoin($name)
    {
        return isset($this->joins[$name]);
    }

}
