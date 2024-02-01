<?php

class XcashPaymentSuccessModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $cart = $this->context->cart;
        $secure_key = Tools::getValue('secure_key');

        xcash_redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$secure_key);

        // $orderHistoryUrl = $this->context->link->getPageLink('history');
        // Tools::redirect($orderHistoryUrl);
    }
}
