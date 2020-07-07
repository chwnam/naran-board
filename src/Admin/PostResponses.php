<?php

namespace Naran\Board\Admin;

use Naran\Board\Models\Objects\Board;
use Naran\Board\Models\Stores\BoardDataStore;

class PostResponses
{
    public function __construct()
    {
        add_action('admin_post_nrbrd_delete_board', [$this, 'deleteBoard']);
        add_action('admin_post_nrbrd_edit_board', [$this, 'editBoard']);
    }

    public function deleteBoard()
    {
        $board = wp_unslash($_REQUEST['board'] ?? '');

        if (!post_type_exists($board)) {
            wp_die(sprintf('에러! 존재하지 않는 게시판 \'%s\'.', $board));
        }

        check_admin_referer(BoardsAdmin::PAGE_SLUG . $board, 'nonce');

        $instance = BoardDataStore::getInstance();
        $instance->delete($board);

        if (isset($_REQUEST['redirect_to'])) {
            $redirectTo = esc_url_raw(wp_unslash($_REQUEST['redirect_to']));
        } else {
            $redirectTo = BoardsAdmin::getBoardsListLink();
        }

        wp_safe_redirect($redirectTo);
        exit;
    }

    public function editBoard()
    {
        check_admin_referer('nrbrd', 'nonce');

        $store   = BoardDataStore::getInstance();
        $newData = Board::fromArray($_REQUEST);
        $oldData = $store->get($newData->getPostType());
        $error   = new \WP_Error();

        if (!$newData->getPostType()) {
            $error->add('error', '잘못된 포스트 타입을 선택했습니다.');
        }

        if (!$newData->getBoardStyle()) {
            $error->add('error', '잘못된 보드 스타일을 선택했습니다.');
        } elseif (
            'shortcode' === $newData->getBoardStyle() &&
            !(($post = get_post($newData->getPageId())) && 'page' === $post->post_type)
        ) {
            $error->add('error', '정확한 페이지를 선택해야 합니다.');
        }

        if ($error->has_errors()) {
            wp_die($error, '보드 편집 에러', ['back_link' => true]);
        };

        $newData->setPageName(isset($post) ? $post->post_name : '');
        $newData->setCreated($oldData ? $oldData->getCreated() : time());
        $newData->setModified(time());

        $store->set($newData->getPostType(), $newData);

        if (isset($_REQUEST['redirect_to'])) {
            $redirectTo = esc_url_raw(wp_unslash($_REQUEST['redirect_to']));
        } else {
            $redirectTo = BoardsAdmin::getEditBoardLink($newData->getPostType());
        }

        wp_safe_redirect($redirectTo);
        exit;
    }
}
