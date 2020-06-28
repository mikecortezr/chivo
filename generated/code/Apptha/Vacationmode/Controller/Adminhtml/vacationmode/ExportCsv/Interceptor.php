<?php
namespace Apptha\Vacationmode\Controller\Adminhtml\vacationmode\ExportCsv;

/**
 * Interceptor class for @see \Apptha\Vacationmode\Controller\Adminhtml\vacationmode\ExportCsv
 */
class Interceptor extends \Apptha\Vacationmode\Controller\Adminhtml\vacationmode\ExportCsv implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($context);
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
