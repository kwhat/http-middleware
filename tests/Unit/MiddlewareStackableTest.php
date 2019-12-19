<?php

namespace What4\Http\Middleware\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use What4\Http\Middleware\MiddlewareStackable;

/**
 * RequestHandlerInterface to chain MiddlewareInterface's
 */
class MiddlewareStackableTest extends TestCase
{
    /** @var MockObject|MiddlewareInterface $middleware */
    private $middleware;

    /** @var MockObject|RequestHandlerInterface $handler */
    private $handler;

    /** @var MiddlewareStackable $subject */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = $this->createMock(MiddlewareInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);

        $this->subject = new MiddlewareStackable(
            $this->middleware,
            $this->handler
        );
    }

    public function testGetNext(): void
    {
        $this->assertEquals($this->middleware, $this->subject->getNext());
    }

    public function testSetNext(): void
    {
        /** @var MockObject|MiddlewareInterface $next */
        $next = $this->createMock(MiddlewareInterface::class);
        $this->subject->setNext($next);

        $this->assertEquals($next, $this->subject->getNext());
    }

    public function testGetHandler(): void
    {
        $this->assertEquals($this->handler, $this->subject->getHandler());
    }

    public function testSetHandler(): void
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $this->subject->setHandler($handler);

        $this->assertEquals($handler, $this->subject->getHandler());
    }

    public function testHandle(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->middleware
            ->expects($this->atLeastOnce())
            ->method("process")
            ->with($request, $this->handler);

        $this->subject->handle($request);
    }
}
