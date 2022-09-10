<?php
namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = request()->segment(1);
        $locales = config('translatable.locales');
        $default = config('app.fallback_locale');
        if ($locale !== $default && array_key_exists($locale, $locales)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale($default);
        }
//        $noTranslationPrefixes = ['admin']; // add what ever prefix that you don't need translation
//        $defaultLocale = config('app.fallback_locale');
//        $currentLocale = $request->segment(1);
//        if (!in_array($currentLocale, config('translatable.locales'))
//            && !in_array($currentLocale, $noTranslationPrefixes)
//        ) {
//            app()->setLocale($currentLocale);
//        } else {
//            app()->setLocale($defaultLocale);
//        }

        return $next($request);
    }
}

?>
