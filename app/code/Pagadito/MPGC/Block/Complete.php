<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 10/08/2018
 * Time: 03:38 PM
 */

namespace Pagadito\MPGC\Block;


class Complete extends \Magento\Framework\View\Element\Template
{
    public function getTitulo_orden()
    {
        return $this->getTitulo();
    }

    public function getReferencia_orden()
    {
        return 'Número de aprobación Pagadito: <strong>' . $this->getReferencia() . '</strong>';
    }

    public function getFechaPago_orden()
    {
        return 'Fecha de transacción:  <strong>' . $this->getFechaPago() . '</strong>';
    }

    public function completada(){
        return $this->getCompletada();
    }
}