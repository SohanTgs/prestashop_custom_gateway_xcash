<?php

class XcashPaymentIpnModuleFrontController extends ModuleFrontController{

    public function postProcess(){

        $cartId = Tools::getValue('cart_id');
        $context = Context::getContext();

        $status = $_POST['status'];
        $signature = $_POST['signature'];
        $identifier = $_POST['identifier'];
        $data = $_POST['data'];

        $amount = $data['amount'];
        $getCurrency = $data['currency']['code'];

        $customKey = $data['amount'].$identifier;
        $secret = Configuration::get('XCASH_SECRET_KEY');
        $mySignature = strtoupper(hash_hmac('sha256', $customKey , $secret));

        if($status == "success" && $signature == $mySignature){

            $cart = new Cart((int)$cartId);
            $context->cart = $cart;

            if (!Validate::isLoadedObject($cart)) {
                $errors[] = $this->module->l('Invalid Cart ID');
            }
            else{
                $currency = new Currency((int)Currency::getIdByIsoCode($getCurrency));

                if (!Validate::isLoadedObject($currency) || $currency->id != $cart->id_currency) {
                    $errors[] = $this->module->l('Invalid Currency ID') . ' ' . ($currency->id . '|' . $cart->id_currency);
                }
                else{
                    $context->currency = $currency;

                    if($amount < $cart->getOrderTotal(true)){
                        $errors[] = $this->module->l('Invalid Amount paid');
                        $order_status = (int)Configuration::get('PS_OS_ERROR');
                    }
                    else{
                        $order_status = (int)Configuration::get('PS_OS_PAYMENT');
                    }

                    if($cart->OrderExists()){
                        $order = new Order((int)Order::getOrderByCartId($cart->id));
                        $new_history = new OrderHistory();
                        $new_history->id_order = (int)$order->id;
                        $new_history->changeIdOrderState((int)$order_status, $order, true);
                        $new_history->addWithemail(true);
                    }else{
                        
                        $customer = new Customer((int)$cart->id_customer);
                        $context->customer = $customer;

                        $message =
                            'Transaction ID: ' . $data['payment_trx'] . '
                            Payment Via: ' . $this->module->displayName . '
                            Final amount charged: ' . $amount . '
                            Currency code: ' . $getCurrency . '
                            verify_sign: ' . $signature;

                        if ($this->module->validateOrder((int)$cart->id, (int)$order_status, (float)$amount, $this->module->displayName, $message, array(), null, false, $customer->secure_key, $shop)) {
                            /* Store transaction ID and details */
                            $this->module->addTransactionId((int)$this->module->currentOrder, $data['payment_trx']);
                        }
                    }
                }

            }
        } 

    }

}
