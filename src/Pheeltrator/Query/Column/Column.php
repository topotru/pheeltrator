<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 16.04.2017
 * Time: 1:13
 */

namespace TopoTrue\Pheeltrator\Query\Column;

use TopoTrue\Pheeltrator\Query\Source\SourceInterface;

/**
 * Class Column
 * @package TopoTrue\Pheeltrator\Column
 */
class Column extends AbstractColumn
{
    /**
     * Column constructor.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    /**
     * @param string $field
     * @return Column
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }
    
    /**
     * @param string $type
     * @return Column
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * @param SourceInterface $source
     * @return Column
     */
    public function setSource(SourceInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * @param string $alias
     * @return Column
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
    
    /**
     * @param string $expression
     * @return Column
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }
    
    /**
     * @param callable $value
     * @return Column
     */
    public function setValue(callable $value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * @param callable $transform
     * @return Column
     */
    public function setTransform(callable $transform)
    {
        $this->transform = $transform;
        return $this;
    }
    
    
}
