<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 14.04.2017
 * Time: 23:49
 */

namespace TopoTrue\Pheeltrator\Query\Column;


/**
 * Class ColumnInterface
 * @package HorseTop\Repositories\Phalcon
 */
interface ColumnInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getField();
    
    /**
     * @return array
     */
    public function getFields();
    
    /**
     * @return string
     */
    public function getType();
    
    /**
     * @return \TopoTrue\Pheeltrator\Query\Source\SourceInterface
     */
    public function getSource();
    
    /**
     * @return string
     */
    public function getAlias();
    
    /**
     * @return string
     */
    public function aliased($with_as = false);
    
    /**
     * @return string
     */
    public function getExpression();
    
    /**
     * @return callable
     */
    public function getValue();
    
    /**
     * @param mixed $value
     * @return mixed
     */
    public function value($value);
    
    /**
     * @return callable
     */
    public function getTransform();
    
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value);
    
    /**
     * @return bool
     */
    public function hasType();
    
    /**
     * @return bool
     */
    public function hasExpression();
    
    /**
     * @return bool
     */
    public function hasValue();
    
    /**
     * @return bool
     */
    public function hasAlias();
    
    /**
     * @return bool
     */
    public function hasTransform();
    
    /**
     * @return bool
     */
    public function isDate();
}
