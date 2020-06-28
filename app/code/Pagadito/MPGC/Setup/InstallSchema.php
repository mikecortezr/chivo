<?php

namespace Pagadito\MPGC\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'pagadito_order'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('pagadito_order')
        )->addColumn(
            'order_id',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false, 'primary' => true],
            'Order ID'
        )->addColumn(
            'ern',
            Table::TYPE_TEXT,
            45,
            ['nullable' => false],
            'numero de comprobante'
        )->addColumn(
            'pagadito_token',
            Table::TYPE_TEXT,
            32,
            [],
            'Token devuelto por Pagadito'
        )->addColumn(
            'pagadito_ref',
            Table::TYPE_TEXT,
            32,
            [],
            'Referencia pagadito'
        )->addColumn(
            'currency',
            Table::TYPE_TEXT,
            4,
            [],
            'Currency utilizada para el pago'
        )->addColumn(
            'date',
            Table::TYPE_TIMESTAMP,
            'null',
            [],
            'Fecha de la compra'
        )->setComment(
            'Pagadito Order Table'
        )->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}