<?php
declare(strict_types=1);

namespace SimpleApi\Utilities;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

/**
 * Utilisation de la fonction mail pour envoyer un e-mail
 */
class FnMailSender implements MailSenderInterface
{
    /**
     * @param string $from
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(protected string $from, protected LoggerInterface $logger = new NullLogger())
    {
    }

    use MailBuilderTrait;

    protected ?string $templateFilename = null;

    /**
     * @inheritDoc
     */
    public function send(string $to, array $data = []): void
    {
        if ($this->templateFilename === null) {
            throw new RuntimeException("Le template du mail n'a pas été initialisé");
        }
        $title = $this->getTitle($data);
        $body = $this->getHtml($data);

        $header = "From:$this->from \r\n";
        //$header .= "Cc:afgh@somedomain.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

        $retval = mail($to, $title, $body, $header);

        if ($retval === false) {
            $this->logger->warning("Un problème est survenue lors de l'envoi d'un e-mail dans " . static::class);
        }
    }

    /**
     * @param string $templateFilename
     */
    public function setTemplateFilename(string $templateFilename): void
    {
        $this->templateFilename = $templateFilename;
    }
}
