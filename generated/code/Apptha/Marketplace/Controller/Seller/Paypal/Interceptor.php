<?php
namespace Apptha\Marketplace\Controller\Seller\Paypal;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Seller\Paypal
 */
class Interceptor extends \Apptha\Marketplace\Controller\Seller\Paypal implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Apptha\Marketplace\Helper\Data $marketplaceData)
    {
        $this->___init();
        parent::__construct($context, $marketplaceData);
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
