<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 16.04.2017
 * Time: 1:09
 */

namespace TopoTrue\Pheeltrator\Query\Source;


interface SourceInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getAlias();
    
    /**
     * Return field with source alias
     * @param string $field
     * @return string
     */
    public function aliased($field);
    
    /**
     * @return string
     */
    public function getSelectFields();
    
    /**
     * @param string $where
     */
    public function addWhere($where);
    
    /**
     * @return array
     */
    public function getWheres();
    
    /**
     * @return bool
     */
    public function hasWheres();
    
}
