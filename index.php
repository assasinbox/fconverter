<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alex
 * Date: 06.10.13
 */

require 'Slim/Slim.php';
require 'Views/TwigView.php';
require 'converter/Converter.php';

TwigView::$twigDirectory = __DIR__ . '/Twig/lib/Twig/';

$app = new Slim(array(
    'view' => new TwigView,
    'debug' => true,
));

$app->get('/', function () use ($app){
    $app->render('home.html');
});


$app->post('/', function () use ($app) {
    if(!empty($_FILES['file']['name'])){
        $converter = new Converter();
        $converter->convert($_FILES['file']['name'], $_FILES['file']['tmp_name'], $_POST['format']);
    }else{
        $app->render('home.html', array('error' => 'empty file field'));
    }
});

$app->run();

