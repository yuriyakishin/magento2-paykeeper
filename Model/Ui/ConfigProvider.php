<?php
namespace Yu\Paykeeper\Model\Ui;

use Magento\Framework\Session\SessionManagerInterface;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    const CODE = 'paykeeper';
    
    /**
     * @var Config
     */
    private $config;
    
    /**
     * @var SessionManagerInterface
     */
    private $session;
    
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    
    /**
     * 
     * @param \Magento\Payment\Gateway\Config\Config $config
     * @param SessionManagerInterface $session
     * @param \Magento\Framework\UrlInterface $urlBuilder
     */
    public function __construct(
        \Magento\Payment\Gateway\Config\Config $config,
        SessionManagerInterface $session,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->session = $session;
        $this->urlBuilder = $urlBuilder;
        $this->config->setMethodCode(self::CODE);
    }
    
    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $storeId = $this->session->getStoreId();
        return [
            'payment' => [
                self::CODE => [
                    'description' => $this->config->getValue('description'),
                    'cgi_url' => $this->urlBuilder->getUrl('paykeeper/pay')
                ],
            ],
        ];
    }
}
