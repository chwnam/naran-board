<?php

namespace Naran\Board;

use Naran\Board\Models\Objects\Script;
use Naran\Board\Models\Objects\Style;

class Scripts
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'adminScripts'], 9);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'frontScripts'], 9);
        }
    }

    public function adminScripts()
    {
        $this->register($this->getCommonScripts());
        $this->register($this->getAdminScripts());
    }

    public function frontScripts()
    {
        $this->register($this->getCommonScripts());
        $this->register($this->getFrontScripts());
    }

    private function register($scripts)
    {
        foreach ($scripts['js'] as $script) {
            wp_register_script(
                $script->handle,
                $script->src,
                $script->deps,
                $script->ver,
                $script->inFooter
            );
        }

        foreach ($scripts['css'] as $style) {
            wp_register_style(
                $style->handle,
                $style->src,
                $style->deps,
                $style->ver,
                $style->media
            );
        }
    }

    private function getCommonScripts()
    {
        return [
            'js'  => [],
            'css' => [],
        ];
    }

    private function getAdminScripts()
    {
        return [
            'js'  => [
                new Script('nrbrd-admin', $this->urlHelper('admin.js')),
            ],
            'css' => [
                new Style('nrbrd-admin', $this->urlHelper('admin.css'))
            ],
        ];
    }

    private function getFrontScripts()
    {
        return [
            'js'  => [],
            'css' => [],
        ];
    }

    private function urlHelper($relpath)
    {
        $relpath = trim($relpath);
        $ext     = pathinfo($relpath, PATHINFO_EXTENSION);

        return plugins_url("assets/{$ext}/{$relpath}", NRBRD_MAIN);
    }
}
