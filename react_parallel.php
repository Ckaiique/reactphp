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

/* O resultado será semelhante a este:
kaique
bool(false)
string(1) "1"  - Este retornou primeiro porque seu tempo de execução foi de 1 segundo.
string(1) "3"  - Este retornou em segundo lugar devido a um tempo de execução de 2 segundos.
string(1) "2"  - Este retornou por último, já que seu tempo de execução foi de 5 segundos.
Total de Tempo: 5 segundos

Por que levou 5 segundos?
Bem, conforme mencionado na linha 27, todas as funções foram chamadas simultaneamente. Os tempos de execução não são acumulativos.
Não é necessário aguardar o término de uma função para iniciar outra. Todas estão sendo chamadas e executadas simultaneamente. No entanto,
o tempo de execução mais longo é determinado pelo funcionamento mais demorado entre elas.
*/ 
