<?php

declare(strict_types=1);

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface
{
    public function render(string $template, array $data = []): string
    {
        $views_dir = dirname(__DIR__, 2) . "/views/";
        
        //  let's read the contents of the file into a string using the file getcontents function.
        $code = file_get_contents($views_dir. $template);

        // checking that double quotes are used, and returns 1 if it does not match
        if (preg_match('#^{% extends "(?<template>.*)" %}#', $code, $matches) === 1){
            
            $base = file_get_contents($views_dir. $matches["template"]);
            
            $block = $this->getBlocks($code);

            $code = $this->replaceYields($base, $block);

            //exit;
        }

        $code = $this->replaceVariables($code);
        
        $code = $this->repalcePHP($code);

        // turn array of objects into individual variables unsing extract
        extract($data, EXTR_SKIP);

        // We are doing this by turning on output buffering to capture the output, then requiring the file instead
         ob_start();

        //  require dirname(__DIR__, 2) . "/views/$template";
        
        eval("?>$code");

        return ob_get_clean();
        //return file_get_contents("views/$template");
    }

    private function replaceVariables(string $code): string
    {
        return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1 ?? '') ?>", $code);
    }

    private function repalcePHP(string $code): string
    {
        // use the Pregreplace function to replace the custom syntax with PHP.
        return preg_replace("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);

    }

    private function getBlocks(string $code): array 
    {
        preg_match_all("#{% block (?<name>\w+) %}(?<content>.*?){% endblock %}#s", $code, $matches, PREG_SET_ORDER);

        $blocks =[];

        foreach ($matches as $match) {
            // index                     value
        $blocks[$match["name"]] = $match["content"];

        }

        return $blocks;
    }

    private function replaceYields(string $code, array $blocks): string
    {
        // match all the yield statements, (?<name>\w+) shows the identifier is a sequence of one or more word characters
        preg_match_all("#{% yield (?<name>\w+) %}#", $code, $matches, PREG_SET_ORDER);

        foreach ($matches as $match){
            $name = $match["name"];
            // we are using the same name for the value of blocks
            $block = $blocks[$name];

            // setting the name of the content variable as the identifier
            // parsing  $block as replacement and $code as the subject
          $code = preg_replace("#{% yield $name %}#", $block, $code);
        }

       // print_r($matches);

        return $code;
    }
}