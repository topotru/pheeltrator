<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 04.10.2016
 * Time: 19:11
 */

namespace TopoTrue\Pheeltrator\Query\Column;

/**
 * Class ImmutableColumn
 * @package HorseTop\Repositories\Phalcon
 */
class ImmutableColumn extends AbstractColumn
{
    /**
     * ImmutableColumn constructor.
     * @param string $name
     * @param string $field
     * @param string $source
     * @param string $alias
     * @param string $expression
     * @param callable $value
     * @param callable $transform
     * @param string $type
     */
    public function __construct($name, $field, $source, $alias = null, $expression = null, callable $value = null, callable $transform = null, $type = null)
    {
        $this->name       = $name;
        $this->fields[]   = $field;
        $this->type       = $type;
        $this->source     = $source;
        $this->expression = $expression;
        $this->value      = $value;
        $this->transform  = $transform;
        $this->alias      = $alias;
    }
    
}
