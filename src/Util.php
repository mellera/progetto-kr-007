<?php

namespace Sys;

class Util
{

    public static function readManifest($manifestPath, $manifestFilename, $filename)
    {
        $file = new \Sys\File\File(ASSETS_PATH . '/' . $manifestPath . '/' . $manifestFilename);

        $json = json_decode($file->getContentAsString());

        if (!isset($json->$filename)) {
            throw new \Sys\Exception\Exception("Proprieta dell'oggetto non trovata");
        }

        return str_replace(ROOT_PATH, '', ASSETS_PATH) . '/' . $manifestPath . '/' . $json->$filename;
    }

}
