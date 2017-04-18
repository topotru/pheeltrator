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
     * @var \TopoTrue\Pheeltrator\Query\Column\ColumnCollectionInterface
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
    
    public function getSelect()
    {
        $out = $this->getSourceSelect($this->source);
        
        foreach ($this->getJoins() as $join) {
            $out = array_merge($out, $this->getSourceSelect($join->getSource()));
        }
        
        return $out;
        
    }
    
    protected function getSourceSelect(SourceInterface $source)
    {
        $out = [];
        
        if ($source->getSelectFields() == Source::SELECT_ALL) {
            $out[] = $source->aliased('*');
        } else {
            foreach ($this->getColumns() as $column) {
                //echo $column->getName()." | {$column->aliased()}";
                if ($column->getSource() === $source) {
                    $out[$column->getName()] = $column->aliased();
                }
                //echo '<pre>', print_r($out), '</pre>';
            }
        }
        return $out;
    }
    
    /**
     * @param Join $join
     * @return $this
     */
    public function join(Join $join)
    {
        $this->joins[] = $join;
        //$this->sources[] = $join->getSource();
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
     * @return \TopoTrue\Pheeltrator\Query\Column\ColumnCollectionInterface|ColumnInterface[]
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
    
}
