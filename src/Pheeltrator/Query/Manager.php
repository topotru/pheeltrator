<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 15.04.2017
 * Time: 0:42
 */

namespace TopoTrue\Pheeltrator\Query;


use TopoTrue\Pheeltrator\Query\Builder\BuilderInterface;
use TopoTrue\Pheeltrator\Query\Column\ColumnInterface;
use TopoTrue\Pheeltrator\Request\Parser\ParserInterface;


/**
 * Class Manager
 * @package HorseTop\Repositories\Phalcon
 */
class Manager
{
    
    const CONDITION_LIKE    = 'like';
    const CONDITION_EQUAL   = 'equal';
    const CONDITION_BETWEEN = 'between';
    
    /**
     * @var bool
     */
    protected $has_filters = false;
    
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
                    default:
                        break;
                }
            }
        }
    }
    
    /**
     * @param \TopoTrue\Pheeltrator\Query\Column\ColumnInterface $column
     * @return mixed|null
     */
    private function prepareValue(ColumnInterface $column)
    {
        return $column->hasTransform()
            ? $column->transform($this->parser->getFilter($column->getName()))
            : $this->parser->getFilter($column->getName());
    }
    
    /**
     * @param \TopoTrue\Pheeltrator\Query\Column\ColumnInterface $column
     */
    protected function addBetween(ColumnInterface $column)
    {
        $values = $this->prepareValue($column);
        $alias  = $column->getSource()->getAlias();
        if (! $values[1]) {
            $values[1] = $column->isDate() ? date('j.m.Y') : $values[0];
        }
        $this->builder->andWhere("( {$alias}.{$column->getField()} BETWEEN :{$column->getField()}_1 AND :{$column->getField()}_2 )", [
            ":{$column->getField()}_1" => $column->isDate() ? date('Y-m-d', strtotime($values[0])) : $values[0],
            ":{$column->getField()}_2" => $column->isDate() ? date('Y-m-d', strtotime($values[1]))." 23:59:59" : $values[1],
        ]);
        
        $this->has_filters        = true;
        $this->filtered_sources[] = $column->getSource()->getName();
    }
    
    /**
     * @param \TopoTrue\Pheeltrator\Query\Column\ColumnInterface $column
     */
    protected function addLike(ColumnInterface $column)
    {
        $value = $this->prepareValue($column);
        if ((string)$value) {
            $key   = $column->getField();
            $alias = $column->getSource()->getAlias();
            $this->builder->andWhere("( {$alias}.{$key} LIKE :{$key}_1 OR {$alias}.{$key} LIKE :{$key}_2 OR {$alias}.{$key} LIKE :{$key}_3 OR {$alias}.{$key} LIKE :{$key}_4 )", [
                ":{$key}_1" => "{$value}%",
                ":{$key}_2" => "%{$value}%",
                ":{$key}_3" => "%{$value}",
                ":{$key}_4" => "{$value}",
            ]);
            $this->has_filters        = true;
            $this->filtered_sources[] = $column->getSource()->getName();
        }
    }
    
    /**
     * @param \TopoTrue\Pheeltrator\Query\Column\ColumnInterface $column
     */
    protected function addEqual(ColumnInterface $column)
    {
        $value = $this->prepareValue($column);
        $alias = $column->getSource()->getAlias();
        $this->builder->andWhere("( {$alias}.{$column->getField()} = :{$column->getField()}_1 )", [
            ":{$column->getField()}_1" => $value,
        ]);
        $this->has_filters        = true;
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
     * @param string $source_name
     * @return bool
     */
    protected function sourceFiltered($source_name)
    {
        return in_array($source_name, $this->filtered_sources);
    }
    
    /**
     * @return mixed
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
        
        $columns = $this->sourceBag->getColumns();
        
        $out['total'] = $this->builder->count();
        
        $this->applyExpressions();
        
        
        // TODO: это вынести
        // джойним сначала сорсы тока по фильтрам для каунта
        foreach ($this->sourceBag->getJoins() as $join) {
            if ($this->sourceFiltered($join->getSource()->getName())) {
                $this->builder->join(
                    $this->sourceBag->getSource()->getAlias(),
                    $join->getSource()->getName(),
                    $join->getCondition(),
                    $join->getSource()->getAlias(),
                    $join->getType()
                );
            }
        }
        
        if ($this->parser->hasFilters()) {
            $out['filtered'] = $this->builder->count();
        } else {
            $out['filtered'] = $out['total'];
        }
        
        // TODO: это вынести
        // потом джойним все остальные
        foreach ($this->sourceBag->getJoins() as $join) {
            if (! $this->sourceFiltered($join->getSource()->getName())) {
                $this->builder->join(
                    $this->sourceBag->getSource()->getAlias(),
                    $join->getSource()->getName(),
                    $join->getCondition(),
                    $join->getSource()->getAlias(),
                    $join->getType()
                );
            }
        }
        
        if ($this->sourceBag->hasGroupBy()) {
            $this->builder->groupBy($this->sourceBag->getGroupBy());
        }
        
        $out['data'] = [];
        
        $this->builder->select($this->sourceBag->getSelect());
        
        $this->builder->limit($this->parser->getLimit(), $this->parser->getOffset());
        
        foreach ($this->parser->getOrder() as $order) {
            $this->builder->orderBy($columns->getByName($order[0])->getAlias(), $order[1]);
        }
        
        $items = $this->builder->execute();
        
        foreach ($items as $i => $item) {
            foreach ($columns as $column) {
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
