<?php declare(strict_types=1);
 
 namespace PaznerA\NetteInertia;
 
 class InertiaMiddleware
{
    public function __invoke($request, $next)
    {
        $response = $next($request);

        if ($request->getHeader('X-Inertia')) {
            $response = $response->withHeader('Vary', 'Accept')
                ->withHeader('X-Inertia', 'true');

            if ($request->getMethod() === 'GET' && 
                $request->getHeader('X-Inertia-Version') !== $this->getVersion()) {
                return $response->withStatus(409)
                    ->withHeader('X-Inertia-Location', $request->getUri());
            }
        }

        return $response;
    }

    private function getVersion(): ?string
    {
        // Implementace verzování assets
        return null;
    }
}