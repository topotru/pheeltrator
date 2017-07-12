<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 14.04.2017
 * Time: 23:48
 */

namespace TopoTrue\Pheeltrator\Query\Column;


use Traversable;

/**
 * Class ColumnCollection
 * @package HorseTop\Repositories\Phalcon
 */
class ColumnCollection implements ColumnCollectionInterface
{
    /**
     * @var ColumnInterface[]
     */
    protected $columns = [];
    
    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->columns);
    }
    
    /**
     * @param ColumnInterface $column
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->columns[] = $column;
    }
    
    /**
     * @param string $name
     * @return ColumnInterface|null
     */
    public function getByName($name)
    {
        if (false !== strpos($name, '.')) {
            $name = strstr($name, '.', true);
        }
        foreach ($this->columns as $column) {
            if ($column->getName() == $name) {
                return $column;
            }
        }
        return null;
    }
}
