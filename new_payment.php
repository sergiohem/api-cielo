<?php
require 'vendor/autoload.php';

use Cielo\API30\Merchant;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;

use Cielo\API30\Ecommerce\Request\CieloRequestException;

$merchant_id = 'MERCHANT ID';
$merchant_key = 'MERCHANT KEY';


if (isset($_POST['order_id']) &&
    !empty($_POST['order_id']) &&
    isset($_POST['client_name']) &&
    !empty($_POST['client_name']) &&
    isset($_POST['amount']) &&
    floatval($_POST['amount']) > 0 &&
    isset($_POST['card_flag']) &&
    !empty($_POST['card_flag']) &&
    isset($_POST['card_security_code']) &&
    !empty($_POST['card_security_code']) &&
    isset($_POST['card_number']) &&
    !empty($_POST['card_number']) &&
    isset($_POST['card_expiration']) &&
    !empty($_POST['card_expiration']) &&
    isset($_POST['card_holder']) &&
    !empty($_POST['card_holder']) &&
    isset($_POST['card_type']) &&
    !empty($_POST['card_type'])
) {

    $order_id = strip_tags($_POST['order_id']);
    $client_name = strip_tags($_POST['client_name']);
    $amount = floatval(strip_tags($_POST['amount'])) * 100; //convertendo para centavos
    $card_flag = strip_tags($_POST['card_flag']) == "VISA" ? CreditCard::VISA : CreditCard::MASTERCARD;
    $card_security_code = strip_tags($_POST['card_security_code']);
    $card_number = str_replace(' ', '', strip_tags($_POST['card_number']));
    $card_expiration = strip_tags($_POST['card_expiration']);
    $card_holder = strip_tags($_POST['card_holder']);
    $card_type = strip_tags($_POST['card_type']);

    // Configure o ambiente
    $environment = $environment = Environment::sandbox();

    // Configure seu merchant
    $merchant = new Merchant($merchant_id, $merchant_key);

    // Crie uma instância de Sale informando o ID do pedido na loja
    $sale = new Sale($order_id);

    // Crie uma instância de Customer informando o nome do cliente
    $customer = $sale->customer($client_name);

    // Crie uma instância de Payment informando o valor do pagamento
    $payment = $sale->payment($amount);

    $payment->setCapture(1);

    if ($card_type == "credit") {
        // Crie uma instância de Credit Card utilizando os dados de teste
        // esses dados estão disponíveis no manual de integração
        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
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

        if (!is_null($sale->getPayment()->getTid()) && $sale->getPayment()->getStatus() == 2) {
            Header("Location: return.php?tid=" . $sale->getPayment()->getTid());
        } else {
            Header("Location: return.php?status=" . $sale->getPayment()->getStatus()  ."&error=" . $sale->getPayment()->getReturnMessage());
        }

        //echo $paymentId;

        // E também podemos fazer seu cancelamento, se for o caso
        //$sale = (new CieloEcommerce($merchant, $environment))->cancelSale($paymentId, $amount);
        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            Header("Location: return.php?error=" . $e->getCieloError()->getCode());
        }
    } else if ($card_type == "debit") {

        $payment->setAuthenticate(1);
        
        // Defina a URL de retorno para que o cliente possa voltar para a loja
        // após a autenticação do cartão
        $payment->setReturnUrl('http://localhost/api-cielo/payment_verify.php?order_id=' . $order_id);

        // Crie uma instância de Debit Card utilizando os dados de teste
        // esses dados estão disponíveis no manual de integração
        $payment->setType(Payment::PAYMENTTYPE_DEBITCARD)
        ->debitCard($card_security_code, $card_flag)
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

            /******* OBSERVAÇÃO IMPORTANTE ********


                Nesta simulação, por não utilizar banco de dados, gravamos o ID do pagamento em um arquivo .txt, 
                para que o ID possa ser obtido na verificação do pagamento (payment_verify.php) pela URL de retorno.

            
            ***************************/

            $fp = fopen("order_id.txt", "w");
            $writter = fwrite($fp, $paymentId);
            fclose($fp);

            // Utilize a URL de autenticação para redirecionar o cliente ao ambiente
            // de autenticação do emissor do cartão
            $authenticationUrl = $sale->getPayment()->getAuthenticationUrl();

            Header("Location: " . $authenticationUrl);
        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            Header("Location: return.php?error=" . $e->getCieloError()->getCode());
        }
    } else {
        Header("Location: return.php?error=Campos obrigatórios não foram preenchidos corretamente!");
    }

    
    
} else {
    Header("Location: return.php?error=Campos obrigatórios não foram preenchidos corretamente!");
}
