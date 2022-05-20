<?php

namespace kalanis\kw_table\form_nette_bootstrap;


/**
 * Class RegisterControls
 * @package kalanis\kw_table\form_nette_bootstrap
 * Register additional controls into Nette
 * These controls uses Bootstrap for styles
 */
class RegisterControls
{
    public static function register()
    {
        Controls\BootstrapDateRange::register();
        Controls\BootstrapRange::register();
    }
}
