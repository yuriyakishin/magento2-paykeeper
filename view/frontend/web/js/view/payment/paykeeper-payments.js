define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'paykeeper',
                component: 'Yu_Paykeeper/js/view/payment/method-renderer/paykeeper'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
