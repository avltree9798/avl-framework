<?php

class Compiler
{
    /**
     * @var array
     */
    private $variables;

    /**
     * @var string
     */
    protected $viewFile;

    /**
     * Compiler constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $viewFile
     * @param array  $vars
     * @return $this
     */
    public function setView($viewFile = '', $vars = [])
    {
        $this->viewFile = $viewFile;
        $this->variables = $vars;

        return $this;
    }

    private function toHTMLPHP($viewFile)
    {
        $contents = file_get_contents($viewFile);
        $contents = preg_replace('/{{(.*?)}}/', '<?php e($1); ?>', $contents);
        $contents = preg_replace('/@foreach\((.*?)\)@/', '<?php foreach($1){ ?>', $contents);
        $contents = preg_replace('/@endforeach@/', '<?php } ?>', $contents);
        $contents = preg_replace('/@if\((.*?)\)@/', '<?php if($1){ ?>', $contents);
        $contents = preg_replace('/@elseif\((.*?)\)@/', '<?php }else if($1){ ?>', $contents);
        $contents = preg_replace('/@else@/', '<?php } else { ?>', $contents);
        $contents = preg_replace('/@endif@/', '<?php } ?>', $contents);
        $contents = preg_replace('/@php\((.*?)\)@/', '<?php $1; ?>', $contents);
        $contents = preg_replace('/@dd\((.*?)\)@/', '<?php dd($1); ?>', $contents);
        $contents = preg_replace('/{!!(.*?)!!}/', '<?php echo $1; ?>', $contents);
        $self = $this;
        $contents = preg_replace_callback('#@include\(\'(.*?)\'\)#', function ($matches) use (&$self) {
            $str = $matches[0];
            $start = strpos($str, '\'');
            $end = strpos($str, '\'', $start + 1);
            $length = $end - $start;
            $result = $GLOBALS['config']['path']['view'] . substr($str, $start + 1, $length - 1) . '.avl.php';

            return $self->toHTMLPHP($result);
        }, $contents);

        return $contents;
    }

    public function render()
    {
        if (file_exists($this->viewFile)) {
            $file_content = $GLOBALS['config']['path']['cache_view'] . md5(file_get_contents($this->viewFile));
            if ( ! file_exists($file_content)) {
                $contents = $this->toHTMLPHP($this->viewFile);
                file_put_contents($file_content, $contents);
            }
            extract($this->variables);
            require_once $file_content;
        } else {
            abort(404, 'View file not found');
        }
    }
}