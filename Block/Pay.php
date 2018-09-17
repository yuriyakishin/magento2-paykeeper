<?php
namespace Yu\Paykeeper\Block;

class Pay extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    
    /**
     * @var Config
     */
    private $config;
    
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Payment\Gateway\Config\Config $config
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Payment\Gateway\Config\Config $config,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->config = $config;
        $this->config->setMethodCode(\Yu\Paykeeper\Model\Ui\ConfigProvider::CODE);
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }
    
    /**
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->config->getValue('title');
    }
    
    /**
     * 
     * @return string
     */
    public function getInstructions()
    {
        return $this->config->getValue('instructions');
    }
    
    /**
     * 
     * @return string
     */
    public function getForm()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        $url = $this->config->getValue('cgi_url');
        $title = $this->config->getValue('title');
        $instructions = $this->config->getValue('instructions');
            
        if($order->getEntityId())
        {          

            $payment_parameters = http_build_query(array(
                    "clientid" => $order->getCustomerId(),
                    "orderid" => $order->getEntityId(),
                    "sum" => $order->getSubtotal(),
                    "phone" => ''));
            $options = array("http"=>array(
                    "method" => "POST",
                    "header"=> "Content-type: application/x-www-form-urlencoded",
                    "content"=>$payment_parameters
                ));
            $context = stream_context_create($options);

            $content = file_get_contents($url,FALSE, $context);
            
            return $content;
        }
        
        return 'Empty order';
    }
}