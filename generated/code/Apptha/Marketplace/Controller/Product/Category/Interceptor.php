<?php
namespace Apptha\Marketplace\Controller\Product\Category;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Product\Category
 */
class Interceptor extends \Apptha\Marketplace\Controller\Product\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Apptha\Marketplace\Helper\Data $dataHelper)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $categoryRepository, $dataHelper);
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
