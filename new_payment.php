<?php
require 'vendor/autoload.php';

use Cielo\API30\Merchant;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;

use Cielo\API30\Ecommerce\Request\CieloRequestException;


if (isset($_POST['order_id']) &&
    //is_numeric($_POST['order_id']) &&
    !empty($_POST['order_id']) &&
    isset($_POST['client_name']) &&
    !empty($_POST['client_name']) &&
    isset($_POST['amount']) &&
    floatval($_POST['amount']) > 0 &&
    isset($_POST['card_flag']) &&
    !empty($_POST['card_flag']) &&
    isset($_POST['card_security_code']) &&
    //is_numeric($_POST['card_security_code']) &&
    !empty($_POST['card_security_code']) &&
    isset($_POST['card_number']) &&
    //is_numeric($_POST['card_number']) &&
    !empty($_POST['card_number']) &&
    isset($_POST['card_expiration']) &&
    !empty($_POST['card_expiration']) &&
    isset($_POST['card_holder']) &&
    !empty($_POST['card_holder'])
) {

    $order_id = strip_tags($_POST['order_id']);
    $client_name = strip_tags($_POST['client_name']);
    $amount = floatval(strip_tags($_POST['amount'])) * 100; //convertendo para centavos
    $card_flag = strip_tags($_POST['card_flag']) == "VISA" ? CreditCard::VISA : CreditCard::MASTERCARD;
    $card_security_code = strip_tags($_POST['card_security_code']);
    $card_number = strip_tags($_POST['card_number']);
    $card_expiration = strip_tags($_POST['card_expiration']);
    $card_holder = strip_tags($_POST['card_holder']);
    
    // Configure o ambiente
    $environment = $environment = Environment::sandbox();

    // Configure seu merchant
    $merchant = new Merchant('0b974f0b-8235-480e-b67f-91747b692930', 'QOVGYOJXVJIVQQQZEKNLSPVGLBAABGJVJRRXVTJE');

    // Crie uma instância de Sale informando o ID do pedido na loja
    $sale = new Sale($order_id);

    // Crie uma instância de Customer informando o nome do cliente
    $customer = $sale->customer($client_name);

    // Crie uma instância de Payment informando o valor do pagamento
    $payment = $sale->payment($amount);

    // Crie uma instância de Credit Card utilizando os dados de teste
    // esses dados estão disponíveis no manual de integração
    $payment->setCapture(1)
            ->setType(Payment::PAYMENTTYPE_CREDITCARD)
            ->creditCard($card_security_code, $card_flag)
            ->setExpirationDate($card_expiration)
            ->setCardNumber($card_number)
            ->setHolder($card_holder);

    // Crie o pagamento na Cielo
    try {
        // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
        $sale = (new CieloEcommerce($merchant, $environment))->createSale($sale);

        // Com a venda criada na Cielo, já temos o ID do pagamento, TID e demais
        // dados retornados pela Cielo
        $paymentId = $sale->getPayment()->getPaymentId();

        // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
        //$sale_capture = (new CieloEcommerce($merchant, $environment))->captureSale($paymentId, $amount, 0);

        if (!is_null($sale->getPayment()->getTid())) {
            Header("Location: return.php?tid=" . $sale->getPayment()->getTid());
        } else {
            Header("Location: return.php?status=" . $sale->getPayment()->getStatus()  ."&error=" . $sale->getPayment()->getReturnCode());
        }

        //echo $paymentId;

        // E também podemos fazer seu cancelamento, se for o caso
        //$sale = (new CieloEcommerce($merchant, $environment))->cancelSale($paymentId, $amount);
    } catch (CieloRequestException $e) {
        // Em caso de erros de integração, podemos tratar o erro aqui.
        // os códigos de erro estão todos disponíveis no manual de integração.
        Header("Location: return.php?error=" . $e->getCieloError()->getCode());
    }
    
} else {
    Header("Location: return.php?error=Campos obrigatórios não foram preenchidos corretamente!");
}
