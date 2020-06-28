<?php
namespace Apptha\Marketplace\Controller\Product\Savedata;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Product\Savedata
 */
class Interceptor extends \Apptha\Marketplace\Controller\Product\Savedata implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Catalog\Model\ProductFactory $productFactory, \Apptha\Marketplace\Helper\System $systemHelper, \Apptha\Marketplace\Helper\Marketplace $dataHelper, \Magento\Framework\Filesystem\Driver\File $file)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $productRepository, $productFactory, $systemHelper, $dataHelper, $file);
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
