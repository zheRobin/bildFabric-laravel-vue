<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
class LocalizationController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $locale = $request->input('locale');

        LaravelLocalization::getLocalizedURL($locale, null, [], true);

        // Застосовуємо вибрану мову
        app()->setLocale($locale);

        // Додаткові дії, які вам потрібні

        return response()->json(['locale' => $locale]);
    }
}