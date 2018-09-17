<?php
namespace Yu\Paykeeper\Model\Payment;

class Paykeeper extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'paykeeper';
}