<?php

namespace What4\Http\Server;

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
     * @inheritDoc
     * @param MiddlewareInterface $next
     * @param RequestHandlerInterface $handler
     */
    public function __construct(MiddlewareInterface $next, RequestHandlerInterface $handler)
    {
        $this->next = $next;
        $this->handler = $handler;
    }

    /**
     * @inheritDoc
     * @return MiddlewareInterface
     */
    public function getNext(): MiddlewareInterface
    {
        return $this->next;
    }

    /**
     * @inheritDoc
     * @param MiddlewareInterface $next
     * @return MiddlewareStackableInterface
     */
    public function setNext(MiddlewareInterface $next): MiddlewareStackableInterface
    {
        $this->next = $next;
        return $this;
    }

    /**
     * @inheritDoc
     * @return RequestHandlerInterface
     */
    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @inheritDoc
     * @param RequestHandlerInterface $handler
     * @return MiddlewareStackableInterface
     */
    public function setHandler(RequestHandlerInterface $handler): MiddlewareStackableInterface
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @inheritDoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->next->process($request, $this->handler);
    }
}
