<?php

namespace Sys\View;

class Block
{

    /**
     *
     * @var \Sys\File\File
     */
    private $template;

    /**
     *
     * @var array
     */
    private $data;

    /**
     * 
     * @param \Sys\File\File $template
     * @param array $data
     */
    public function __construct(\Sys\File\File $template, array $data = array())
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * 
     * @return string
     */
    public function render(): string
    {
        foreach ($this->data as $key => $value) {
            $$key = $value;
        }

        ob_start();

        include $this->template->getPath();

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

}
