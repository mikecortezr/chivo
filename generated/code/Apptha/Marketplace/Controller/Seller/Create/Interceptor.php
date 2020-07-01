<?php
namespace Apptha\Marketplace\Controller\Seller\Create;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Seller\Create
 */
class Interceptor extends \Apptha\Marketplace\Controller\Seller\Create implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Customer\Model\Registration $registration)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $resultPageFactory, $registration);
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
