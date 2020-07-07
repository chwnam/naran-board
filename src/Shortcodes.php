<?php

namespace Naran\Board;

use Naran\Board\Views\BoardView;

class Shortcodes
{
    public function __construct()
    {
        add_shortcode('nrbrd_board', [$this, 'renderBoard']);
    }

    public function renderBoard($atts)
    {
        $defaultAtts = [
            'post_type'     => '',
            'skin'          => 'prototype',
            'allowed_stati' => ['publish'],
            'board_id'      => get_query_var(Rewrites::VAR_BOARD_ID, 0),
        ];

        $atts = wp_array_slice_assoc(shortcode_atts($defaultAtts, $atts), array_keys($defaultAtts));
        $atts = apply_filters('nrbrd_board_atts', $atts);

        if (!post_type_exists($atts['post_type'])) {
            return '존재하지 않는 포스트 타입을 지정하였습니다.';
        }

        if (empty($atts['skin'])) {
            $atts['skin'] = $defaultAtts['skin'];
        }

        if (empty($atts['allowed_stati'])) {
            $atts['allowed_stati'] = $defaultAtts['allowed_stati'];
        }

        $view = new BoardView($atts);

        return $view->render();
    }
}
