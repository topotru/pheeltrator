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
     * YadcfResult constructor.
     * @param Yadcf $parser
     * @param array $items
     */
    public function __construct(Yadcf $parser, array $items = [])
    {
        $this->parser = $parser;
        $this->items  = $items;
    }
    
    
    /**
     * @return array
     */
    public function getData()
    {
        return [
            'draw'            => $this->parser->getData('draw'),
            'recordsTotal'    => $this->items['total'],
            'recordsFiltered' => $this->items['filtered'],
            'data'            => $this->items['data'],
        ];
    }
}
