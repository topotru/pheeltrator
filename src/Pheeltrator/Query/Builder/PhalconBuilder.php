<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 2:11
 */

namespace TopoTrue\Pheeltrator\Query\Builder;


/**
 * Class PhalconBuilder
 * @package HorseTop\Repositories\Phalcon
 */
class PhalconBuilder extends \Phalcon\Mvc\Model\Query\Builder implements BuilderInterface
{
    
    /**
     * @param array $binds
     * @return mixed
     */
    public function execute(array $binds = [])
    {
        return $this->getQuery()->execute($binds);
    }
    
    /**
     * @param string|array $columns
     * @return PhalconBuilder
     */
    public function select($columns)
    {
        $this->columns($columns);
        return $this;
    }
    
    /**
     * @param string $from
     * @param string $alias
     * @return BuilderInterface
     */
    public function from($from, $alias = null)
    {
        $this->addFrom($from, $alias);
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function count()
    {
        $this->select(['COUNT(*) as count']);
        /** @var \Phalcon\Mvc\Model\Resultset\Complex $res */
        $res = $this->execute();
        return $res->getFirst()['count'];
    }
}
