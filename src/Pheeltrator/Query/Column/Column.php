<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 16.04.2017
 * Time: 1:13
 */

namespace TopoTrue\Pheeltrator\Query\Column;

use TopoTrue\Pheeltrator\Query\Source\SourceInterface;

/**
 * Class Column
 * @package TopoTrue\Pheeltrator\Column
 */
class Column extends AbstractColumn
{
    /**
     * Column constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $field
     * @return Column
     */
    public function setField($field)
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * @param array $fields
     * @return Column
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param string $search_field
     * @return Column
     */
    public function setSearchField($search_field)
    {
        $this->search_field = $search_field;
        return $this;
    }

    /**
     * @param string $sort_field
     * @return Column
     */
    public function setSortField($sort_field)
    {
        $this->sort_field = $sort_field;
        return $this;
    }

    /**
     * @param string $type
     * @return Column
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param SourceInterface $source
     * @return Column
     */
    public function setSource(SourceInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param string $expression
     * @return Column
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @param callable $value
     * @return Column
     */
    public function setValue(callable $value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param int|string $min_value
     * @return Column
     */
    public function setMinValue($min_value)
    {
        $this->min_value = $min_value;
        return $this;
    }

    /**
     * @param int|string $max_value
     * @return Column
     */
    public function setMaxValue($max_value)
    {
        $this->max_value = $max_value;
        return $this;
    }

    /**
     * @param callable $transform
     * @return Column
     */
    public function setTransform(callable $transform)
    {
        $this->transform = $transform;
        return $this;
    }

    /**
     * @param $aggregate
     * @return Column
     */
    public function setAggregate($aggregate)
    {
        $this->aggregate = $aggregate;
        return $this;
    }

    /**
     * @param string $aggregate_expr
     * @return Column
     */
    public function setAggregateExpr($aggregate_expr)
    {
        $this->aggregate_expr = $aggregate_expr;
        return $this;
    }

    /**
     * @param string $func
     * @return $this
     */
    public function setFunc(string $func)
    {
        $this->func = $func;
        return $this;
    }

}
