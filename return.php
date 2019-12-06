<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Retorno de pagamento</title>

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

      <div class="row">
        
        <div class="col-md-12">
			<?php
			if (isset($_GET['tid']) && !empty($_GET['tid'])) { ?>
                <div class="alert alert-success" role="alert">
                    Pagamento realizado com sucesso! <?= "TID: " . $_GET['tid']; ?>
                </div>        			
            <?php } else if (isset($_GET['status']) && !empty($_GET['status']) && isset($_GET['error']) && !empty($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    Erro ao realizar pagamento! <?= "Status: " . $_GET['status'] . " | Erro: " . $_GET['error']; ?>
                </div>
            <?php } else if (isset($_GET['error']) && !empty($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    Erro de integração! <?= "Erro: " . $_GET['error']; ?>
                </div>
            <?php } ?>
        </div>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-slim.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.min.js"></script>
    
  </body>
</html>
