<?php
namespace Yu\Paykeeper\Block\Checkout\Onepage\Success;


class PaymentForm extends \Magento\Framework\View\Element\Template
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
    }

    protected function _toHtml()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        if($order->getEntityId())
        {
            $customerId = $this->_customerSession->getCustomerId();
            $method = $order->getPayment()->getMethod();

            if($method == \Yu\Paykeeper\Model\Ui\ConfigProvider::CODE)
            {
                $url = $this->config->getValue('cgi_url');
                $instructions = $this->config->getValue('instructions');
                $payment_parameters = http_build_query(array(
                        "clientid"=>$order->getCustomerId(),
                        "orderid"=>$order->getEntityId(),
                        "sum"=>$order->getSubtotal(),
                        "phone"=>''));
                $options = array("http"=>array(
                        "method"=>"POST",
                        "header"=>
                        "Content-type: application/x-www-form-urlencoded",
                        "content"=>$payment_parameters
                           ));
                $context = stream_context_create($options);
                return "<p>".$instructions."</p>".file_get_contents($url,FALSE, $context);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }
    }
}