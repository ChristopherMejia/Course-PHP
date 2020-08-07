<?php

ini_set('display_errors',1);
ini_set('display_starup_error',1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createUnsafeMutable(__DIR__ . '/..');
$dotenv->load();



use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer; //Libreria para implementar un router y tener solo un punto de acceso en nuestro proyecto

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES,
); 

$routerContainer = new RouterContainer(); //Instancia del router
$map = $routerContainer->getMap(); //Generamos un mapa del router

$map->get('index', '/curso-php/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction',
    'auth' => true

    ]); // implementamos la url donde esta nuestra vista index

$map->get('addJob', '/curso-php/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction',
    'auth' => true

]);

$map->post('saveJobs', '/curso-php/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);

$map->get('addUser', '/curso-php/user/add', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'getUser',
    'auth' => true

]);

$map->post('saveUser', '/curso-php/user/add', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'storeUser'
]);

$map->get('loginForm', '/curso-php/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);

$map->get('logout', '/curso-php/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);

$map->post('auth', '/curso-php/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);

$map->get('admin', '/curso-php/admin', [
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true
]);





$matcher = $routerContainer->getMatcher();//hacemos un matcher 

$route = $matcher->match($request);


function printElement( $job) {
      
    echo '<li class="work-position">';
    echo '<h5>' . $job->title . '</h5>';
    echo '<p>' . $job->description . '</p>';
    echo '<p>' . $job->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '</ul>';
    echo '</li>';
    }

function printElementProject( $project) {
      
    echo '<li class="work-position">';
    echo '<h5>' . $project->title . '</h5>';
    echo '<p>' . $project->description . '</p>';
    echo '<p>' . $project->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '</ul>';
    echo '</li>';
    }

    if (!$route) {
        echo 'No route';
    } else {
        $handlerData = $route->handler;
        $controllerName = $handlerData['controller'];
        $actionName = $handlerData['action'];
        $needsAuth = $handlerData['auth'] ?? false;

        $sessionUserId = $_SESSION['id_user'] ?? null;
        
        if($needsAuth && !$sessionUserId){
            header('location: /curso-php/login' ); //redirecciona al login cuando intenta acceder a una ruta protegida
            exit;
        }
    
        $controller = new $controllerName;
        $response = $controller->$actionName($request);
    
        foreach($response->getHeaders() as $name => $values)
        {
            foreach($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        http_response_code($response->getStatusCode());
        echo $response->getBody();
    }



?>