<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 0:42
 */

namespace TopoTrue\Pheeltrator\Query;


use TopoTrue\Pheeltrator\Query\Builder\BuilderInterface;
use TopoTrue\Pheeltrator\Query\Builder\Helper;
use TopoTrue\Pheeltrator\Query\Column\Column;
use TopoTrue\Pheeltrator\Query\Column\ColumnInterface;
use TopoTrue\Pheeltrator\Query\Source\Join;
use TopoTrue\Pheeltrator\Query\Source\SourceInterface;
use TopoTrue\Pheeltrator\Request\Parser\ParserInterface;


/**
 * Class Manager
 * @package HorseTop\Repositories\Phalcon
 */
class Manager
{
    const CONDITION_LIKE     = 'like';
    const CONDITION_EQUAL    = 'equal';
    const CONDITION_BETWEEN  = 'between';
    const CONDITION_MASK_OR  = 'mask_or'; // partial (or) comparision
    const CONDITION_MASK_AND = 'mask_and'; // full (and) comparision
    const CONDITION_IN       = 'in';
    
    /**
     * @var ParserInterface
     */
    protected $parser;
    
    /**
     * @var BuilderInterface
     */
    protected $builder;
    
    /**
     * @var SourceBag
     */
    protected $sourceBag;
    
    /**
     * @var array
     */
    protected $filtered_sources = [];
    
    /**
     * @var array
     */
    protected $joined_sources = [];
    
    /**
     * @var array
     */
    protected $group_by_applied_sources = [];
    
    /**
     * Manager constructor.
     * @param SourceBag $sourceBag
     * @param ParserInterface $parser
     * @param BuilderInterface $builder
     */
    public function __construct(SourceBag $sourceBag, ParserInterface $parser, BuilderInterface $builder)
    {
        $this->parser    = $parser;
        $this->builder   = $builder;
        $this->sourceBag = $sourceBag;
    }
    
    /**
     *
     */
    private function applyExpressions()
    {
        foreach ($this->sourceBag->getColumns() as $column) {
            if ($column->hasExpression() && $this->parser->hasFilter($column->getName())) {
                switch ($column->getExpression()) {
                    case self::CONDITION_BETWEEN:
                        $this->addBetween($column);
                        break;
                    case self::CONDITION_EQUAL:
                        $this->addEqual($column);
                        break;
                    case self::CONDITION_LIKE:
                        $this->addLike($column);
                        break;
                    case self::CONDITION_MASK_OR:
                        $this->addMask($column, false);
                        break;
                    case self::CONDITION_MASK_AND:
                        $this->addMask($column, true);
                        break;
                    case self::CONDITION_IN:
                        $this->addIn($column);
                        break;
                    default:
                        break;
                }
            }
        }
    }
    
    /**
     * @param ColumnInterface $column
     * @return mixed|null
     */
    private function prepareValue(ColumnInterface $column)
    {
        return $column->hasTransform()
            ? $column->transform($this->parser->getFilter($column->getName()))
            : $this->parser->getFilter($column->getName());
    }
    
    /**
     * @param ColumnInterface $column
     */
    protected function addBetween(ColumnInterface $column)
    {
        $values = $this->prepareValue($column);
        $col    = $column->forSearch();
        $key    = str_replace('.', '_', $col);
        
        if (strlen($values[0]) < 1) {
            $values[0] = $this->getMinValue($column);
        }
        
        if (strlen($values[1]) < 1) {
            $values[1] = $this->getMaxValue($column);
        }
        
        // TODO: добавить hasAggregate во все add* методы
        if ($column->hasAggregate()) {
            $this->builder->andHaving("( {$column->getFullAggregateExpr()} BETWEEN :{$key}_1 AND :{$key}_2 )", [
                ":{$key}_1" => $column->isDate() ? date('Y-m-d', strtotime($values[0])) : $values[0],
                ":{$key}_2" => $column->isDate() ? date('Y-m-d', strtotime($values[1]))." 23:59:59" : $values[1],
            ]);
        } else {
            $this->builder->andWhere("( {$col} BETWEEN :{$key}_1 AND :{$key}_2 )", [
                ":{$key}_1" => $column->isDate() ? date('Y-m-d', strtotime($values[0])) : $values[0],
                ":{$key}_2" => $column->isDate() ? date('Y-m-d', strtotime($values[1]))." 23:59:59" : $values[1],
            ]);
        }
        $this->filtered_sources[] = $column->getSource()->getName();
    }
    
