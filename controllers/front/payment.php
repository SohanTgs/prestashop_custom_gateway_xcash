<?php 

class XcashPaymentModuleFrontController extends ModuleFrontController{

    public function initContent(){
        
        $cart = $this->context->cart;

        $currency = new Currency((int)$cart->id_currency);
        $customer = new Customer($cart->id_customer);
        $secureKey = $customer->secure_key;

        $parameters = [
            'identifier' => $cart->id,
            'currency' => $currency->iso_code,
            'amount' => $cart->getOrderTotal(true, Cart::BOTH),
            'details' => 'Order #' . $cart->id,
            
            'ipn_url' => $this->context->link->getModuleLink($this->module->name, 'ipn', [
                'cart_id' => $cart->id
            ]),
            'cancel_url' => $this->context->link->getModuleLink($this->module->name, 'paymentCancel', [
                'cart_id' => $cart->id,
                'secure_key' => $secureKey
            ]),

            'success_url' => $this->context->link->getModuleLink($this->module->name, 'paymentSuccess', [
                'cart_id' => $cart->id,
                'secure_key' => $secureKey
            ]),

            'public_key' => Configuration::get('XCASH_PUBLIC_KEY'),
            'site_logo' => '',
            'checkout_theme' => Configuration::get('XCASH_THEME'),
            'customer_name' => $customer->firstname . ' ' . $customer->lastname,
            'customer_email' => $customer->email
        ];

        $url = XCASH_API_ENDPOINT."/payment/initiate";

        if(Configuration::get('XCASH_PAYMENT_MODE') == 'sandbox'){
            $url = XCASH_API_ENDPOINT."/sandbox/payment/initiate";
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $paymentUrl = $result;
        $result = json_decode($paymentUrl);

        if (@$result->error == 'true' || @$result->error == true || @$result->error == 'yes') { 

            $messages = [@$result->message];
            if (@$result->errors) {
                $messages = $result->errors;
            }

            $error = '<ul style="background: #ff1b1b29;color: #ff1b1b;padding-top: 6px;padding-bottom: 6px;font-weight: 500;">';
            foreach($messages as $message){
                $error .= "<li>$message</li>";
            }
            $error .= '<ul>';
            echo $error;
            die();
        }else{
            xcash_redirect($result->url);
        }

    }

}
