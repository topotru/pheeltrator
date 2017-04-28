<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 17.04.2017
 * Time: 14:22
 */

namespace TopoTrue\Pheeltrator\Request\Parser;
use TopoTrue\Pheeltrator\Response\YadcfResult;

/**
 * Class Yadcf
 * @package TopoTrue\Pheeltrator\Request\Parser
 */
class Yadcf extends Parser
{
    
    const DELIMITER = '-yadcf_delim-';
    
    const KEY_COLUMNS   = 'columns';
    const KEY_SEARCH    = 'search';
    const KEY_DATA      = 'data';
    const KEY_VALUE     = 'value';
    const KEY_ORDER     = 'order';
    const KEY_COLUMN    = 'column';
    const KEY_DIRECTION = 'dir';
    const KEY_LENGTH    = 'length';
    const KEY_START     = 'start';
    
    /**
     *
     */
    protected function parse()
    {
        if (! $this->data) {
            return;
        }
        
        if (isset($this->data[self::KEY_COLUMNS]) && ! empty($this->data[self::KEY_COLUMNS]) && is_array($this->data[self::KEY_COLUMNS])) {
            foreach ($this->data[self::KEY_COLUMNS] as $i => $column) {
                
                if (! isset($column[self::KEY_SEARCH]) || ! isset($column[self::KEY_SEARCH][self::KEY_VALUE]) || ! isset($column[self::KEY_DATA])) {
                    continue;
                }
                
                $this->fields[$i] = $column[self::KEY_DATA];
                
                // todo проверять $column[self::KEY_DATA] a-z0-9-_.
                
                $val = trim($column[self::KEY_SEARCH][self::KEY_VALUE], " \t\n\r\0\x0B\/");
                
                if ($val !== "" && $val != self::DELIMITER) {
                    
                    if (stripos($val, self::DELIMITER) !== false) {
                        
                        $vals = explode(self::DELIMITER, $val);
                        //
                        $this->filters[$column[self::KEY_DATA]] = $vals;
                        
                    } else {
                        $this->filters[$column[self::KEY_DATA]] = $val;
                    }
                }
            }
            
            if (isset($this->data[self::KEY_ORDER]) && is_array($this->data[self::KEY_ORDER]) && ! empty($this->data[self::KEY_ORDER])) {
                foreach ($this->data[self::KEY_ORDER] as $i => $order) {
                    if (! isset($this->data[self::KEY_ORDER][$i][self::KEY_COLUMN]) || ! isset($this->data[self::KEY_ORDER][$i][self::KEY_DIRECTION])) {
                        continue;
                    }
                    $order_by  = $this->data[self::KEY_COLUMNS][$this->data[self::KEY_ORDER][$i][self::KEY_COLUMN]][self::KEY_DATA];
                    $direction = in_array(strtolower($this->data[self::KEY_ORDER][$i][self::KEY_DIRECTION]), ['asc', 'desc'])
                        ? $this->data[self::KEY_ORDER][$i][self::KEY_DIRECTION]
                        : 'desc';
                    
                    $this->order[$i] = [$order_by, $direction];
                    
                }
            }
            
        }
        
        if (isset($this->data[self::KEY_LENGTH]) && $this->data[self::KEY_LENGTH]) {
            $this->limit = (int)$this->data[self::KEY_LENGTH];
        }
        
        if (isset($this->data[self::KEY_START]) && $this->data[self::KEY_START]) {
            $this->offset = (int)$this->data[self::KEY_START];
        }
        
    }
    
    /**
     * @param array $items
     * @return YadcfResult
     */
    public function getResultObject(array $items, array $additional = [])
    {
        return new YadcfResult($this, $items, $additional);
    }
    
}
