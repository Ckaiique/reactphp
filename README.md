## Usando ReactPHP com PHP

Para utilizar o ReactPHP em seu projeto PHP, você tem duas opções:

1. **Usando o Framework ReactPHP**:

   Você pode obter mais informações e documentação detalhada no [site oficial do ReactPHP](https://reactphp.org/).

2. **Usando o Composer**:

   Você pode adicionar as dependências necessárias ao seu projeto PHP usando o Composer. Certifique-se de que está usando o PHP 8 ou superior para as versões mais recentes do ReactPHP. Se estiver usando o PHP 7, siga as instruções abaixo.

   - Para PHP 8+:
     ```bash
     composer require react/async:^4.1.0
     ```

   - Para PHP 5+, PHP 7+:
     ```bash
     composer require react/async:^3
     ```

     Para ambas as versões:
     ```bash
     composer require react/event-loop:^1.4.0
     composer require react/promise:^3.0.0
     ```
