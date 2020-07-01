<?php
namespace WeltPixel\OwlCarouselSlider\Controller\Recentlyviewed\Index;

/**
 * Interceptor class for @see \WeltPixel\OwlCarouselSlider\Controller\Recentlyviewed\Index
 */
class Interceptor extends \WeltPixel\OwlCarouselSlider\Controller\Recentlyviewed\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Json\Helper\Data $jsonHelper, \Psr\Log\LoggerInterface $logger, \Magento\Reports\Block\Product\Widget\Viewed\Proxy $viewProductsBlock, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $jsonHelper, $logger, $viewProductsBlock, $resultJsonFactory, $resultRawFactory, $resultPageFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonResponse($response = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'jsonResponse');
        if (!$pluginInfo) {
            return parent::jsonResponse($response);
        } else {
            return $this->___callPlugins('jsonResponse', func_get_args(), $pluginInfo);
        }
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

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
