<?php

namespace Sys\File;

class View extends File
{

    public function __construct(string $path, bool $check = true)
    {
        parent::__construct(VIEWS_PATH . '/' . $path, $check);
    }

}
