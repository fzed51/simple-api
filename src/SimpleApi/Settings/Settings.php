<?php
declare(strict_types=1);

namespace SimpleApi\Settings;

/**
 * Fonction de base pour les settings
 */
class Settings
{
    /**
     * Constructeur de Settings
     * @param string $configEntitiesFile
     * @param \SimpleApi\Settings\LogSettings $logSettings
     * @param \SimpleApi\Settings\MailSettings $mailSettings
     */
    public function __construct(
        readonly public string $configEntitiesFile,
        readonly public LogSettings $logSettings,
        readonly public MailSettings $mailSettings
    ) {
    }
}
