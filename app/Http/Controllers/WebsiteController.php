<?php

namespace App\Http\Controllers;

use App\Helpers\Plugins\CurrencyHelper;
use App\Helpers\Plugins\TranslateHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebsiteController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $languageCodes = array_keys(TranslateHelper::$languages);

        $request->validate([
            'language' => ['required',Rule::in($languageCodes)]
        ]);

        session()->put('user_language', $request->language);

        return redirect()->back();
    }

    public function changeCurrency(Request $request)
    {
        $currencies = CurrencyHelper::$currencies;

        $request->validate([
            'currency' => ['required',Rule::in($currencies)]
        ]);

        session()->put('user_currency', $request->currency);

        return redirect()->back();
    }
}
