<?php

namespace Mca\AccessIntel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\Permission\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

class EnsureMcaAccessIntelRoot
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        if (config('access-intel.access.use_permission_root', true) && class_exists(PermissionService::class)) {
            if (app(PermissionService::class)->isRoot($user)) {
                return $next($request);
            }

            abort(403, mca_intel('errors.root_only'));
        }

        $column = (string) config('access-intel.access.role_column', 'role_id');
        $rootRole = (string) config('access-intel.access.root_role', 'root');
        $value = $user->{$column} ?? null;

        if ($column === 'role_id' && $value && class_exists(\Mca\Permission\Models\Role::class)) {
            if (\Mca\Permission\Models\Role::query()->whereKey($value)->where('is_root', true)->exists()) {
                return $next($request);
            }
        } elseif ((string) $value === $rootRole) {
            return $next($request);
        }

        abort(403, mca_intel('errors.root_only'));
    }
}
