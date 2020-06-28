<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 10/08/2018
 * Time: 09:00 AM
 */

namespace Pagadito\MPGC\Model\Config\Source;


class MPGCCurrency
{
    public function toOptionArray()
    {
        return array(
            array('value' => '---', 'label'=>__('Moneda Base de Magento (Recomendado)')),
            array('value' => 'USD', 'label'=>__('Dolares Americanos')),
            array('value' => 'GTQ', 'label'=>__('Quetzales')),
            array('value' => 'HNL', 'label'=>__('Lempiras')),
            array('value' => 'NIO', 'label'=>__('Cordobas')),
            array('value' => 'CRC', 'label'=>__('Colones Costarricenses')),
            array('value' => 'PAB', 'label'=>__('Balboas')),
            array('value' => 'DOP', 'label'=>__('Pesos Dominicanos')),
        );
    }
}