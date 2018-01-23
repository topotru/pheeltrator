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
     * @param string|null $alias
     * @return BuilderInterface
     */
    public function from($from, $alias = null);
    
    /**
     * @param string $from
     * @param string $source
     * @param string|null $conditions
     * @param string|null $alias
     * @param string|null $type
     * @return BuilderInterface
     */
    public function join($from, $source, $conditions = null, $alias = null, $type = null);
    
    /**
     * @param string $cond
     * @param array|null $bindParams
     * @param array|null $bindTypes
     * @return BuilderInterface
     */
    public function andWhere($cond, $bindParams = null, $bindTypes = null);
    
    /**
     * @param string $cond
     * @param array|null $bindParams
     * @param array|null $bindTypes
     * @return BuilderInterface
     */
    public function andHaving($cond, $bindParams = null, $bindTypes = null);
    
    /**
     * @param array|null $binds
     * @param array|null $types
     * @return mixed
     */
    public function execute(array $binds = [], array $types = []);
    
    /**
     * @param string|null $field
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
    
    /**
     * @param string $groupBy
     * @return BuilderInterface
     */
    public function addGroupBy($groupBy);
    
    /**
     * @return string
     */
    public function getSQL();
    
    /**
     * @return string
     */
    public function getQueryBasicPart();
    
    /**
     * @return array
     */
    public function getParameters();
    
}
