<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 17.04.2017
 * Time: 14:21
 */

namespace TopoTrue\Pheeltrator\Request\Parser;

use TopoTrue\Pheeltrator\Response\ResultInterface;

/**
 * Interface ParserInterface
 * @package TopoTrue\Pheeltrator\Request\Parser
 */
interface ParserInterface
{
    /**
     * @return bool
     */
    public function hasFilters();
    
    /**
     * @return array
     */
    public function getFilters();
    
    /**
     * @param string $key
     * @return bool
     */
    public function hasFilter($key);
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public function getFilter($key);
    
    /**
     * @return bool
     */
    public function hasOrder();
    
    /**
     * @return array
     */
    public function getOrder();
    
    /**
     * @param string $field
     * @param string $direction
     */
    public function addOrder($field, $direction);
    
    /**
     * @param int $default
     * @return int
     */
    public function getLimit($default = 25);
    
    /**
     * @param int $default
     * @return int
     */
    public function getOffset($default = 0);
    
    /**
     * @param string $key
     * @return int|false
     */
    public function getFieldIndex($key);
    
    /**
     * @param string $key
     * @return mixed
     */
    public function getData($key = null);
    
    /**
     * @return ResultInterface
     */
    public function getResultObject(array $items, array $additional = []);
    
}
