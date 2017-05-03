<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 0:47
 */

namespace TopoTrue\Pheeltrator\Query\Builder;

/**
 * Interface BuilderInterface
 * @package HorseTop\Repositories\Phalcon
 */
interface BuilderInterface
{
    /**
     * @param string|array $columns
     * @return BuilderInterface
     */
    public function select($columns);
    
    /**
     * @param string $from
     * @param string $alias
     * @return BuilderInterface
     */
    public function from($from, $alias = null);
    
    /**
     * @param string $source
     * @param string $conditions
     * @param string $alias
     * @param string $type
     * @return BuilderInterface
     */
    public function join($from, $source, $conditions = null, $alias = null, $type = null);
    
    /**
     * @param string $cond
     * @param array $bindParams
     * @param array $bindTypes
     * @return mixed
     */
    public function andWhere($cond, $bindParams = null, $bindTypes = null);
    
    /**
     * @param array $binds
     * @return mixed
     */
    public function execute(array $binds = []);
    
    /**
     * @param string $field
     * @return int
     */
    public function count($field = '*');
    
    /**
     * @param int $limit
     * @param int $offset
     * @return BuilderInterface
     */
    public function limit($limit, $offset = null);
    
    /**
     * @param string $orderBy
     * @param string $direction
     * @return BuilderInterface
     */
    public function orderBy($orderBy, $direction);
    
    /**
     * @param string $groupBy
     * @return BuilderInterface
     */
    public function groupBy($groupBy);
    
}
