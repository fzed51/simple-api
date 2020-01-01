<?php

namespace App\Renderer;

use Slim\Http\Response;

class ApiRenderer
{
    /** @var Response réponse utilisé pour le rendu */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function success($data): Response
    {
        $dataResponse = [
            'success' => true,
            'data' => $data,
            'error' => null
        ];
        return $this->render($dataResponse);
    }

    public function error(int $code, string $err): Response
    {
        $dataResponse = [
            'success' => true,
            'data' => null,
            'error' => [
                "status" => $code,
                "message" => $err
            ]
        ];
        return $this->render($dataResponse);
    }

    private function render($data): Response
    {
        $this->response->write(json_encode($data));
        return $this->response->withHeader('content-type', 'application/json');
    }
}