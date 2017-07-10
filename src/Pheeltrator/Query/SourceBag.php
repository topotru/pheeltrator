<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 16.04.2017
 * Time: 1:55
 */

namespace TopoTrue\Pheeltrator\Query;

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
     * @var string
     */
    protected $group_by;
    
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
            foreach ($this->getColumns() as $column) {
                if ($column->getSource() === $source) {
                    $fields = [];
                    foreach ($column->getFields() as $field) {
                        $alias = $column->getAlias($field);
                        if ($column->hasAggregate()) {
                            $fields[] = "{$column->getAggregate()}({$field}) as {$alias}";
                        } else {
                            $fields[] = "{$column->getSource()->aliased($field)} as {$alias}";
                        }
                    }
                    $out[$column->getName()] = implode(',', $fields);
                }
            }
        }
        return $out;
    }
    
    /**
     * @param Join $join
     * @return SourceBag
     */
    public function join(Join $join)
    {
        $this->joins[$join->getSource()->getName()] = $join;
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
    
    /**
     * @return string
     */
    public function getGroupBy()
    {
        return $this->group_by;
    }
    
    /**
     * @param string $group_by
     * @return SourceBag
     */
    public function setGroupBy($group_by)
    {
        $this->group_by = $group_by;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasGroupBy()
    {
        return ! empty($this->getGroupBy());
    }
}
