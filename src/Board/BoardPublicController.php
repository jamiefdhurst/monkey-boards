<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Monkey\Board;

use Monkey\Core\Controller;

/**
 * Public-facing board view, displays list of boards.
 */
class BoardPublicController extends Controller
{
    public function index()
    {
        // TODO: Actually return something valid here and get some boards!
        return $this->rawResponse('TEST!');
    }
}
