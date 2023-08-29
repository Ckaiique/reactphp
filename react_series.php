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


//series  
React\Async\series([   // aqui você cria um array chamando as funções anônimas 
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

/* O resultado será semelhante a este:
kaique
bool(false)
string(1) "1"  - Este retornou primeiro pois seu tempo de execução foi de 1 segundo.
string(1) "3"  - Este retornou em segundo lugar pois seu tempo de execução foi de 2 segundos.
string(1) "2"  - Este retornou por último pois seu tempo de execução foi de 5 segundos.
Total de Tempo: 8 

Por que levou 8 segundos?
Ao contrário do método "parallel", quando se utiliza o método "series", uma função inicia somente quando a anterior termina.
Portanto, o tempo de execução dessas funções é acumulativo: 1 + 2 + 5 = 8.
É necessário aguardar que uma função esteja pronta para que a próxima possa iniciar.
As funções estão sendo chamadas em sequência, uma após a outra.*/
