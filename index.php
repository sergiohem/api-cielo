
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>API 3.0 da Cielo em PHP</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/form-validation.css" rel="stylesheet">
  </head>

  <body class="bg-light">

    <div class="container">
      <div class="py-5 text-center">
        <h2>Simulação de pagamento - API PHP da Cielo</h2>
      </div>
      <form id="form_new_payment" class="needs-validation" novalidate action="new_payment.php" method="POST">
        <div class="row">
          <div class="col-md-12 order-md-6">
            <h4 class="mb-3">Dados do pagamento</h4>         
            <div class="row">
              <div class="col-md-3 mb-1">
                <label for="order_id">ID do pedido</label>
                <input type="text" class="form-control" name="order_id" id="order_id" placeholder="" value="" required>
                <div class="invalid-feedback">
                  ID do pedido é obrigatório.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="client_name">Nome do cliente</label>
                <input type="text" class="form-control" name="client_name" id="client_name" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Nome do cliente é obrigatório.
                </div>
              </div>
              <div class="col-md-3 mb-1">
                <label for="amount">Valor em R$</label>
                <input type="text" class="form-control" name="amount" id="amount" onkeypress="return mascaraMoeda(this,'.',',',event);" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Valor é obrigatório.
                </div>
              </div>            
            </div>
          </div>
        </div>

        <hr class="mb-4">

        <div class="row">
          <div class="col-md-12 order-md-6">
            <h4 class="mb-3">Dados do cartão</h4>
            <div class="row">
              <div class="col-md-4 mb-2">
                <label for="card_number">Número</label>
                <input type="text" class="form-control" name="card_number" id="card_number" maxlength="19" onkeypress="return somenteNumeros(event);" placeholder="" required>
                <div class="invalid-feedback">
                  Número do cartão é obrigatório.
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="card_number">Nome impresso</label>
                <input type="text" class="form-control" name="card_holder" id="card_holder" placeholder="" required>
                <div class="invalid-feedback">
                  Nome impresso é obrigatório.
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="card_type">Tipo</label>
                <div class="radio">
                  <label class="radio-inline"><input type="radio" name="card_type" value="credit" checked="true"> Crédito</label>
                  <label class="radio-inline col-md-4 mb-2"><input type="radio" name="card_type" value="debit"> Débito</label>
                </div>
                <div class="invalid-feedback">
                  Tipo do cartão é obrigatório.
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-2">
                <label for="card_flag">Bandeira</label>
                <select name="card_flag" id="card_flag" class="form-control" required>
                  <option value="">Selecione</option>
                  <option value="VISA">VISA</option>
                  <option value="MASTERCARD">MASTER CARD</option>
                </select>
                <div class="invalid-feedback">
                  Bandeira do cartão é obrigatório.
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="card_expiration">Vencimento</label>
                <input type="text" class="form-control" name="card_expiration" id="card_expiration" maxlength="7" onkeypress="return dateCardExpirationMask(this, event);" placeholder="Ex.: 01/2022" required>
                <div class="invalid-feedback">
                  Vencimento do cartão é obrigatório.
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="card_security_code">Código de segurança</label>
                <input type="text" class="form-control" name="card_security_code" id="card_security_code" maxlength="3" onkeypress="return somenteNumeros(event);" placeholder="" required>
                <div class="invalid-feedback">
                  Código de segurança é obrigatório.
                </div>
              </div>
            </div>
          </div>
        </div>
            
        <hr class="mb-4">
        <button class="btn btn-primary btn-lg btn-block" type="submit">Finalizar pagamento</button>
      </form>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-slim.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.min.js"></script>
    <script src="js/new_payment.min.js"></script>
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>
