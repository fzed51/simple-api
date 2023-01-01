<?php

namespace SimpleApi\Api\Settings;

/**
 * Paramètre des logs
 */
class LogSettings
{
    /**
     * Constructeur de LogSettings
     * @param string $apiName
     * @param string $path
     */
public function __construct(
    readonly protected string $apiName,
    readonly protected string $path
)
{
}
}