<?php

declare(strict_types=1);

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface
{
    public function render(string $template, array $data = []): string
    {
        //  let's read the contents of the file into a string using the file getcontents function.
        $code = file_get_contents(dirname(__DIR__, 2). "/views/$template");

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
return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1) ?>", $code);
}

private function repalcePHP(string $code): string
{
// use the Pregreplace function to replace the custom syntax with PHP.
return preg_replace("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);

}


}