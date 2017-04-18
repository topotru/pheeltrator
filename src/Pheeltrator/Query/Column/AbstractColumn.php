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
     * @var string
     */
    protected $field;
    
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
        return $this->field;
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
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
    
    /**
     * @return string
     */
    public function aliased()
    {
        //return "{$this->getSource()->getAlias()}.{$this->getField()}";
        return $this->getSource()->aliased($this->getField());
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
}
