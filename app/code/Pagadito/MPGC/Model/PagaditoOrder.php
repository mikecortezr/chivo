<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 10/08/2018
 * Time: 10:39 AM
 */

namespace Pagadito\MPGC\Model;


class PagaditoOrder extends \Magento\Framework\Model\AbstractModel
{
    public function _construct(){
        $this->_init('Pagadito\MPGC\Model\ResourceModel\PagaditoOrder');
    }
}