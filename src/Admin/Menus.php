<?php


namespace Naran\Board\Admin;


class Menus
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'adminMenu']);
    }

    public function adminMenu()
    {
        add_menu_page(
            '나란 보드',
            '나란 보드',
            'manage_options',
            'nrbrd',
            '__return_empty_string'
        );

        do_action('nrbrd_admin_menu');

        remove_submenu_page('nrbrd', 'nrbrd');
    }
}
