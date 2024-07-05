<?php

use MercadoPago\SDK;
use MercadoPago\Payment;
use MercadoPago\Payer;

class MercadoPagoHandler
{

    /** CUENTAS DE PRUEBA
     *  vendedor:
     *         -user: TESTUSER216102577
     *         -pass: tqWu7lXf2S
     * comprador:
     *         -user: TESTUSER1991475345
     *         -pass: Is7D13EIB0
     */
    private $payment;

    public function __construct()
    {
        $this->payment = new Payment();
    }

    public function comprar()
    {
        SDK::setAccessToken('YOUR_ACCESS_TOKEN');

        $payment = new Payment();
        $payment->transaction_amount = 100;
        $payment->token = "YOUR_CARD_TOKEN";
        $payment->description = "Descripción del producto";
        $payment->installments = 1;
        $payment->payment_method_id = "visa";
        $payment->payer = new Payer();
        $payment->payer->email = "test_user@test.com";

        try {
            $payment->save();
            echo "Pago creado con ID: " . $payment->id;
        } catch (Exception $e) {
            echo "Error al crear el pago: " . $e->getMessage();
        }
    }
}