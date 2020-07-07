<?php

namespace Naran\Board\Views;

use Naran\Board\Rewrites;

use function Naran\Board\Functions\renderFile;

class BoardView
{
    protected $attrs = [];

    public function __construct($attrs)
    {
        $this->attrs = wp_parse_args($attrs, self::getDefaultAttrs());
    }

    public function render()
    {
        $method = null;

        if ($this->isListPage()) {
            $method = apply_filters('nrbrd:renderListPageMethod', [$this, 'renderListPage'], $this->attrs);
        } elseif ($this->isSinglePage()) {
            $method = apply_filters('nrbrd:renderSinglePageMethod', [$this, 'renderSinglePage'], $this->attrs);
        }

        if (is_callable($method)) {
            return call_user_func($method, $this->attrs);
        }

        return false;
    }

    protected function renderListPage()
    {
        $skin = plugin_dir_path(NRBRD_MAIN) . "templates/skins/{$this->getBoardSkin()}/board-list.php";
        $skin = apply_filters('nrbrd:listPageSkin', $skin, $this->attrs);

        if (!file_exists($skin) || !is_readable($skin)) {
            return '리스트 스킨 파일이 존재하지 않습니다.';
        }

        $context = [
            'query'    => new \WP_Query(),
            'no_items' => '게시물이 존재하지 않습니다.',
            'items'    => [],
        ];

        $queryArgs = [
            'post_type'   => $this->getPostType(),
            'post_status' => $this->getAllowedStati(),
            'orderby'     => 'date',
            'order'       => 'DESC',
        ];

        $query = &$context['query'];
        $query->query(apply_filters('nrbrd:listPageQuery', $queryArgs, $this->attrs));

        $total        = intval($query->found_posts);
        $postsPerPage = intval($query->get('posts_per_page'));
        $paged        = intval($query->get('paged'));
        $url          = untrailingslashit($_SERVER['REQUEST_URI']);

        if ($postsPerPage > 0 && $paged > 0) {
            $number = $total - $postsPerPage * ($paged - 1);
        } else {
            $number = $total;
        }

        while ($query->have_posts()) {
            $query->the_post();

            $item = [
                'number'        => $number--,
                'post_id'       => get_the_ID(),
                'post_title'    => get_the_title(),
                'post_content'  => get_the_content(),
                'exceprt'       => get_the_excerpt(),
                'board_link'    => $url . '/p/' . get_the_ID(),
                'permalink'     => get_the_permalink(),
                'edit_link'     => get_edit_post_link(0, 'raw'),
                'author_id'     => get_the_author_meta('ID'),
                'author_name'   => get_the_author_meta('display_name'),
                'post_date'     => get_post_datetime(null, 'date'),
                'modified_date' => get_post_datetime(null, 'modified'),
            ];

            // TODO: process item

            $context['items'][] = apply_filters('nrbrd:listPageItem', $item, $query, $this->attrs);
        }

        $query->reset_postdata();
        wp_reset_postdata();

        ob_start();

        do_action('nrbrd:listPageBeforeRender', $context, $this->attrs);

        renderFile($skin, apply_filters('nrbrd:listPageContext', $context, $this->attrs));

        do_action('nrbrd:listPageAfterRender', $context, $this->attrs);

        return apply_filters('nrbrd:listPageOutput', ob_get_clean(), $context, $this->attrs);
    }

    protected function renderSinglePage()
    {
        $skin = plugin_dir_path(NRBRD_MAIN) . "templates/skins/{$this->getBoardSkin()}/board-single.php";
        $skin = apply_filters('nrbrd:singlePageSkin', $skin, $this->attrs);

        if (!file_exists($skin) || !is_readable($skin)) {
            return '싱글 스킨 파일이 존재하지 않습니다.';
        }

        $context = [
            'query' => new \WP_Query(),
            'item'  => null,
        ];

        $queryArgs = [
            'p'              => $this->getBoardId(),
            'post_type'      => $this->getPostType(),
            'post_status'    => $this->getAllowedStati(),
            'posts_per_page' => 1,
            'paged'          => 1,
        ];

        $query = &$context['query'];
        $query->query(apply_filters('nrbrd:singlePageQuery', $queryArgs, $this->attrs));

        if (!apply_filters('nrbrd:singlePageAvailable', $query->have_posts(), $this->attrs)) {
            // TODO: 404 skin
            return '';
        }

        $query->the_post();

        $item = [
            'number'        => '',
            'post_id'       => get_the_ID(),
            'post_title'    => get_the_title(),
            'post_content'  => get_the_content(),
            'exceprt'       => get_the_excerpt(),
            'board_link'    => untrailingslashit($_SERVER['REQUEST_URI']) . '/p/' . get_the_ID(),
            'permalink'     => get_the_permalink(),
            'edit_link'     => get_edit_post_link(0, 'raw'),
            'author_id'     => get_the_author_meta('ID'),
            'author_name'   => get_the_author_meta('display_name'),
            'post_date'     => get_post_datetime(null, 'date'),
            'modified_date' => get_post_datetime(null, 'modified'),
            // TODO: back to the list link.
        ];

        $context['item'] = apply_filters('nrbrd:singlePageItem', $item, $query, $this->attrs);

        $query->reset_postdata();
        wp_reset_postdata();

        do_action('nrbrd:singlePageBeforeRender', $context, $this->attrs);

        renderFile($skin, apply_filters('nrbrd:singlePageContext', $context, $this->attrs));

        do_action('nrbrd:singlePageAfterRender', $context, $this->attrs);

        return apply_filters('nrbrd:singlePageOutput', ob_get_clean(), $context, $this->attrs);
    }

    public function isListPage()
    {
        return empty($this->getBoardId());
    }

    public function isSinglePage()
    {
        return $this->getBoardId() > 0;
    }

    public function getPostType()
    {
        return $this->attrs['post_type'] ?? '';
    }

    public function getBoardSkin()
    {
        return $this->attrs['skin'] ?? '';
    }

    public function getAllowedStati()
    {
        return $this->attrs['allowed_stati'] ?? [];
    }

    public function getBoardId()
    {
        return absint($this->attrs['board_id'] ?? '0');
    }

    public static function getDefaultAttrs()
    {
        return [
            'post_type'     => '',
            'skin'          => 'prototype',
            'allowed_stati' => ['publish'],
            'board_id'      => get_query_var(Rewrites::VAR_BOARD_ID, 0),
        ];
    }
}
