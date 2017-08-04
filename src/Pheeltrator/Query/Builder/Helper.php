<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 04.08.2017
 * Time: 17:31
 */

namespace TopoTrue\Pheeltrator\Query\Builder;

/**
 * Class Helper
 * @package TopoTrue\Pheeltrator\Query\Builder
 */
class Helper
{
    /**
     * @param string $key
     * @param array $data
     * @return MultipleBind
     */
    public static function makeMultipleBind($key, array $data)
    {
        $placeholders = [];
        $values       = [];
        
        foreach ($data as $i => $value) {
            $placeholders[$i]          = ":{$key}_{$i}";
            $values[$placeholders[$i]] = $value;
        }
        
        return new MultipleBind($placeholders, $values);
    }
}
