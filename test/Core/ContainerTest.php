<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Test\Core;

use Monkey\Core\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Test\TestCase;

class ContainerTest extends TestCase
{
    const SERVICES_PATH = __DIR__ . '/../../resources/';
    const SERVICES_FILE = 'services.yaml';

    public function testStaticInstance()
    {
        $sut = new Container(
            self::SERVICES_PATH,
            self::SERVICES_FILE
        );

        $this->assertInstanceOf(Container::class, $sut);
        
        $secondSut = Container::getInstance();
        $this->assertEquals($sut, $secondSut);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLoadException()
    {
        $sut = new Container(
            __DIR__ . '../non-existent-folder',
            self::SERVICES_FILE
        );
    }
}
