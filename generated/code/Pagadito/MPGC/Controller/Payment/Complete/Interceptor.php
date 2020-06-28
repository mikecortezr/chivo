<?php
namespace Pagadito\MPGC\Controller\Payment\Complete;

/**
 * Interceptor class for @see \Pagadito\MPGC\Controller\Payment\Complete
 */
class Interceptor extends \Pagadito\MPGC\Controller\Payment\Complete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Payment\Gateway\ConfigInterface $config, \Magento\Sales\Model\Service\InvoiceService $invoiceService, \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender, \Magento\Framework\DB\Transaction $transaction)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $resultPageFactory, $config, $invoiceService, $invoiceSender, $transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
