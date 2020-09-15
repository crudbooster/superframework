<?php


if(!function_exists("__")) {
    /**
     * @param $text
     * @param string $lang
     * @return mixed|string
     * @throws Exception
     */
    function __($text, $lang = null) {
        $i18n = new i18n(base_path('app/Lang/lang_{LANGUAGE}.ini'), base_path('bootstrap/lang/'), config("fallback_lang"));
        $i18n->setForcedLang($lang ?: config('lang'));
        $i18n->init();
        return L($text);
    }
}