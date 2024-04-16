<?php

$container = new Framework\Container;

$container->set(App\Database::class, function () {

    // object of database
    return new App\Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});


// By adding an entry to the containers registry for the interface, 
// we've decoupled the dispatcher from a concrete class.
$container->set(Framework\TemplateViewerInterface::class, function(){
    // And inside the closure, we'll return a new object of the PHP Template Viewer class.
    return new Framework\PHPTemplateViewer;
});

return $container;  