    /**
     * @param ColumnInterface $column
     * @return int|string
     */
    protected function getMinValue(ColumnInterface $column)
    {
        if ($column->hasMinValue()) {
            return $column->getMinValue();
        }
        switch ($column->getType()) {
            case Column::TYPE_INT:
                return 0;
            case Column::TYPE_DATE:
                return date('j.m.Y', 0);
            default:
                return '';
        }
    }
    
    /**
     * @param ColumnInterface $column
     * @return int|string
     */
    protected function getMaxValue(ColumnInterface $column)
    {
        if ($column->hasMaxValue()) {
            return $column->getMaxValue();
        }
        switch ($column->getType()) {
            case Column::TYPE_INT:
                return 100000;
            case Column::TYPE_DATE:
                return date('j.m.Y');
            default:
                return '';
        }
    }
    
    /**
     * @param ColumnInterface $column
     */
    protected function addIn(ColumnInterface $column)
    {
        $values = (array)$this->prepareValue($column);
        $col    = $column->forSearch();
        $key    = str_replace('.', '_', $col);
        
        $bind = Helper::makeMultipleBind($key, $values);
        
        $this->builder->andWhere("( {$col} IN ({$bind->getPlaceholdersString()}) )", $bind->getValues());
        $this->filtered_sources[] = $column->getSource()->getName();
    }
    
    /**
     * @param ColumnInterface $column
     */
    protected function addLike(ColumnInterface $column)
    {
        $value = $this->prepareValue($column);
        if ((string)$value) {
            $col = $column->forSearch();
            $key = str_replace('.', '_', $col);
            $this->builder->andWhere("( LOWER({$col}) LIKE LOWER(:{$key}_1) OR LOWER({$col}) LIKE LOWER(:{$key}_2) OR LOWER({$col}) LIKE LOWER(:{$key}_3) OR LOWER({$col}) LIKE (:{$key}_4) )", [
                ":{$key}_1" => "{$value}%",
                ":{$key}_2" => "%{$value}%",
                ":{$key}_3" => "%{$value}",
                ":{$key}_4" => "{$value}",
            ]);
            $this->filtered_sources[] = $column->getSource()->getName();
        }
    }
    
    /**
     * @param ColumnInterface $column
     */
    protected function addEqual(ColumnInterface $column)
    {
        $value = $this->prepareValue($column);
        $col   = $column->forSearch();
        $key   = str_replace('.', '_', $col);
        $this->builder->andWhere("( {$col} = :{$key}_1 )", [
            ":{$key}_1" => $value,
        ]);
        $this->filtered_sources[] = $column->getSource()->getName();
    }
    
    /**
     * @param ColumnInterface $column
     * @param bool $and_comparision
     */
    protected function addMask(ColumnInterface $column, bool $and_comparision)
    {
        $value = $this->prepareValue($column);
        if (is_array($value)) {
            $val = 0;
            foreach ($value as $item) {
                $val |= 1 << ($item - 1);
            }
        } else {
            $val = 0 | 1 << ($value - 1);
        }
        $col = $column->forSearch();
        $key = str_replace('.', '_', $col);
        if ($and_comparision) {
            $condition = "({$col} & :{$key}_1) = :{$key}_2";
            $binds     = [
                ":{$key}_1" => $val,
                ":{$key}_2" => $val,
            ];
        } else {
            $condition = "{$col} & :{$key}_1";
            $binds     = [
                ":{$key}_1" => $val,
            ];
        }
        $this->builder->andWhere("({$condition})", $binds);
        $this->filtered_sources[] = $column->getSource()->getName();
    }
    
    /**
     * @return bool
     */
    public function hasFilters()
    {
        return ! empty($this->filtered_sources);
    }
    
    /**
     * @param SourceInterface $source
     * @return bool
     */
    protected function isFilteredSource($source)
    {
        return in_array($source->getName(), $this->filtered_sources);
    }
    
    /**
     * @param SourceInterface $source
     * @return bool
     */
    protected function isJoinedSource(SourceInterface $source)
    {
        return in_array($source->getName(), $this->joined_sources);
    }
    
