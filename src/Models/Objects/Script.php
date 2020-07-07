<?php

namespace Naran\Board\Models\Objects;

class Script
{
    public $handle;

    public $src;

    public $deps;

    public $ver;

    public $inFooter;

    /**
     * Script constructor.
     *
     * @param string $handle
     * @param string $src
     * @param string|array $deps
     * @param false|null $ver
     * @param bool $in_footer
     */
    public function __construct($handle, $src, $deps = [], $ver = false, $in_footer = false)
    {
        $this->handle   = $handle;
        $this->src      = $src;
        $this->deps     = (array)$deps;
        $this->ver      = $ver;
        $this->inFooter = $in_footer;
    }
}
