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

if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    // Configure o ambiente
    $environment = $environment = Environment::sandbox();

    // Configure seu merchant
    $merchant = new Merchant($merchant_id, $merchant_key);

    $order_id = $_GET['order_id'];

    /******* OBSERVAÇÃO IMPORTANTE ********


        Na utilização de banco de dados, é necessário obter o ID do pagamento através do ID do pedido (order_id).
        Nesta simulação, por não utilizar banco de dados, obtemos o ID do pagamento através de um arquivo .txt, 
        gravado quando a venda por cartão de débito é criada.

     
     ***************************/

    $payment_id = fgets(fopen ('order_id.txt', 'r'), 1024);
    $sale = (new CieloEcommerce($merchant, $environment))->getSale($payment_id);

    if (!is_null($sale->getPayment()->getTid()) && $sale->getPayment()->getStatus() == 2) {
        Header("Location: return.php?tid=" . $sale->getPayment()->getTid());
    } else {
        Header("Location: return.php?status=" . $sale->getPayment()->getStatus()  ."&error=" . $sale->getPayment()->getReturnMessage());
    }
} else {
    Header("Location: return.php?error=ID do pedido inválido!");
}


?>