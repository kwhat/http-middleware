<?php

declare(strict_types=1);

namespace What4\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareStackableInterface extends RequestHandlerInterface
{
    /**
     * Get the MiddlewareInterface executed by this MiddlewareStackableInterface
     *
     * @return MiddlewareInterface
     */
    public function getNext(): MiddlewareInterface;

    /**
     * Set the MiddlewareInterface executed by this MiddlewareStackableInterface
     *
     * @param MiddlewareInterface $next
     * @return self
     */
    public function setNext(MiddlewareInterface $next): MiddlewareStackableInterface;

    /**
     * Get the next RequestHandlerInterface passed to the contained MiddlewareInterface
     *
     * @return RequestHandlerInterface
     */
    public function getHandler(): RequestHandlerInterface;

    /**
     * Set the next RequestHandlerInterface passed to the contained MiddlewareInterface
     *
     * @param RequestHandlerInterface $handler
     * @return self
     */
    public function setHandler(RequestHandlerInterface $handler): MiddlewareStackableInterface;
}
