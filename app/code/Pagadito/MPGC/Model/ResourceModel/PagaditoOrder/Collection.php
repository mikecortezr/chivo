<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 10/08/2018
 * Time: 10:42 AM
 */

namespace Pagadito\MPGC\Model\ResourceModel\PagaditoOrder;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct(){
        $this->_init('Pagadito\MPGC\Model\PagaditoOrder','Pagadito\MPGC\Model\ResourceModel\PagaditoOrder');
    }
}