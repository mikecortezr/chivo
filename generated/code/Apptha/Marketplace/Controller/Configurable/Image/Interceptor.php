<?php
namespace Apptha\Marketplace\Controller\Configurable\Image;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Configurable\Image
 */
class Interceptor extends \Apptha\Marketplace\Controller\Configurable\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Result\PageFactory $resultPageFactoryObject, \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\LayoutFactory $layoutFactory)
    {
        $this->___init();
        parent::__construct($resultPageFactoryObject, $context, $layoutFactory);
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
