<?php

use theorx\SDIC\SDIC;

require __DIR__ . '/vendor/autoload.php';

$c = new SDIC();

$c->register('test', function() {

    return "test 123 yo" . time();
})->register('test2', function() {

    return "aaa bbb";
})->register('sh', function(SDIC $container) {

    return $container->shared('sh', function() {

        return microtime();
    });
});

var_dump($c->has('test'));
var_dump($c->has('test2'));
var_dump($c->get('test2'));
var_dump($c->get('test'));

var_dump($c->get('sh'));
var_dump($c->get('sh'));
var_dump($c->get('sh'));

print_r($c->getRegistry());