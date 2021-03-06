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
    const INNER = "INNER";
    const FULL  = "FULL OUTER";
    const LEFT  = "LEFT";
    const RIGHT = "RIGHT";
    
    /**
     * @var SourceInterface
     */
    protected $source;
    
    /**
     * @var SourceInterface
     */
    protected $joiner;
    
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
     * @param SourceInterface $joiner
     * @param string $type
     */
    public function __construct(SourceInterface $source, $condition, SourceInterface $joiner = null, $type = self::LEFT)
    {
        $this->source    = $source;
        $this->joiner    = $joiner;
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
    
    /**
     * @return SourceInterface
     */
    public function getJoiner()
    {
        return $this->joiner;
    }
    
    /**
     * @return bool
     */
    public function hasJoiner()
    {
        return null !== $this->joiner;
    }
    
}
