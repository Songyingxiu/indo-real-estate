<?php namespace App\Controllers;

class Language extends BaseController
{
    public function switch($locale)
    {
        $session = session();
        $supportedLocales = config('App')->supportedLocales;
        
        if (in_array($locale, $supportedLocales)) {
            $session->set('locale', $locale);
        }

        return redirect()->back();
    }
}