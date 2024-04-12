<?php

declare(strict_types=1);

namespace Framework;

use ReflectionMethod;
use Framework\Exceptions\PageNotFoundException;
use UnexpectedValueException;

class Dispatcher
{
    public function __construct(private Router $router,
                                private Container $container)
    { 
    }
    
    /**
     * handle
     *
     * @param  Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        $path = $this->getPath($request->uri);
        
        $params = $this->router->match($path, $request->method);
        // var_dump($params);
        // exit;

        // print_r($params);

        if ($params === false) {
            throw new PageNotFoundException("No route matched for '$path' with method '{$request->method}'");
        }

        // $segments = explode("/", $path);
        // print_r($segments);
        // exit;

        //$action = $params["action"];
        $action = $this->getActionName($params);
        $controller = $this->getControllerName($params); 
        //exit($action);

        // create object from the controller class
       // $controller_object = $this->getObject($controller);
        $controller_object = $this->container->get($controller);


        $controller_object->setRequest($request);
        
        // $controller = "App\Controllers\\" . ucwords($params["controller"]);
        // $action = $segments[2];
        // $controller = $segments[1];

        // $action = $_GET["action"];
        // $controller = $_GET["controller"];

        // setting value for controller variables
        // require "src/controllers/$controller.php";

        // if ($controller === "products") {

        //     $controller_object = new ProductsController;

        // } elseif ($controller === "home") {

        //     $controller_object = new HomeController;

        // }

       // $controller_object = new $controller(new Viewer, new Product);

        // $controller_object = new $controller(...$dependencies);

        $args = $this->getActionArguments($controller, $action, $params);

        // if ($action === "index") {

        //     $controller_object->index();

        // } elseif ($action === "show") {

        //     $controller_object->show();
        // }

        $controller_object->$action(...$args);
    }

    private function getActionArguments(string $controller, string $action, array $params): array
    {
        $args = [];
        
        $method = new ReflectionMethod($controller, $action);

        foreach($method->getParameters() as $parameter){

            $name = $parameter->getName();

             $args[$name] = $params[$name];
        }

         return $args;
    }

    private function getControllerName(array $params): string
    {

        $controller = $params["controller"];

        $controller = str_replace("-", "", ucwords(strtolower($controller), "-"));

        $namespace = "App\Controllers";

        // create namespace variables
        if(array_key_exists("namespace", $params)){

            // append namespace variable and seperate them with \\ backslashe
            $namespace .= "\\" . $params["namespace"];
        }

        return $namespace . "\\" . $controller;
    }

    private function getActionName(array $params): string
    {
        $action = $params["action"];

        $action = lcfirst(str_replace("-", "", ucwords(strtolower($action), "-")));

        return $action;
    }

    private function getPath(string $uri): string
    {
        // parse the incoming uri
        $path = parse_url($uri, PHP_URL_PATH);
        // exit($path);

        if ($path === false) {

            throw new UnexpectedValueException("Malformed URL: '$uri'");
        }

        return $path;
    }
  
}