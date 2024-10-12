<?php

namespace bootstrap\Framework\Router;

use bootstrap\Framework\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next);
}
