<?php

namespace Naran\Board\Admin;

use Naran\Board\Models\Objects\Board;
use Naran\Board\Models\Stores\BoardDataStore;

use function Naran\Board\Functions\template;

class BoardsAdmin
{
    const PAGE_SLUG = 'nrbrd-boards-admin';

    private $pageHook = '';

    public function __construct()
    {
        add_action('nrbrd_admin_menu', [$this, 'adminMenu'], 1);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function adminMenu()
    {
        if (static::isNew()) {
            $pageTitle = '나란 보드 새 보드 추가';
            $menuTitle = '새 보드';
        } elseif (static::isEdit()) {
            $pageTitle = '나란 보드 목록 편집';
            $menuTitle = '보드 편집';
        } else {
            $pageTitle = '나란 보드 목록 관리';
            $menuTitle = '보드 목록';
        }

        $this->pageHook = add_submenu_page(
            'nrbrd',
            $pageTitle,
            $menuTitle,
            'manage_options',
            'nrbrd-boards-admin',
            [$this, 'outputAdminMenu'],
            1
        );
    }

    public function outputAdminMenu()
    {
        if ($this->isList()) {
            $this->outputList();
        } elseif ($this->isEdit()) {
            $this->outputEdit($this->getPostType());
        } elseif ($this->isNew()) {
            $this->outputEdit(null);
        }
    }

    public function enqueueScripts($hook)
    {
        if ($this->pageHook === $hook) {
            wp_enqueue_style('nrbrd-admin');
            wp_enqueue_script('nrbrd-admin');
        }
    }

    private function outputEdit($postType)
    {
        $instance  = BoardDataStore::getInstance();
        $board     = $instance->get($postType);
        $postTypes = [];

        if (!$board) {
            $board = Board::getDefault();
            foreach ($GLOBALS['wp_post_types'] as $type) {
                if (!$type->_builtin && !$instance->get($type->name)) {
                    $postTypes[] = $type;
                }
            }
        }

        template(
            'admin/board-edit.php',
            [
                'is_new'      => $this->isNew(),
                'board'       => $board,
                'post_types'  => &$postTypes,
                'list_link'   => static::getBoardsListLink(),
                'new_link'    => static::getNewBoardLink(),
                'delete_link' => static::getDeleteBoardLink($postType),
            ]
        );
    }

    private function outputList()
    {
        template(
            'admin/board-list.php',
            [
                'items'           => BoardDataStore::getInstance()->getValues(),
                'new_link'        => static::getNewBoardLink(),
                'get_edit_link'   => [static::class, 'getEditBoardLink'],
                'get_delete_link' => [static::class, 'getDeleteBoardLink'],
            ]
        );
    }

    public static function isList()
    {
        return self::PAGE_SLUG === ($_GET['page'] ?? '') && empty($_GET['new']) && empty($_GET['board']);
    }

    public static function isNew()
    {
        return self::PAGE_SLUG === ($_GET['page'] ?? '') && '1' === ($_GET['new'] ?? '');
    }

    public static function isEdit()
    {
        return self::PAGE_SLUG === ($_GET['page'] ?? '') && static::getPostType();
    }

    public static function getPostType()
    {
        return $_GET['board'] ?? '';
    }

    public static function getBoardsListLink()
    {
        return admin_url('admin.php') . '?page=' . static::PAGE_SLUG;
    }

    public static function getNewBoardLink()
    {
        return add_query_arg(
            [
                'page' => static::PAGE_SLUG,
                'new'  => '1',
            ],
            admin_url('admin.php')
        );
    }

    public static function getEditBoardLink($board)
    {
        return add_query_arg(
            [
                'page'  => static::PAGE_SLUG,
                'board' => $board,
            ],
            admin_url('admin.php')
        );
    }

    public static function getDeleteBoardLink($board)
    {
        return add_query_arg(
            [
                'action' => 'nrbrd_delete_board',
                'board'  => $board,
                'nonce'  => wp_create_nonce(static::PAGE_SLUG . $board),
            ],
            admin_url('admin-post.php')
        );
    }
}
