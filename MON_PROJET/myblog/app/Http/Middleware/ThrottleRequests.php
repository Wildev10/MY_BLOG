<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de Rate Limiting personnalisé
 * Protège contre le spam et les abus
 */
class ThrottleRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $limiterName = 'global'): Response
    {
        $key = $this->resolveRequestSignature($request, $limiterName);

        if (RateLimiter::tooManyAttempts($key, $this->getMaxAttempts($limiterName))) {
            return response()->json([
                'success' => false,
                'message' => 'Trop de requêtes. Veuillez réessayer dans ' . RateLimiter::availableIn($key) . ' secondes.',
                'retry_after' => RateLimiter::availableIn($key)
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::hit($key, $this->getDecayMinutes($limiterName) * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $this->getMaxAttempts($limiterName),
            RateLimiter::remaining($key, $this->getMaxAttempts($limiterName))
        );
    }

    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request, string $limiterName): string
    {
        $user = $request->user();

        if ($user) {
            return $limiterName . '|' . $user->id;
        }

        return $limiterName . '|' . $request->ip();
    }

    /**
     * Get max attempts based on limiter name
     */
    protected function getMaxAttempts(string $limiterName): int
    {
        return match ($limiterName) {
            'auth' => 5,           // 5 tentatives de login/register
            'like' => 30,          // 30 likes par minute
            'comment' => 10,       // 10 commentaires par minute
            'post' => 5,           // 5 posts par heure
            'api' => 60,           // 60 requêtes API par minute
            default => 100,
        };
    }

    /**
     * Get decay time in minutes
     */
    protected function getDecayMinutes(string $limiterName): int
    {
        return match ($limiterName) {
            'auth' => 1,           // Reset après 1 minute
            'like' => 1,           // Reset après 1 minute
            'comment' => 1,        // Reset après 1 minute
            'post' => 60,          // Reset après 1 heure
            'api' => 1,            // Reset après 1 minute
            default => 1,
        };
    }

    /**
     * Add rate limit headers to response
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remainingAttempts),
        ]);

        return $response;
    }
}
