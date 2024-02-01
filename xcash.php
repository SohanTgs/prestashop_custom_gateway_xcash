<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

define('XCASH_API_ENDPOINT', 'https://script.viserlab.com/xcash');

class Xcash extends PaymentModule{

    private $_html = '';
    private $_postErrors = array();

    public function __construct() 
    {    
        $this->name = 'xcash';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'ViserLab';
        $this->controllers = array('payment');

        parent::__construct();

        $this->displayName = $this->l('Xcash Payment');
        $this->description = $this->l('xCash payment gateway for PrestaShop');
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('paymentOptions')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }

        $paymentOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $paymentOption->setCallToActionText($this->l('Payment with Xcash'))
            ->setAction($this->context->link->getModuleLink($this->name, 'payment', array(), true));
            // ->setAdditionalInformation($this->fetch('module:xcashpayment/views/templates/hook/payment_option.tpl'));
        
        return array($paymentOption);
    }
    
    private function _displayXcash()
    {   
        return $this->display(__FILE__, 'views/infos.tpl');
    }

    private function _postValidation()
    {
        if (Tools::isSubmit('submitXcashKeys')) {
            if (!Tools::getValue('xcash_public_key')) {
                $this->_postErrors[] = $this->l('The "Public Key" field is required.');
            } elseif (!Tools::getValue('xcash_secret_key')) {
                $this->_postErrors[] = $this->l('The "Secret Key" field is required.');
            } elseif (!Tools::getValue('xcash_payment_mode')) {
                $this->_postErrors[] = $this->l('The "Payment Mode" field is required.');
            }elseif (!Tools::getValue('xcash_theme')) {
                $this->_postErrors[] = $this->l('The "Theme" field is required.');
            }
        }
    }
    
    private function _postProcess()
    {
        if (Tools::isSubmit('submitXcashKeys')) {
            Configuration::updateValue('XCASH_SECRET_KEY', Tools::getValue('xcash_secret_key'));
            Configuration::updateValue('XCASH_PUBLIC_KEY', Tools::getValue('xcash_public_key'));
            Configuration::updateValue('XCASH_PAYMENT_MODE', Tools::getValue('xcash_payment_mode'));
            Configuration::updateValue('XCASH_THEME', Tools::getValue('xcash_theme'));
        }
        $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
    }
    
    public function getContent()
    {
        $this->_html = '';

        if (Tools::isSubmit('submitXcashKeys')) {
            $this->_postValidation();
            if (!count($this->_postErrors)) {
                $this->_postProcess();
            } else {
                foreach ($this->_postErrors as $err) {
                    $this->_html .= $this->displayError($err);
                }
            }
        }
    
        $this->_html .= $this->_displayXcash();
        $this->_html .= $this->renderForm();
    
        return $this->_html;
    }
    
    public function renderForm()
    {
        $fieldsForm = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('XCash Payment Configuration'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Public Key'),
                        'name' => 'xcash_public_key',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Secret Key'),
                        'name' => 'xcash_secret_key',
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Payment Mode'),
                        'name' => 'xcash_payment_mode',
                        'options' => array(
                            'query' => array(
                                array('id' => '0', 'name' => $this->l('Select One')),
                                array('id' => 'sandbox', 'name' => $this->l('Sandbox')),
                                array('id' => 'live', 'name' => $this->l('Live')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Theme'),
                        'name' => 'xcash_theme',
                        'options' => array(
                            'query' => array(
                                array('id' => 'dark', 'name' => $this->l('Dark')),
                                array('id' => 'light', 'name' => $this->l('Light')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitXcashKeys',
                ),
            ),
        );
    
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->title = $this->displayName;
    
        // Load current values
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        $fieldsForm['form']['input'][0]['value'] = Configuration::get('XCASH_SECRET_KEY');
        $fieldsForm['form']['input'][1]['value'] = Configuration::get('XCASH_PUBLIC_KEY');
        $fieldsForm['form']['input'][2]['value'] = Configuration::get('XCASH_PAYMENT_MODE');
        $fieldsForm['form']['input'][3]['value'] = Configuration::get('XCASH_THEME');
    
        // Assign field values
        $helper->fields_value = array(
            'xcash_secret_key' => Configuration::get('XCASH_SECRET_KEY'),
            'xcash_public_key' => Configuration::get('XCASH_PUBLIC_KEY'),
            'xcash_payment_mode' => Configuration::get('XCASH_PAYMENT_MODE'),
            'xcash_theme' => Configuration::get('XCASH_THEME'),
        );
    
        // Generate the form
        $form = $helper->generateForm(array($fieldsForm));
        
        return $form;
    }
    
    

}
