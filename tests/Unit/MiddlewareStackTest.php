<?php

namespace What4\Http\Server\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use What4\Http\Server\MiddlewareStack;

class MiddlewareStackTest extends TestCase
{
    /** @var RequestHandlerInterface $subject */
    private $kernel;

    /** @var MiddlewareStack $subject */
    private $subject;

    /** @var MockObject|ServerRequestInterface $request */
    private $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->kernel = $this->createMock(RequestHandlerInterface::class);
        $this->subject = new MiddlewareStack($this->kernel);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testAdd(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        /** @var MockObject|MiddlewareInterface $middleware */
        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware
            ->expects($this->once())
            ->method("process")
            ->with($this->request, $this->kernel)
            ->willReturn($response);

        $this->subject->add($middleware)->run($this->request);
    }

    public function testSeed(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->kernel
            ->expects($this->never())
            ->method("handle");

        /** @var MockObject|RequestHandlerInterface $seed */
        $seed = $this->createMock(RequestHandlerInterface::class);
        $seed
            ->expects($this->once())
            ->method("handle")
            ->with($this->request)
            ->willReturn($response);

        $middleware = new class implements MiddlewareInterface {
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return $handler->handle($request);
            }
        };

        $this->subject
            ->add($middleware)
            ->add($middleware)
            ->seed($seed)
            ->run($this->request);
    }

    public function testRun(): void
    {
        $this->kernel
            ->expects($this->once())
            ->method("handle")
            ->with($this->request);

        $this->subject->run($this->request);
    }
}
