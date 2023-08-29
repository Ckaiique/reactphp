<?php 
require_once 'vendor/autoload.php';
 use function React\Promise\Stream\buffer;

//use React\EventLoop\Loop;
//use React\Stream\ReadableResourceStream;

// $loop = Loop::get();

// $stream  = new ReadableResourceStream( stream_socket_client('tcp://localhost:8001') , $loop );
// buffer($stream)->then(function (string $contents) {
//     var_dump(json_decode($contents));
// });

// $loop->run();


use React\EventLoop\Loop;

$startTimes = microtime(true);

$teste1 = fn () => new React\Promise\Promise(function ($resolve) {
    Loop::addTimer(1, function () use ($resolve) {
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

React\Async\parallel([
    fn () => $teste1(),
    fn () => $teste2(),
    fn () => $teste3(),
    fn () => isset($teste4) ? $teste4() : \React\Promise\resolve(false),
])->then(function (array $results) use ($startTimes) {
    foreach ($results as $result) {
        var_dump($result);
    }
    $endTime = microtime(true);
    echo  "Total Levo " . (int)($endTime - $startTimes) . PHP_EOL;
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

echo ('kaique') . PHP_EOL;

