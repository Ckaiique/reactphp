<?php 
require_once 'vendor/autoload.php';
// A tem um exemplo bem legal para usar  o php assincrono, usando o REACTPHP

use React\EventLoop\Loop;
$startTimes = microtime(true);

$array_parallel = [];

$array_parallel[]  = fn () => new React\Promise\Promise(function ($resolve) { // você cria uma promise
    Loop::addTimer(1, function () use ($resolve) {  // esse  Loop usei apenas  para simular  o tempo de execução, NÂO PRECISA  USAR ELE COM A PROMISE
        $resolve('1');
    });
});

$array_parallel[]  = fn () => new React\Promise\Promise(function ($resolve) {
    Loop::addTimer(5, function () use ($resolve) {
        $resolve('2');
    });
});

$array = ['a','s','d','f','g','h','j','k','l'];

foreach ($array as $key => $value) {
    $array_parallel[]  = fn () => new React\Promise\Promise(function ($resolve) use($key,$value) {
        Loop::addTimer($key, function () use ($resolve,$value) {    // aqui  o tempo de execução vai dar 8 segundos
            $resolve($value);
        });
    });
  }



for ($i=0; $i <10 ; $i++) { 
    $array_parallel[]  = fn () => new React\Promise\Promise(function ($resolve) use($i) {
        Loop::addTimer($i, function () use ($resolve) {    // repare que o tempo de execução desse usei 
                                                           // o proprio $i então o maior tempo desse parallel vai ser 9 segundos e não 5 segundos
            $resolve('3');
        });
    });
}


//Parallel vai chamar todas as funções que estão no $array_parallel ao mesmo tempo  
React\Async\parallel( 
    $array_parallel  
)->then(function (array $results) use ($startTimes) {
    
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
Estava com dúvidas sobre como criar Promises dentro de um loop, como um foreach ou for. Aqui está um exemplo de como fazer:
kaique
string(1) "3"
string(1) "1"
string(1) "3"
string(1) "3"
string(1) "3"
string(1) "3"
string(1) "2"
string(1) "3"
string(1) "3"
string(1) "3"
string(1) "3"
string(1) "3"
Total de Tempo: 9 segundos

Por que levou 9 segundos?
Bem, como expliquei anteriormente, todas as funções foram chamadas simultaneamente, sem acumular seus tempos de execução.
Não é necessário aguardar o término de uma função para iniciar outra. Elas estão sendo chamadas e executadas simultaneamente. 
No entanto, o tempo de execução mais longo determina o tempo total do processo.
*/
