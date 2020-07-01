<?php
namespace Apptha\Marketplace\Controller\Seller\Subscriptionplans;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Seller\Subscriptionplans
 */
class Interceptor extends \Apptha\Marketplace\Controller\Seller\Subscriptionplans implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Apptha\Marketplace\Helper\Data $marketplaceHelperData)
    {
        $this->___init();
        parent::__construct($context, $marketplaceHelperData);
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
