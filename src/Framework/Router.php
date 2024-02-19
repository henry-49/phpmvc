<?php

namespace Framework;

class Router 
{
    private array $routes = [];

    public function add(string $path, array $params = []): void
    {
        $this->routes[] = [
            "path" => $path,
            "params" => $params
        ];
    }

    public function match(string $path): array|bool
    {
        $path = urldecode($path);
        
        $path = trim($path, '/');

        foreach ($this->routes as $route) {
        
            // $pattern = "#^/(?<controller>[a-z]+)/(?<action>[a-z]+)$#";

           // echo $pattern, "\n", $route["path"], "\n";

            $pattern = $this->getPatternFromRoutePath($route["path"]);

           // echo $pattern, "\n";
            
            if (preg_match($pattern, $path, $matches)) {

                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                $params = array_merge($matches, $route["params"]);

                return $params;
                
                // print_r($matches);
                
                // exit("match");
            }
        }
       /*  foreach ($this->routes as $route) {

            if ($route["path"] == $path){

                return $route["params"];
            }
        }
 */
            return false; 
    }

    private function getPatternFromRoutePath(string $route_path): string
    {
        $route_path = trim($route_path, '/');

        $segments = explode("/", $route_path);

        $segments = array_map(function (string $segment): string {

            if(preg_match('#^\{([a-z][a-z0-9]*)\}$#', $segment, $matches)){

                // get the name of the segment from the matched array
                return "(?<" . $matches[1] .   ">[^/]*)"; // match any character that is not / slash
            }

            if (preg_match('#^\{([a-z][a-z0-9]*):(.+)\}$#', $segment, $matches)) {

                // get the name of the segment from the matched array
                return  "(?<" . $matches[1] .   ">" . $matches[2] .")";
            }

            return $segment;

        }, $segments);

        // to make url case insensitive we add i after the closing delimiter
        // u for any unicode characters
        return "#^" . implode("/", $segments) . "$#iu";

        // echo $pattern, "\n";
       // print_r($segments);
    }

}