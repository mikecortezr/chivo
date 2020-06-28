<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 10/08/2018
 * Time: 10:44 AM
 */

namespace Pagadito\MPGC\Model\ResourceModel;


class PagaditoOrder extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct(){
        $this->_init('pagadito_order',  'order_id');
    }
}