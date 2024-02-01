<?php

class XcashPaymentCancelModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        xcash_redirect('index.php?controller=order&step=1');

        // $checkoutUrl = $this->context->link->getPageLink('order');
        // Tools::redirect($checkoutUrl);
    }
}
