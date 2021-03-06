<?php
namespace Tun2U\AutoRelated\Block\Product\ProductList\Related;

/**
 * Interceptor class for @see \Tun2U\AutoRelated\Block\Product\ProductList\Related
 */
class Interceptor extends \Tun2U\AutoRelated\Block\Product\ProductList\Related implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\Module\Manager $moduleManager, array $data, \Magento\Framework\ObjectManagerInterface $helperFactory, \Tun2U\AutoRelated\Model\Collection $collection)
    {
        $this->___init();
        parent::__construct($context, $checkoutCart, $catalogProductVisibility, $checkoutSession, $moduleManager, $data, $helperFactory, $collection);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItems');
        if (!$pluginInfo) {
            return parent::getItems();
        } else {
            return $this->___callPlugins('getItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
