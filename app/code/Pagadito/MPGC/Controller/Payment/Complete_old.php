<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 10/08/2018
 * Time: 02:39 PM
 */

namespace Pagadito\MPGC\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Checkout\Model\Session;
use Pagadito\MPGC\Model\Api;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Framework\DB\Transaction;

class Complete  extends Action
{
    protected $_resultPageFactory;
    private $checkoutSession;
    protected $config;
    private $invoiceSender;
    private $invoiceService;
    private $transaction;

    public function __construct(Context $context,
                                Session $checkoutSession,
                                PageFactory $resultPageFactory,
                                ConfigInterface $config,
                                InvoiceService $invoiceService,
                                InvoiceSender $invoiceSender,
                                Transaction $transaction)
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->invoiceSender = $invoiceSender;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
    }

    public function execute()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        $pg = new Api();
        if($this->config->getValue('sandbox_mode', $order->getStoreId()) == 1){
            $pg->mode_sandbox_on();
            $uid = $this->config->getValue('pg_sandbox_uid', $order->getStoreId());
            $wsk = $this->config->getValue('pg_sandbox_wsk', $order->getStoreId());
        } else {
            $uid = $this->config->getValue('pg_production_uid', $order->getStoreId());
            $wsk = $this->config->getValue('pg_production_wsk', $order->getStoreId());
        }
        $pg->setParams($uid, $wsk);
        $pg->connect();
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/pg.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($order->getIncrementId());
        $titulo = '';
        $completada = false;
        $pg->get_status($this->getRequest()->getParam('p'));
        $referencia = $pg->get_rs_reference();
        $fecha_pago = $pg->get_rs_date_trans();
        switch ($pg->get_rs_status()){
            case 'COMPLETED':
                $titulo = 'Su compra fue exitosa';
                $completada = true;
                if($order->canInvoice()) {
                    $invoice = $this->invoiceService->prepareInvoice($order);
                    $invoice->register();
                    $invoice->save();
                    $transactionSave = $this->transaction->addObject(
                        $invoice
                    )->addObject(
                        $invoice->getOrder()
                    );
                    $transactionSave->save();
                    $this->invoiceSender->send($invoice);
                    //send notification code
                    $order->addStatusHistoryComment(
                        __('Notificacion de invoice realizada.', $invoice->getId())
                    )
                        ->setIsCustomerNotified(true)
                        ->save();
                } else {
                    $order->addStatusHistoryComment(
                        __('Invoice no pudo ser creado.')
                    );
                }
                $orderState = Order::STATE_COMPLETE;
                $order->setState($orderState)->setStatus(Order::STATE_COMPLETE);
                $model = $this->_objectManager->create('Pagadito\MPGC\Model\PagaditoOrder');
                $model->load($order->getIncrementId())->addData([
                    "pagadito_ref" => $referencia
                ]);
                $model->save();
                break;
            case 'REGISTERED':
                $orderState = Order::STATE_CANCELED;
                $order->setState($orderState)->setStatus(Order::STATE_CANCELED);
                $titulo = 'Su compra fue cancelada';
                break;
            case 'VERIFYING':
                $orderState = Order::STATE_PAYMENT_REVIEW;
                $order->setState($orderState)->setStatus(Order::STATE_PAYMENT_REVIEW);
                $titulo = 'Su compra fue se encuentra en revisión';
                break;
            default:
                $orderState = Order::STATE_CANCELED;
                $order->setState($orderState)->setStatus(Order::STATE_CANCELED);
                $titulo = 'Su compra fue cancelada';
        }

        $order->save();

        $resultPage = $this->_resultPageFactory->create();
        $resultPage->addHandle('pagadito_payment_complete');
        $resultPage->getLayout()->getBlock('complete')->setReferencia($referencia);
        $resultPage->getLayout()->getBlock('complete')->setTitulo($titulo);
        $resultPage->getLayout()->getBlock('complete')->setFechaPago($fecha_pago);
        $resultPage->getLayout()->getBlock('complete')->setCompletada($completada);

        return $resultPage;
    }
}