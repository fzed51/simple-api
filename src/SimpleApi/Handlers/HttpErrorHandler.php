<?php
declare(strict_types=1);

namespace SimpleApi\Handlers;

use HttpException\HttpException;
use JsonException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Handler pour les erreur
 */
class HttpErrorHandler extends ErrorHandler
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable              $exception,
        bool                   $displayErrorDetails,
        bool                   $logErrors,
        bool                   $logErrorDetails
    ): ResponseInterface {
        if (!is_a($exception, HttpException::class)) {
            throw new LogicException(sprintf("%s n'est pas une %s", $exception::class, HttpException::class));
        }
        $response = $this->responseFactory->createResponse($exception->getCode());
        if ($exception->getCode() >= 500) {
            $this->logger->alert($exception::class . " : " . $exception->getMessage(), [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
        } else {
            $this->logger->error($exception::class . " : " . $exception->getMessage(), [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
        }
        $body = [
            'message' => $exception->getMessage()
        ];
        try {
            $strBody = json_encode($body, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->logger->critical(
                "Erreur lors de la transformation en JSON d'un message : " . $e->getMessage(),
                [
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );
            $strBody = '{"message":"Erreur Interne"}';
        }
        $response->getBody()->write($strBody);
        $response->withHeader("content-type", "application/json");
        return $response;
    }
}
