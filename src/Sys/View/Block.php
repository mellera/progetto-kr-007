<?php

namespace Sys\View;

class Block
{

    /**
     *
     * @var \Sys\File\View
     */
    private $template;

    /**
     *
     * @var array
     */
    private $data;

    /**
     * 
     * @param \Sys\File\View $template
     * @param array $data
     */
    public function __construct(\Sys\File\View $template, array $data = array())
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
