<?php

namespace What4\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareStackInterface
{
    /**
     * Add a new middleware instance to the stack
     *
     * Middleware are organized as a stack. That means middleware
     * that have been added before will be executed after the newly
     * added one (last in, first out).
     *
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function add(MiddlewareInterface $middleware): self;

    /**
     * Seed the middleware stack with a new inner request handler
     *
     * @param RequestHandlerInterface $kernel
     * @return self
     */
    public function seed(RequestHandlerInterface $kernel): self;

    /**
     * Execute the middleware stack
     *
     * This method traverses the application middleware stack and then sends the
     * resultant Response object to the HTTP client.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface;
}
