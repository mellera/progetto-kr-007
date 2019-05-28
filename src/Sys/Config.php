<?php

namespace Sys;

abstract class Config
{

    public function getLoggerPath(): string
    {
        return '{{Y}}/{{m}}/{{d}}';
    }

    public function getLoggerFilename(): string
    {
        return '{{user:username}}.log';
    }

    public function getLoggerPrefix(): string
    {
        return '[ {{H}}:{{i}}:{{s}} ][ {{log:level}} ][ {{caller:file}} ][ {{caller:info}} ] ';
    }

    abstract public function getRootPath(): string;

    abstract public function getLoggerBasePath(): string;

    abstract public function getLogLevel(): string;

    abstract public function handleException(\Throwable $ex);

}
