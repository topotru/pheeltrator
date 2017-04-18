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
     * Source constructor.
     * @param string $name
     * @param string $alias
     * @param int $select
     */
    public function __construct($name, $alias = null, $select = self::SELECT_COLUMNS)
    {
        $this->name   = $name;
        $this->alias  = $alias ?? strtolower(preg_replace('/[^a-z0-9_]/i', '_', $this->name));
        $this->select = $select;
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
}
