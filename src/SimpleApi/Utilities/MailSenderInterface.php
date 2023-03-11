<?php

namespace SimpleApi\Utilities;

interface MailSenderInterface
{
    /**
     * @param string $to
     * @param array<string,string> $data
     * @return mixed
     */
    public function send(string $to, array $data = []): void;
}