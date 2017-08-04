<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 04.08.2017
 * Time: 17:50
 */

namespace TopoTrue\Pheeltrator\Query\Builder;

/**
 * DTO
 * Class MultipleBind
 * @package TopoTrue\Pheeltrator\Query\Builder
 */
class MultipleBind
{
    /**
     * @var array
     */
    protected $placeholders = [];
    
    /**
     * @var array
     */
    protected $values = [];
    
    /**
     * MultipleBind constructor.
     * @param array $placeholders
     * @param array $values
     */
    public function __construct(array $placeholders, array $values)
    {
        $this->placeholders = $placeholders;
        $this->values       = $values;
    }
    
    /**
     * @return array
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }
    
    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
    
    /**
     * @return string
     */
    public function getPlaceholdersString()
    {
        return implode(',', $this->getPlaceholders());
    }
    
}
