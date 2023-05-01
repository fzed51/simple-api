<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Level;
use SimpleApi\Settings\LogSettings;
use SimpleApi\Settings\MailSettings;
use SimpleApi\Settings\Settings;

return static function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([Settings::class => function () {
        return new Settings(
            __DIR__ . '/entity.json',
            new LogSettings(
                'simple-api',
                isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                Level::Debug
            ),
            new MailSettings(
                "contact@fzed51.com"
            )
        );
    }]);
};
