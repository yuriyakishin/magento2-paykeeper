<?php
namespace Yu\Paykeeper\Controller\Pay;

/**
 *
 * @author Yu
 */
class Success extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var \Magento\Framework\Translate\InlineInterface
     */
    protected $_translateInline;
    
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory 
     */
    protected $_resultRawFactory;
    
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    
    /**
     * 
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param array $data
     */
    public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Magento\Framework\Translate\InlineInterface $translateInline,
            \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
            \Magento\Checkout\Model\Session $checkoutSession,
            array $data = []
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_translateInline = $translateInline;
        $this->_resultRawFactory = $resultRawFactory;
        $this->_checkoutSession = $checkoutSession;
    }
    
    /**
     * 
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {        
        $order = $this->_checkoutSession->getLastRealOrder();
        
        $page = $this->resultPageFactory->create();
        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $html = $page->getLayout()
                ->getBlock('paykeeper.success')
                ->toHtml();
        
        $this->_translateInline->processResponseBody($html);
        $resultRaw = $this->_resultRawFactory->create();
        $resultRaw->setContents($html);
        return $resultRaw;
    }
}