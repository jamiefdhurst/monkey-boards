<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Monkey\Error;

use Monkey\Core\Controller;

/**
 * Handle an error.
 */
class ErrorController extends Controller
{
    public function notFoundError()
    {
        // TODO: Actual response
        return $this->rawResponse('ERROR!');
    }

    public function permissionError()
    {
        // TODO: Actual response
        return $this->rawResponse('ERROR!');
    }

    public function systemError()
    {
        // TODO: Actual response
        return $this->rawResponse('ERROR!');
    }
}