    /**
     * @param SourceInterface $source
     * @return bool
     */
    protected function isSourceGroupByApplied(SourceInterface $source)
    {
        return in_array($source->getName(), $this->group_by_applied_sources);
    }
    
    /**
     * @param Join $join
     */
    private function applyJoin(Join $join)
    {
        if (! $this->isJoinedSource($join->getSource())) {
            
            if ($join->hasJoiner() && $join->getJoiner() !== $this->sourceBag->getSource() && ! $this->isJoinedSource($join->getJoiner())) {
                $joinerJoin = $this->sourceBag->getJoinBySourceName($join->getJoiner()->getName());
                if (! is_null($joinerJoin)) {
                    $this->applyJoin($joinerJoin);
                }
            }
            
            $this->builder->join(
                $this->sourceBag->getSource()->getAlias(),
                $join->getSource()->getName(),
                $join->getCondition(),
                $join->getSource()->getAlias(),
                $join->getType()
            );
            
            $this->applyGroupBy($join->getSource());
            
            $this->joined_sources[] = $join->getSource()->getName();
        }
    }
    
    /**
     * @param SourceInterface $source
     */
    private function attachGroupBy(SourceInterface $source)
    {
        if ($source->hasGroupByFields() && ! $this->isSourceGroupByApplied($source)) {
            foreach ($source->getGroupByFields(true) as $field) {
                $this->builder->addGroupBy($field);
            }
            $this->group_by_applied_sources[] = $source->getName();
        }
    }
    
    /**
     * @param SourceInterface $source
     */
    private function applyGroupBy(SourceInterface $source = null)
    {
        if (! is_null($source)) {
            if ($this->sourceBag->sourceHasAggregates($source)) {
                $this->attachGroupBy($this->sourceBag->getSource());
            }
        } else {
            foreach ($this->sourceBag->getJoins() as $join) {
                $this->attachGroupBy($join->getSource());
            }
        }
    }
    
    /**
     * @return array
     */
    public function execute()
    {
        $out = [];
        
        $this->builder->from($this->sourceBag->getSource()->getName(), $this->sourceBag->getSource()->getAlias());
        
        if ($this->sourceBag->getSource()->hasWheres()) {
            foreach ($this->sourceBag->getSource()->getWheres() as $where) {
                $this->builder->andWhere($this->sourceBag->getSource()->aliased($where));
            }
        }
        
        $out['total'] = $this->builder->count();
        
        $this->applyExpressions();
        
        if ($this->parser->hasFilters()) {
            // джойним сначала сорсы тока по фильтрам для каунта
            foreach ($this->sourceBag->getJoins() as $join) {
                if ($this->isFilteredSource($join->getSource())) {
                    $this->applyJoin($join);
                }
            }
            $out['filtered'] = $this->builder->count();
        } else {
            $out['filtered'] = $out['total'];
        }
        
        //die();
        
        // джойним все остальные сорсы
        foreach ($this->sourceBag->getJoins() as $join) {
            $this->applyJoin($join);
        }
        
        $this->applyGroupBy();
        
        $this->builder->select($this->sourceBag->getSelect());
        
        $this->builder->limit($this->parser->getLimit(), $this->parser->getOffset());
        
        foreach ($this->parser->getOrder() as $order) {
            $column = $this->sourceBag->getColumns()->getByName($order[0]);
            $this->builder->orderBy($column->getAlias($column->getSortField()), $order[1]);
        }
        
        $items = $this->builder->execute();
        
        $out['_query_'] = [
            'sql_part' => $this->builder->getQueryBasicPart(),
            'values'   => $this->builder->getParameters(),
        ];
        
        $out['data'] = [];
        
        foreach ($items as $i => $item) {
            foreach ($this->sourceBag->getColumns() as $column) {
                $key = isset($item->{$column->getSource()->getAlias()}) ? $column->getSource()->getAlias() : $column->getName();
                
                if (is_array($item)) {
                    
                    if ($column->isMultiField()) {
                        
                        $val = [];
                        foreach ($column->getFields() as $field) {
                            $_key        = $column->getAlias($field);
                            $val[$field] = $item[$_key];
                        }
                        
                    } else {
                        $val = $item[$key];
                    }
                    
                } else {
                    $val = $item->{$key};
                }
                
                $out['data'][$i][$column->getName()] = $column->value($val);
            }
        }
        
        return $out;
        
    }
    
}
