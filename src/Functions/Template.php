<?php

namespace Naran\Board\Functions;


function template( $template_name, $context = [], $echo = true ) {
    $template = trim( $template_name, '/' );

    $paths = [
        STYLESHEETPATH . '/naran/board/' . $template,
        TEMPLATEPATH . '/naran/board/' . $template,
        dirname( NRBRD_MAIN ) . '/templates/' . $template,
    ];

    $found = false;

    foreach ( $paths as $path ) {
        if ( file_exists( $path ) && is_readable( $path ) ) {
            $found = $path;
            break;
        }
    }

    return renderFile($found, $context, $echo );
}


function renderFile( $path, $context = [], $echo = true ) {
    if ( ! $echo ) {
        ob_start();
    }

    $__nrbrd_path__ = $path;
    unset( $path );

    if ( is_array( $context ) && ! empty( $context ) ) {
        foreach ( $context as $__nrbrd_ctx_name__ => $__nrbrd_ctx_value__ ) {
            if ( ! isset( $$__nrbrd_ctx_name__ ) ) {
                ${$__nrbrd_ctx_name__} = $__nrbrd_ctx_value__;
            }
        }
        unset( $__nrbrd_ctx_name__, $__nrbrd_ctx_value__ );
    }

    unset( $context );

    if ( $__nrbrd_path__ ) {
        /** @noinspection PhpIncludeInspection */
        include $__nrbrd_path__;
    }

    if ( ! $echo ) {
        return ob_get_clean();
    }

    return '';
}


function timeTag( $timestamp, $useHumanTimeDiff = true, $echo = true ) {
    $html = '';

    if ( $timestamp ) {
        $datetime_text = wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $timestamp );

        $html = sprintf(
            '<time class="%s" datetime="%s" title="%s">%s%s</time>',
            $useHumanTimeDiff ? 'human-time-diff' : '',
            esc_attr( wp_date( 'c', $timestamp ) ),
            esc_attr( $datetime_text ),
            $useHumanTimeDiff ? human_time_diff( $timestamp ) : $datetime_text,
            $useHumanTimeDiff ? ' 전' : ''
        );
    }

    if ( $echo ) {
        echo $html;
        return '';
    }

    return $html;
}
