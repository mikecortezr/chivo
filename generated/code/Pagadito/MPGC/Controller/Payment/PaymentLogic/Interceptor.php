<?php
namespace Pagadito\MPGC\Controller\Payment\PaymentLogic;

/**
 * Interceptor class for @see \Pagadito\MPGC\Controller\Payment\PaymentLogic
 */
class Interceptor extends \Pagadito\MPGC\Controller\Payment\PaymentLogic implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Payment\Gateway\ConfigInterface $config)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $config);
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
