<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Monkey\Core;

use Symfony\Component\HttpFoundation\Response;

/**
 * Superclass for all controllers.
 */
class Controller
{
    /**
     * Can be overridden in individual controllers.
     *
     * @param Response $response
     */
    public function afterAction(Response $response)
    {
    }

    /**
     * Can be overridden in individual controllers.
     */
    public function beforeAction()
    {
    }

    /**
     * Create a new instance of a response.
     *
     * @param int|null $statusCode
     * @param string|null $contentType
     * @return Response
     */
    private function createResponse(
        ?int $statusCode,
        ?string $contentType
    ): Response {
        $response = new Response();
        if (null !== $statusCode) {
            $response->setStatusCode($statusCode);
        }
        if (null !== $contentType) {
            $response->headers->set('Content-Type', $contentType);
        }

        return $response;
    }

    /**
     * Send a raw response from a string.
     *
     * @param string $rawContent
     * @param int|null $statusCode
     * @param string|null $contentType
     * @return Response
     */
    protected function rawResponse(
        string $rawContent,
        ?int $statusCode = null,
        ?string $contentType = null
    ): Response {
        $response = $this->createResponse($statusCode, $contentType);
        $response->setContent($rawContent);

        return $response;
    }
}
