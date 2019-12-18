<?php

declare(strict_types=1);

namespace What4\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RequestHandlerInterface to chain MiddlewareInterface's
 */
class MiddlewareStackable implements MiddlewareStackableInterface
{
    /** @var MiddlewareInterface $next */
    protected $next;

    /** @var RequestHandlerInterface $handler */
    protected $handler;

    /**
     * @param MiddlewareInterface $next
     * @param RequestHandlerInterface $handler
     */
    public function __construct(MiddlewareInterface $next, RequestHandlerInterface $handler)
    {
        $this->next = $next;
        $this->handler = $handler;
    }

    public function getNext(): MiddlewareInterface
    {
        return $this->next;
    }

    public function setNext(MiddlewareInterface $next): MiddlewareStackableInterface
    {
        $this->next = $next;
        return $this;
    }

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }


    public function setHandler(RequestHandlerInterface $handler): MiddlewareStackableInterface
    {
        $this->handler = $handler;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->next->process($request, $this->handler);
    }
}
