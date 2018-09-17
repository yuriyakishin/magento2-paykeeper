<?php
namespace Yu\Paykeeper\Model;

class PaykeeperNotify implements \Yu\Paykeeper\Api\PaykeeperNotifyInterface
{
    /**
     * @var Config
     */
    private $config;
    
    /**
     *
     * @var OrderRepository 
     */
    private $_orderRepository;
    
    /**
     * @param \Magento\Payment\Gateway\Config\Config $config
     */
    public function __construct(
        \Magento\Payment\Gateway\Config\Config $_config,
        \Magento\Sales\Model\OrderRepository $_orderRepository
    ) {
        $this->config = $_config;
        $this->config->setMethodCode(\Yu\Paykeeper\Model\Ui\ConfigProvider::CODE);
        $this->_orderRepository = $_orderRepository;
    }
    
    /**
     * @return string
     */
    public function notify() 
    {
        $secret_seed = $this->config->getValue('merchant_gateway_key');
        if(isset($_POST['orderid']) && isset($_POST['id']) && isset($_POST['sum']) && isset($_POST['clientid']) && isset($_POST['key']))
        {
            $orderid = $_POST['orderid'];
            $id = $_POST['id'];
            $sum = $_POST['sum'];
            $clientid = $_POST['clientid'];        
            $key = $_POST['key'];   
        } else {
            die('Request doesn\'t contain POST elements.');
        }

        if ($key != md5 ($id . sprintf ("%.2lf", $sum).
                                       $clientid.$orderid.$secret_seed))
        {
            echo "Error! Hash mismatch";
            exit;
        }

        if ($orderid == "")
        {
            # Платёж – пополнение счёта, нужно зачислить деньги на баланс $clientid
        }
        else
        {
            $order = $this->_orderRepository->get($orderid);
            $order->setStatus($order::STATE_PROCESSING);
            $this->_orderRepository->save($order);
        }
        echo "OK ".md5($id.$secret_seed);
    }
}