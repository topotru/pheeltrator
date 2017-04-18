<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 14.04.2017
 * Time: 23:49
 */

namespace TopoTrue\Pheeltrator\Query\Column;

use TopoTrue\Pheeltrator\Query\Source\SourceInterface;


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
    public function aliased();
    
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
