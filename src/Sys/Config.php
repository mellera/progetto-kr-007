<?php

namespace Sys;

interface Config
{

    public function getLoggerPath(): string;

    public function getLoggerFilename(): string;

    public function getLoggerPrefix(): string;

    public function getRootPath(): string;

    public function getLoggerBasePath(): string;

    public function getLogLevel(): string;

    public function handleException(\Throwable $ex);

}
