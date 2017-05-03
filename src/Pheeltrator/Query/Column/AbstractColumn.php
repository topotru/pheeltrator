<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 0:37
 */

namespace TopoTrue\Pheeltrator\Query\Column;

use TopoTrue\Pheeltrator\Query\Source\SourceInterface;

/**
 * Class AbstractColumn
 * @package HorseTop\Repositories\Phalcon
 */
abstract class AbstractColumn implements ColumnInterface
{
    
    const TYPE_DATE = 'date';
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $fields = [];
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var SourceInterface
     */
    protected $source;
    
    /**
     * @var string
     */
    protected $alias;
    
    /**
     * @var string
     */
    protected $expression;
    
    /**
     * @var callable
     */
    protected $value;
    
    /**
     * Transform before query
     * @var callable
     */
    protected $transform;
    
    /**
     * @var string
     */
    protected $aggregate;
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getField()
    {
        return isset($this->fields[0]) ? $this->fields[0] : null;
    }
    
    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * @return bool
     */
    public function isMultiField()
    {
        return count($this->fields) > 1;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * @param string $field
     * @return string
     */
    public function getAlias($field = null)
    {
        if (is_null($field)) {
            if (empty($this->fields)) {
                return null;
            }
            $field = $this->fields[0];
        }
        return $this->isMultiField() ? "_{$this->getSource()->getAlias()}_{$field}" : $this->getName();
    }
    
    /**
     * @param bool $with_as
     * @return string
     */
    public function aliased($with_as = false)
    {
        //return "{$this->getSource()->getAlias()}.{$this->getField()}";
        return $this->getSource()->aliased($this->getField()).($with_as ? " as {$this->getAlias()}" : '');
    }
    
    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }
    
    /**
     * @return callable
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * @param mixed $value
     * @return mixed
     */
    public function value($value)
    {
        $func = $this->value;
        return is_callable($func) ? $func($value) : $value;
    }
    
    /**
     * @return callable
     */
    public function getTransform()
    {
        return $this->transform;
    }
    
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        $func = $this->transform;
        return is_callable($func) ? $func($value) : $value;
    }
    
    /**
     * @return bool
     */
    public function hasType()
    {
        return ! is_null($this->type);
    }
    
    /**
     * @return bool
     */
    public function hasExpression()
    {
        return ! is_null($this->expression);
    }
    
    /**
     * @return bool
     */
    public function hasAlias()
    {
        return ! is_null($this->alias);
    }
    
    /**
     * @return bool
     */
    public function hasValue()
    {
        return ! is_null($this->value);
    }
    
    /**
     * @return bool
     */
    public function hasTransform()
    {
        return ! is_null($this->transform);
    }
    
    /**
     * @return bool
     */
    public function isDate()
    {
        return $this->type === self::TYPE_DATE;
    }
    
    /**
     * @return string
     */
    public function getAggregate()
    {
        return $this->aggregate;
    }
    
    /**
     * @return bool
     */
    public function hasAggregate()
    {
        return ! empty($this->aggregate);
    }
}
