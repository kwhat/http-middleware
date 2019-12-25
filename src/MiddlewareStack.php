<?php

declare(strict_types=1);

namespace What4\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareStack implements MiddlewareStackInterface
{
    /** @var RequestHandlerInterface $resolver */
    protected $resolver;

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
        $this->resolver = new MiddlewareStackable($middleware, $this->resolver);
        return $this;
    }

    /**
     * @inheritDoc
     * @param RequestHandlerInterface $kernel
     * @return self
     */
    public function seed(RequestHandlerInterface $kernel): MiddlewareStackInterface
    {
        if ($this->resolver instanceof MiddlewareStackableInterface) {
            $next = $this->resolver;
            while ($next->getHandler() instanceof MiddlewareStackableInterface) {
                $next = $next->getHandler();
            }

            $next->setHandler($kernel);
        } else {
            $this->resolver = $kernel;
        }

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->resolver->handle($request);
    }
}
