<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 16.04.2017
 * Time: 2:00
 */

namespace TopoTrue\Pheeltrator\Query\Source;

/**
 * Class Join
 * @package TopoTrue\Pheeltrator\Query
 */
class Join
{
    
    const INNER = 1;
    const OUTER = 2;
    const LEFT  = 3;
    const RIGHT = 4;
    
    /**
     * @var SourceInterface
     */
    protected $source;
    
    /**
     * @var string
     */
    protected $condition;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * Join constructor.
     * @param SourceInterface $source
     * @param string $condition
     * @param string $type
     */
    public function __construct(SourceInterface $source, $condition, $type = self::INNER)
    {
        $this->source    = $source;
        $this->condition = $condition;
        $this->type      = $type;
    }
    
    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
}
