<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 18.04.2017
 * Time: 0:23
 */

namespace TopoTrue\Pheeltrator\Request\Parser;


/**
 * Class Parser
 * @package TopoTrue\Pheeltrator\Request\Parser
 */
abstract class Parser implements ParserInterface
{
    
    
    /**
     * @var array
     */
    protected $data = [];
    
    /**
     * @var array
     */
    protected $fields = [];
    
    /**
     * @var array
     */
    protected $filters = [];
    
    /**
     * @var array
     */
    protected $order = [];
    
    /**
     * @var int
     */
    protected $limit;
    
    /**
     * @var int
     */
    protected $offset = 0;
    
    /**
     * Yadcf constructor.
     * @param array $data _POST
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->parse();
    }
    
    /**
     * Parse data and fill variables
     * @return void
     */
    abstract protected function parse();
    
    /**
     * @param string $key
     * @return int|false
     */
    public function getFieldIndex($key)
    {
        return array_search($key, $this->fields);
    }
    
    /**
     * @return bool
     */
    public function hasFilters()
    {
        return ! empty($this->filters);
    }
    
    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function hasFilter($key)
    {
        return isset($this->filters[$key]);
    }
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public function getFilter($key)
    {
        return $this->hasFilter($key) ? $this->filters[$key] : null;
    }
    
    /**
     * @return bool
     */
    public function hasOrder()
    {
        return ! empty($this->order);
    }
    
    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * @param string $field
     * @param string $direction
     */
    public function addOrder($field, $direction)
    {
        foreach ($this->order as $item) {
            if ($item[0] == $field) {
                return;
            }
        }
        if ($i = $this->getFieldIndex($field)) {
            $this->order[$i] = [$field, $direction];
        } else {
            $this->order[] = [$field, $direction];
        }
    }
    
    /**
     * @param int $default
     * @return int
     */
    public function getLimit($default = 25)
    {
        return $this->limit ? $this->limit : $default;
    }
    
    /**
     * @param int $default
     * @return int
     */
    public function getOffset($default = 0)
    {
        return $this->offset ? $this->offset : $default;
    }
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public function getData($key = null)
    {
        return is_null($key) ? $this->data : (isset($this->data[$key]) ? $this->data[$key] : null);
    }
    
}
