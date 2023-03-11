<?php
declare(strict_types=1);

namespace SimpleApi\Utilities;

use RuntimeException;

/**
 * Donne le comportement pour générer le contenu d'un mail et de son titre
 */
trait MailBuilderTrait
{
    private string $titleTemplate;
    private string $textTemplate;
    private string $htmlTemplate;

    /**
     * Initialise le fichier de template
     * @param string $filename
     * @return void
     */
    public function setTemplate(string $filename): void
    {
        if (!is_file($filename)) {
            throw new RuntimeException("$filename n'est pas un fichier");
        }
        $realFilename = realpath($filename);
        if ($realFilename === false) {
            throw new RuntimeException(
                "Une erreur s'est produite lors de la détermination du chemin complet du fichier de modèle."
            );
        }
        $contentTemplate = file_get_contents($realFilename);
        if ($contentTemplate === false) {
            throw new RuntimeException(
                "Une erreur s'est produite lors de la lecture du fichier de modèle."
            );
        }
        $splitedTemplate = explode(PHP_EOL . "---" . PHP_EOL, $contentTemplate);
        if (count($splitedTemplate) !== 3) {
            throw new RuntimeException(
                "Une erreur s'est produite le fichier n'est pas un modèle valide."
            );
        }
        $this->titleTemplate = $splitedTemplate[0];
        $this->textTemplate = $splitedTemplate[1];
        $this->htmlTemplate = $splitedTemplate[2];
    }

    /**
     * @param array<string,mixed> $data
     * @return string
     */
    protected function getTitle(array $data): string
    {
        return $this->build($this->titleTemplate, $data);
    }

    /**
     * @param string $template
     * @param array<string,mixed> $data
     * @return string
     */
    private function build(string $template, array $data): string
    {
        $data = array_change_key_case($data, CASE_UPPER);
        $out = $template;
        foreach ($data as $key => $value) {
            $out = str_replace("%$key%", $value, $out);
        }
        return $out;
    }

    /**
     * @param array<string,mixed> $data
     * @return string
     */
    protected function getText(array $data): string
    {
        return $this->build($this->textTemplate, $data);
    }

    /**
     * @param array<string,mixed> $data
     * @return string
     */
    protected function getHtml(array $data): string
    {
        return $this->build($this->htmlTemplate, $data);
    }
}
