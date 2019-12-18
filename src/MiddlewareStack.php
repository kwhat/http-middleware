<?php

declare(strict_types=1);

namespace What4\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use What4\Http\Interfaces\MiddlewareStackableInterface;
use What4\Http\Interfaces\MiddlewareStackInterface;
use What4\Http\Middleware\MiddlewareStackable;

class MiddlewareStack implements MiddlewareStackInterface
{
    /** @var RequestHandlerInterface $middleware */
    protected $middleware;

    /**
     * @param RequestHandlerInterface $kernel
     */
    public function __construct(RequestHandlerInterface $kernel)
    {
        // Seed the middleware stack
        $this->seed($kernel);
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function add(MiddlewareInterface $middleware): MiddlewareStackInterface
    {
        $this->middleware = new MiddlewareStackable($middleware, $this->middleware);
        return $this;
    }

    /**
     * @param RequestHandlerInterface $kernel
     * @return self
     */
    public function seed(RequestHandlerInterface $kernel): MiddlewareStackInterface
    {
        if ($this->middleware instanceof MiddlewareStackableInterface) {
            $tip = $this->middleware;
            while ($tip->getNext() instanceof MiddlewareStackableInterface) {
                $tip = $tip->getHandler();
            }

            $tip->setHandler($kernel);
        } else {
            $this->middleware = $kernel;
        }

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->handle($request);
    }
}
