<?php

namespace SimpleApi\Settings;

/**
 * Maramètre des mail
 */
class MailSettings
{
    /**
     * @param string $from
     */
    public function __construct(
        readonly public string $from
    ) {
    }
}
