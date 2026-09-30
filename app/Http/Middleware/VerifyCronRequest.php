<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authenticates Vercel Cron requests.
 *
 * This fails closed on purpose. The previous guard skipped verification
 * entirely when no secret was configured, which left /api/payment-reminders
 * and /api/backup-db reachable by anyone: a public GET was enough to make the
 * app email every customer with an unpaid balance, or trigger a full database
 * export. An unconfigured secret is a deployment mistake, so it is reported
 * loudly here rather than silently allowing the request through.
 */
class VerifyCronRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('services.cron.secret');

        if ($secret === '') {
            Log::error('Cron request rejected: no secret configured. Set CRON_SECRET for the Vercel project.');

            return response('Cron is not configured.', 503);
        }

        $provided = (string) $request->header('Authorization', '');

        if (! hash_equals('Bearer ' . $secret, $provided)) {
            Log::warning('Cron request rejected: bad or missing Authorization header.');

            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
