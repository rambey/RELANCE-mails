<?php

require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/controller/EmailController.php';


class OrdersConfigActionsModuleFrontController extends ModuleFrontController
{
   
    public function init()
    {
        parent::init();
    }

    public function postProcess()
    {
        $emailController = new EmailController();
        if ($emailController->exportMail()) {
            die(json_encode(
                array(
                    'error' => true,
                    'message' => $this->trans('Message sent successfully', array(), 'Modules.ordersconfig.Shop'),
                )
            ));
            
        } else {
            die(json_encode(
                array(
                    'error' => true,
                    'message' => $this->trans('error while sending message', array(), 'Modules.ordersconfig.Shop'),
                )
            ));
        }
    }
}
