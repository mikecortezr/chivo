<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 04/08/2018
 * Time: 10:31 PM
 */

namespace Pagadito\MPGC\Controller\Payment;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Sales\Model\Order;
use Pagadito\MPGC\Model\Api;
use Magento\Payment\Gateway\ConfigInterface;

class PaymentLogic extends Action
{
    private $checkoutSession;
    private $config;
    private $pg;
    /**
     * @param Context $context
     * @param Session $checkoutSession
     * @param ConfigInterface $config
     */
    public function __construct(Context $context, Session $checkoutSession, ConfigInterface $config) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->config = $config;
        $this->pg = new Api();
    }

    public function execute()
    {
        /** @var Order $order */
        $order = $this->checkoutSession->getLastRealOrder();
        $orderState = Order::STATE_PENDING_PAYMENT;
        $order->setState($orderState)->setStatus(Order::STATE_PENDING_PAYMENT);
        $order->save();
        $orderStoreId = $order->getStoreId();
        $ern = $order->getIncrementId();
        $currency = $this->setCurrency($order->getOrderCurrencyCode(), $orderStoreId);

        $this->setUidWsk($orderStoreId);
        $this->pg->connect();

        $token = $this->pg->get_rs_value();
        //guardando registro en db
        $this->saveOrderData($ern, $token, $currency);

        $data = [
            "order_id" => $ern,
            "ern" => $ern,
            "pagadito_token" => $token,
            "currency" => $this->config->getValue('currency', $order->getStoreId()),
            "date" => (string)date('Y-m-d H:i:s')
        ];
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/pg.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($data);

        $this->addDetails($order);
        $url = $this->pg->exec_trans($ern, false);

        if ($url == '') {
            $url = $this->_url->getUrl('pagadito/payment/complete');
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($url);
        return $resultRedirect;
    }

    private function setUidWsk($orderStoreId){
        if($this->config->getValue('sandbox_mode', $orderStoreId) == 1){
            $this->pg->mode_sandbox_on();
            $uid = $this->config->getValue('pg_sandbox_uid', $orderStoreId);
            $wsk = $this->config->getValue('pg_sandbox_wsk', $orderStoreId);
        } else {
            $uid = $this->config->getValue('pg_production_uid', $orderStoreId);
            $wsk = $this->config->getValue('pg_production_wsk', $orderStoreId);
        }
        $this->pg->setParams($uid, $wsk);
    }

    private function addDetails($order){
        $items = $order->getAllItems();
        foreach ($items as $itemId => $item){
            $tmp_amount = str_replace(',', '', round($item->getPrice(), 2));
            $tmp_amount = (string) number_format($tmp_amount, 2, '.', '');
            $this->pg->add_detail($item->getQtyToInvoice(), $item->getName(), $tmp_amount, "");
        }

        if($order->getBaseShippingAmount() > 0){
            $tmp_amount = str_replace(',', '', round($order->getBaseShippingAmount(), 2));
            $tmp_amount = (string) number_format($tmp_amount, 2, '.', '');
            $this->pg->add_detail(1, $order->getShippingDescription(), $tmp_amount, "");
        }

        /**
         * Agregando taxes si este se encuentra disponible
         */
        if($order->getBaseTaxAmount() > 0){
            $tmp_amount = str_replace(',', '', round($order->getBaseTaxAmount(), 2));
            $tmp_amount = (string) number_format($tmp_amount, 2, '.', '');
            $this->pg->add_detail(1, 'tax', $tmp_amount, "");
        }
    }

    private function setCurrency($orderCurrencyCode, $orderStoreId){
        $currency = $this->config->getValue('currency', $orderStoreId);

        if($currency == '---'){
            $currency = strtoupper($orderCurrencyCode);
        }

        switch ($currency) {
            case 'GTQ':
                $this->pg->change_currency_gtq();
                break;
            case 'HNL':
                $this->pg->change_currency_hnl();
                break;
            case 'NIO':
                $this->pg->change_currency_nio();
                break;
            case 'CRC':
                $this->pg->change_currency_crc();
                break;
            case 'PAB':
                $this->pg->change_currency_pab();
                break;
            case 'DOP':
                $this->pg->change_currency_dop();
                break;
            default:
                $this->pg->change_currency_usd();
                break;
        }
        return $currency;
    }

    private function saveOrderData($ern, $token, $currency){
        $recurso = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
        $conn = $recurso->getConnection();
        $table_name = $recurso->getTableName('pagadito_order');

        $sql = 'INSERT INTO '.$table_name.' (`order_id`, `ern`, `pagadito_token`, `currency`, `date`) VALUES ("'.$ern.'", "'.$ern.'", "'.$token.'", "'.$currency.'", "'.date('Y-m-d H:i:s').'")';
        $conn->query($sql);
    }
}