<?php

declare(strict_types=1);

namespace What4\Http\Server\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use What4\Http\Middleware\MiddlewareStack;

class MiddlewareStackTest extends TestCase
{
    /** @var RequestHandlerInterface $subject */
    private $kernel;

    /** @var MockObject|MiddlewareInterface $middleware */
    private $middleware;

    /** @var MiddlewareStack $subject */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->kernel = $this->createMock(RequestHandlerInterface::class);
        $this->middleware = $this->createMock(MiddlewareInterface::class);
        $this->subject = new MiddlewareStack($this->kernel);
    }

    public function testAdd(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $this->middleware
            ->expects($this->once())
            ->method("process")
            ->with($request, $this->handler);

        $this->subject->add($this->middleware)->run($request);
    }

    public function testSeed(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $this->kernel
            ->expects($this->never())
            ->method("handle")
            ->with($request);
    }

    public function testRun(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->kernel
            ->expects($this->once())
            ->method("handle")
            ->with($request);

        $this->middleware
            ->expects($this->once())
            ->method("process")
            ->with($request, $this->handler);

        $this->subject->run($request);
    }
}
