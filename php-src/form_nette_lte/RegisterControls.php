<?php

namespace kalanis\kw_table\form_nette_lte;


/**
 * Class RegisterControls
 * @package kalanis\kw_table\form_nette_lte
 * Register additional controls into Nette
 * Parts from AdminLTE
 */
class RegisterControls
{
    public static function register()
    {
        Controls\DateTimeRange::register();
        Controls\DateTimeRangeButton::register();
    }
}
