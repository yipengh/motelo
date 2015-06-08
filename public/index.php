<?php

session_start();

require '../vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
));

// Create monolog logger and store logger in container as singleton 
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('motelo');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Database PDO
$app->db = new PDO('sqlite:' . DB_DIR . DB_FILENAME);
$app->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$app->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// Define routes
$app->get('/', function () use ($app) {
    $app->redirect('/view');
});

$app->get('/list/:type', function($type) use ($app) {});

$app->get('/create/:type', function($type) use ($app) {
    $app->render('header.php');
    $app->render('flash.php');
    switch ($type) {
        default:
        case CREATION_TYPE_SOLUTION:
            $app->render('creation_solution.php');
            break;
        case CREATION_TYPE_INSTANCE:
            $app->render('creation_instance.php');
            break;
        case CREATION_TYPE_MODEL:
            $app->render('creation_model.php');
            break;
    }
    $app->render('jsincludes.php', array(
        'scripts' => array('/js/form.js')
    ));
    $app->render('footer.php');
});

$app->post('/create/:type', function($type) use ($app) {
    FormActions::create($app, $type);
    $app->redirect('/create/' . $type);
});

$app->get('/view', function() use ($app) {
    $app->render('header.php');
    $app->render('flash.php');
    $app->render('view.php');
    $app->render('jsincludes.php', array(
        'libs'    => array(
            '/js/lib/cytoscape.min.js'
        ),
        'scripts' => array(
            '/js/view.js'
        )
    ));
    $app->render('footer.php');
});

// API
$app->get('/getdat/:filename', function($filename) use ($app) {
    echo Utils::dat2json(file_get_contents(STORAGE_DIR . $filename . '.dat'));
});

$app->get('/getsol/:filename', function($filename) use ($app) {
    echo Utils::sol2json(file_get_contents(STORAGE_DIR . $filename . '.sol'));
});

$app->get('/getmodels', function() use ($app) {
    echo API::getList($app->db, 'model');
});

$app->get('/getinstances', function() use ($app) {
    echo API::getList($app->db, 'instances');
});

$app->get('/getsolutions', function() use ($app) {
    echo API::getList($app->db, 'solutions');
});

// Run app
$app->run();

// END /public/index.php