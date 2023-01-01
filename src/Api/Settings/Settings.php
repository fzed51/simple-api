<?php

namespace SimpleApi\Settings;

/**
 * Fonction de base pour les settings
 */
class Settings
{
    /**
     * Constructeur de Settings
     * @param \SimpleApi\Settings\LogSettings $logSetting
     */
    public function __construct(readonly protected LogSettings $logSetting)
    {
    }
}
