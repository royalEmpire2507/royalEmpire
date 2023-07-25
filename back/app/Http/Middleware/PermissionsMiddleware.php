<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PermissionsMiddleware
{

    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::exists('permissions')) {
            return response()->json(['permissions' => "your profile don't have permissions"], 401);
        } else {
            $perms = Session::get('permissions');
            $route = $request->route();
            $nameRoute = '';
            $typeRoute = '';
            $actionRoute = '';
            $nameModuleRoute = '';
            $authorized = false;
            $configPerms = (array)$perms['permissions']['admin'];
            $modulePerms = (array)$perms['permissions']['modules'];
            $deduplicatePerms = (array)$perms['permissions']['deduplicate'];
            $imporPerms = (array)$perms['permissions']['importPermission'];
            $deleteMassPerms = (array)$perms['permissions']['massiveDeletePermission'];
            $buttomsPerms = (array)$perms['permissions']['buttoms'];

            if (isset($route->action['as'])) {
                $nameRoute = explode('|', $route->action['as'])[2];
                $typeRoute = explode('|', $route->action['as'])[1];
                $actionRoute = explode('|', $route->action['as'])[0];
                
                if ($typeRoute == 'modules') {
                    $nameModuleRoute = explode('|', $route->action['as'])[3];
                    if ($nameModuleRoute == 'custom') {
                        $nameModuleRoute = $route->parameters['module'];
                    }
                }
            
                if ($typeRoute == 'config') {
                    if (in_array($nameRoute, $configPerms)) {
                        $authorized = true;
                    }
                } elseif ($typeRoute == 'modules') {
                    if (
                        isset($modulePerms[$nameModuleRoute]) &&
                        in_array($actionRoute, $modulePerms[$nameModuleRoute])
                    ) {
                        $authorized = true;
                    }
                } elseif ($typeRoute == 'deduplicate') {
                    if (isset($deduplicatePerms[$nameModuleRoute]) && $deduplicatePerms[$nameModuleRoute]) {
                        $authorized = true;
                    }
                } elseif ($typeRoute == 'importPermission') {
                    if (isset($imporPerms[$nameModuleRoute]) && $imporPerms[$nameModuleRoute]) {
                        $authorized = true;
                    }
                } elseif ($typeRoute == 'massiveDeletePermission') {
                    if (isset($deleteMassPerms[$nameModuleRoute]) && $deleteMassPerms[$nameModuleRoute]) {
                        $authorized = true;
                    }
                } elseif ($typeRoute == 'buttoms') {
                    if (in_array($nameRoute, $buttomsPerms)) {
                        $authorized = true;
                    }
                }

            }
            
            if ($authorized == true) {
                return $next($request);
            } else {
                return response()->json(["result"=>"error", "message"=>"your profile don't have permissions"], 401);
            }

        }

    }
}
