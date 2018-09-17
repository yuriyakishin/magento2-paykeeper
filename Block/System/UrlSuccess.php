<?php
/**
 * Paykeeper block
 *
 * @author Yuriy Akishin
 * @email yuriyakishin@gmail.com 
 */
namespace Yu\Paykeeper\Block\System;

class UrlSuccess extends \Magento\Config\Block\System\Config\Form\Field 
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    
    public function __construct(
        \Magento\Framework\Url $urlBuilder,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlBuilder = $urlBuilder;
    }
    
    /**
     * Return url
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->urlBuilder->getBaseUrl().'index.php/paykeeper/pay/success/';
    }
}

