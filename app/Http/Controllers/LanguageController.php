<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLang(Request $request, $lang)
    {
        // Store the URL on which the user was
        $previous_url = url()->previous();

        // Transform it into a correct request instance
        $previous_request = app('request')->create($previous_url);

        // In case the route name was translated
        $route_name = app('router')->getRoutes()->match($previous_request)->getName();

        // Store the segments of the last request as an array
        $segments = $previous_request->segments();

        // Check if the first segment matches a language code
        if (array_key_exists($lang, config('languages'))) {

            // If it was indeed a translated route name
            if ($route_name) {

                // Translate the route name to get the correct URI in the required language, and redirect to that URL.
                return redirect()->to($lang . '/' .  trans('routes.' . $route_name, [], 'messages', $lang));
            }

            // Replace the first segment by the new language code
            $segments[0] = $lang;

            // Redirect to the required URL
            return redirect()->to(implode('/', $segments));
        }

        return redirect()->back();
    }

}
