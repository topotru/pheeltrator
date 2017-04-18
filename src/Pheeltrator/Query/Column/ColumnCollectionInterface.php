<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 0:44
 */

namespace TopoTrue\Pheeltrator\Query\Column;


/**
 * Class ColumnCollection
 * @package HorseTop\Repositories\Phalcon
 */
interface ColumnCollectionInterface extends \IteratorAggregate
{
    /**
     * @param ColumnInterface $column
     */
    public function addColumn(ColumnInterface $column);
    
    /**
     * @param string $name
     * @return ColumnInterface|null
     */
    public function getByName($name);
    
}
