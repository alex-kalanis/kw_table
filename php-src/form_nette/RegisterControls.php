<?php

namespace kalanis\kw_table\form_nette;


/**
 * Class RegisterControls
 * @package kalanis\kw_table\form_nette
 * Register additional controls into Nette
 */
class RegisterControls
{
    public static function register()
    {
        Controls\DateRange::register();
        Controls\Range::register();
    }
}
