define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Yu_Paykeeper/js/view/payment/action/paykeeper-action'
    ],
    function (Component,setPaymentMethodAction) {
        'use strict';
        return Component.extend({
            defaults: {
                redirectAfterPlaceOrder: false,
                template: 'Yu_Paykeeper/payment/paykeeper'
            },
            
            getCode: function() {
                return 'paykeeper';
            },
            
            getDescription: function() {
                return window.checkoutConfig.payment[this.getCode()].description;
            },
            
            validate: function () {
                return true;
            },
            
            afterPlaceOrder: function () {
                setPaymentMethodAction(this);
                return false;
            }
        });
    }
);