<?php
namespace Apptha\Marketplace\Controller\Adminhtml\Allpayments\Index;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Adminhtml\Allpayments\Index
 */
class Interceptor extends \Apptha\Marketplace\Controller\Adminhtml\Allpayments\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $viewresult)
    {
        $this->___init();
        parent::__construct($context, $viewresult);
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
