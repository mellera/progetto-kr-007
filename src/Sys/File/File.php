<?php

namespace Sys\File;

class File
{

    private $path;

    public function __construct(string $path, bool $check = true)
    {
        $this->setPath($path, $check);
    }

    public function setPath(string $path, bool $check = true)
    {
        $this->path = $path;

        if ($check === true) {
            $this->isFile();
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function isFile()
    {
        if (is_file($this->path) === false) {
            throw new \Exception('File ' . $this->getPath() . ' assente');
        }
    }

    public function putContent(string $content)
    {
        if (file_put_contents($this->getPath(), $content) === false) {
            throw new \Exception('Impossibile creare il file ' . $this->getPath());
        }
    }

    public function getContentAsString(): string
    {
        $this->isFile();

        $string = file_get_contents($this->getPath());

        if ($string === false) {
            throw new \Exception('Impossibile leggere il contenuto del file ' . $this->getPath());
        }

        return $string;
    }

    public function getContentAsArray(): array
    {
        $this->isFile();

        $array = file($this->getPath());

        if ($array === false) {
            throw new \Exception('Impossibile leggere il contenuto del file ' . $this->getPath());
        }

        return $array;
    }

    public function move(\File $to)
    {
        if (rename($this->getPath(), $to->getPath()) === false) {
            throw new \Exception('Impossibile spostare il file ' . $this->getPath());
        }

        $this->setPath($to);
    }

    public function delete()
    {
        if (unlink($this->getPath()) === false) {
            throw new \Excpetion('Impossibile eliminare il file ' . $this->getPath());
        }
    }

    public function __toString(): string
    {
        return $this->getPath();
    }

}
