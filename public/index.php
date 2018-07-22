<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

use Monkey\Core\Application;

require_once '../vendor/autoload.php';

Application::getInstance()->handleHttpRequest();
