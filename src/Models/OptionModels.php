<?php

namespace Naran\Board\Models;

class OptionModels
{
    public function __construct()
    {
        add_action('init', [$this, 'registerSettings'], 100);
    }

    public function registerSettings()
    {
    }
}
