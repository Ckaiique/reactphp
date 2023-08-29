<?php 
require_once 'vendor/autoload.php';
// A tem um exemplo bem legal para usar  o php assincrono, usando o REACTPHP

use React\EventLoop\Loop;
$startTimes = microtime(true);

$teste1 = fn () => new React\Promise\Promise(function ($resolve) { // você cria uma promise
    Loop::addTimer(1, function () use ($resolve) {  // esse  Loop usei apenas  para simular  o tempo de execução, NÂO PRECISA  USAR ELE COM A PROMISE
        $resolve('1');
    });
});

$teste2 = fn () => new React\Promise\Promise(function ($resolve) {
    Loop::addTimer(5, function () use ($resolve) {
        $resolve('2');
    });
});

$teste3 = fn () => new React\Promise\Promise(function ($resolve) {
    Loop::addTimer(2, function () use ($resolve) {
        $resolve('3');
    });
});


//Parallel vai chamar todas as funções ao mesmo tempo  
React\Async\parallel([   // aqui você cria um array chamando as funções anônimas 
    fn () => $teste1(),
    fn () => $teste2(),
    fn () => $teste3(),
    fn () => isset($teste4) ? $teste4() : \React\Promise\resolve(false),
])->then(function (array $results) use ($startTimes) {
    
    foreach ($results as $result) {
        // só  vai cair  aqui qual tiver a resposta , ai depende do tempo de cada  uma 
        var_dump($result);
    }
    $endTime = microtime(true);

    // só vai cair  aqui depois que todas estivem prontas
    echo  "Total Levo " . (int)($endTime - $startTimes) ." segundos" . PHP_EOL;
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

echo ('kaique') . PHP_EOL;  

/*o seu resultado vai ser  igual a esse aqui  
kaique
bool(false)
string(1) "1"  esse retornou primeiro por  o tempo de execução dele demoro 1 segundo
string(1) "3"  esse retornou primeiro por  o tempo de execução dele demoro 2 segundo
string(1) "2"  esse retornou primeiro por  o tempo de execução dele demoro 5 segundo
Total Levo 5 

Por que levou 5 segundos ?

 bom como eu escrevi na linha 27 todas as funções foram chamadas  juntas então,
 então o tempo de execução delas  não  somam.
 Não precisa esperar que uma função esteja pronta para poder iniciar a outra função,
 elas estão sendo chamadas aos mesmo tempo e sendo executadas ao mesmo tepo , so que 
 o tempo da execução mais demorado , vai ser da função que demorou mais. 
*/    