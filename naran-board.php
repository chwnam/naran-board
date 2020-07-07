<?php

/**
 * Plugin Name:       Naran Board
 * Description:       Tiny bulletin board system for web developers.
 * Plugin URI:        https://github.com/chwnam/naran-board
 * Version:           1.0.0
 * Author:            Changwoo
 * Author URI:        cs.chwnam@gmail.com
 * Requires PHP:      7.2
 * Requires at least: 5.4.1
 * License:           GPLv2 or later
 */

define('NRBRD_MAIN', __FILE__);
define('NRBRD_VERSION', '1.0.0');

require_once __DIR__ . '/vendor/autoload.php';

final class NaranBoard implements ArrayAccess
{
    use \Naran\Board\Traits\Singleton;

    private $modules = [];

    private function __construct()
    {
        $this->modules = [
            'admin/boards-admin'   => new \Naran\Board\Admin\BoardsAdmin(),
            'admin/ajax-responses' => new \Naran\Board\Admin\AjaxResponses(),
            'admin/menus'          => new \Naran\Board\Admin\Menus(),
            'admin/post-responses' => new \Naran\Board\Admin\PostResponses(),

            'models/option-models' => new \Naran\Board\Models\OptionModels(),

            'rewrites'   => new \Naran\Board\Rewrites(),
            'scripts'    => new \Naran\Board\Scripts(),
            'shortcodes' => new \Naran\Board\Shortcodes(),
        ];

        // $this->modules['shortcode-board'] = new NRBRD_Shortcode_Board();
    }

    public function offsetExists($offset)
    {
        return isset($this->modules[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->modules[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('읽기 전용입니다.');
    }

    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('읽기 전용입니다.');
    }
}

NaranBoard::getInstance();
