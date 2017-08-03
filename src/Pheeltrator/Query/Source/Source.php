<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 3:37
 */

namespace TopoTrue\Pheeltrator\Query\Source;

/**
 * Class Source
 * @package TopoTrue\Pheeltrator\Query
 */
class Source implements SourceInterface
{
    const SELECT_ALL     = 0;
    const SELECT_COLUMNS = 1;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $alias;
    
    /**
     * @var string
     */
    protected $select;
    
    /**
     * @var array
     */
    protected $wheres = [];
    
    /**
     * @var array
     */
    protected $group_by_fields = [];
    
    /**
     * Source constructor.
     * @param string $name
     * @param string $alias
     * @param int $select
     */
    public function __construct($name, array $group_by_fields = [], $alias = null, $select = self::SELECT_COLUMNS)
    {
        $this->name   = $name;
        $this->alias  = ! is_null($alias) ? $alias : strtolower(preg_replace('/[^a-z0-9_]/i', '_', $this->name));
        $this->select = $select;
        //
        $this->group_by_fields = $group_by_fields;
    }
    
    /**
     * @return string
     */
    public function getSelectFields()
    {
        return $this->select;
    }
    
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
    public function getAlias()
    {
        return $this->alias;
    }
    
    /**
     * Return field with source alias
     * @param string $field
     * @return string
     */
    public function aliased($field)
    {
        return "{$this->getAlias()}.{$field}";
    }
    
    /**
     * @param string $where
     */
    public function addWhere($where)
    {
        $this->wheres[] = $where;
    }
    
    /**
     * @return array
     */
    public function getWheres()
    {
        return $this->wheres;
    }
    
    /**
     * @return bool
     */
    public function hasWheres()
    {
        return ! empty($this->wheres);
    }
    
    /**
     * @param bool $aliased
     * @return array
     */
    public function getGroupByFields($aliased = false)
    {
        return $aliased ? array_map(function ($field) {
            return $this->aliased($field);
        }, $this->group_by_fields) : $this->group_by_fields;
    }
    
    /**
     * @return bool
     */
    public function hasGroupByFields()
    {
        return ! empty($this->group_by_fields);
    }
    
}
