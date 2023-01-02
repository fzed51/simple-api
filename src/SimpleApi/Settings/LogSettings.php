<?php

namespace SimpleApi\Settings;

use Monolog\Level;

/**
 * Paramètre des logs
 */
class LogSettings
{
    /**
     * Constructeur de LogSettings
     * @param string $apiName
     * @param string $path
     * @param \Monolog\Level $level
     */
public function __construct(
    readonly public string $apiName,
    readonly public string $path,
    readonly public Level $level
)
{
}
}