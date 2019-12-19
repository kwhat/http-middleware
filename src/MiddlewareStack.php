<?php

declare(strict_types=1);

namespace What4\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareStack implements MiddlewareStackInterface
{
    /** @var RequestHandlerInterface $resolver */
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
            $next = $this->middleware;

            $i = 0;
            while ($next->getHandler() instanceof MiddlewareStackableInterface) {
                $next = $next->getHandler();
                echo ++$i;
            }

            $next->setHandler($kernel);
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
