# Simulação de vendas da API PHP da Cielo

Esta é uma simulação de vendas por cartão de crédito ou débito, consumindo a API PHP da Cielo (https://github.com/DeveloperCielo/API-3.0-PHP).

Para executar essa aplicação, é necessário trocar as variáveis "MERCHANT ID" e "MERCHANT KEY", nos arquivos "new_payment.php" e "payment_verify.php", pela suas respectivas credenciais.

Importante lembrar também que esta simulação não persiste os dados em um banco. Sendo assim, na simulação de venda por cartão de débito, há comentários explicando como é feita a persistência necessária para a execução da aplicação (dica: procurar no código a expressão "OBSERVAÇÃO IMPORTANTE" para encontrar a explicação).
