<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 17.04.2017
 * Time: 14:16
 */

namespace TopoTrue\Pheeltrator\Response;


use TopoTrue\Pheeltrator\Request\Parser\Yadcf;

/**
 * Class YadcfResult
 * @package TopoTrue\Pheeltrator\Response
 */
class YadcfResult implements ResultInterface
{
    /**
     * @var Yadcf
     */
    protected $parser;
    
    /**
     * @var array
     */
    protected $items;
    
    /**
     * @var array
     */
    protected $additional;
    
    /**
     * YadcfResult constructor.
     * @param Yadcf $parser
     * @param array $items
     * @param array $additional
     */
    public function __construct(Yadcf $parser, array $items = [], array $additional = [])
    {
        $this->parser     = $parser;
        $this->items      = $items;
        $this->additional = $additional;
    }
    
    
    /**
     * @return array
     */
    public function getData()
    {
        return array_merge([
            'draw'            => $this->parser->getData('draw'),
            'recordsTotal'    => $this->items['total'],
            'recordsFiltered' => $this->items['filtered'],
            'data'            => $this->items['data'],
        ], $this->makeAdditional());
    }
    
    /**
     * @return array
     */
    protected function makeAdditional()
    {
        $out = [];
        foreach ($this->additional as $fld => $items) {
            
            $index = $this->parser->getFieldIndex($fld);
            
            if (false !== $index) {
                
                $key = "yadcf_data_{$index}";
                
                foreach ($items as $_k => $_v) {
                    $out[$key][] = ["value" => "{$_k}", "label" => "{$_v}"];
                }
                
            } else {
                $out[$fld] = $items;
            }
            
        }
        return $out;
    }
}
