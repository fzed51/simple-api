<?php

namespace SimpleApi\Handlers;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\ErrorHandler;
use Throwable;

/**
 * Handler par defaut pour les erreurs
 */
class DefaultErrorHandler extends ErrorHandler
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
        $response = $this->responseFactory->createResponse(500);
        $this->logger->error($exception::class . " : " . $exception->getMessage(), [
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
        $body = ['message' => 'Erreur interne'];
        try {
            $strBody = json_encode($body, JSON_THROW_ON_ERROR);
            $response->getBody()->write($strBody);
            $response->withHeader("content-type", "application/json");
        } catch (JsonException $e) {
        }
        return $response;
    }
}
