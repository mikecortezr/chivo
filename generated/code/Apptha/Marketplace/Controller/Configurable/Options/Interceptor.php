<?php
namespace Apptha\Marketplace\Controller\Configurable\Options;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Configurable\Options
 */
class Interceptor extends \Apptha\Marketplace\Controller\Configurable\Options implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($resultPageFactory, $context);
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