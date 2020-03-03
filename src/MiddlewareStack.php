<?php

namespace What4\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * LIFO stack where middleware is placed.
 */
class MiddlewareStack implements MiddlewareStackInterface
{
    /** @var RequestHandlerInterface $stack */
    protected $stack;

    /**
     * @param RequestHandlerInterface $kernel
     */
    public function __construct(RequestHandlerInterface $kernel)
    {
        $this->seed($kernel);
    }

    /**
     * @inheritDoc
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function add(MiddlewareInterface $middleware): MiddlewareStackInterface
    {
        $this->stack = new MiddlewareStackable($middleware, $this->stack);
        return $this;
    }

    /**
     * @inheritDoc
     * @param RequestHandlerInterface $kernel
     * @return self
     */
    public function seed(RequestHandlerInterface $kernel): MiddlewareStackInterface
    {
        // Check to see if the tip of the stack is a RequestHandler or MiddlewareInterface
        if ($this->stack instanceof MiddlewareStackableInterface) {
            $next = $this->stack;
            while ($next->getHandler() instanceof MiddlewareStackableInterface) {
                $next = $next->getHandler();
            }

            $next->setHandler($kernel);
        } else {
            $this->stack = $kernel;
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->stack->handle($request);
    }
}
