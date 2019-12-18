<?php

declare(strict_types=1);

namespace What4\Http\Interfaces;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareStackableInterface extends RequestHandlerInterface
{
    /**
     * @return MiddlewareInterface
     */
    public function getNext(): MiddlewareInterface;

    /**
     * @param MiddlewareInterface $next
     * @return self
     */
    public function setNext(MiddlewareInterface $next): MiddlewareStackableInterface;

    /**
     * @return RequestHandlerInterface
     */
    public function getHandler(): RequestHandlerInterface;

    /**
     * @param RequestHandlerInterface $handler
     * @return self
     */
    public function setHandler(RequestHandlerInterface $handler): MiddlewareStackableInterface;
}
