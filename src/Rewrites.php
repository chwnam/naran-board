<?php


namespace Naran\Board;


use Naran\Board\Models\Objects\Board;
use Naran\Board\Models\Stores\BoardDataStore;

class Rewrites
{
    const PAGE_NAMES   = 'nrbrd_page_names';
    const VAR_BOARD_ID = 'nrbrd-id';

    public function __construct()
    {
        add_action('wp', [$this, 'wp']);
        add_action('parse_query', [$this, 'parseQuery']);
        add_filter('query_vars', [$this, 'queryVars']);
        add_filter('rewrite_rules_array', [$this, 'prependRewriteRule'], 9999);
    }

    public function prependRewriteRule($rules)
    {
        $store     = BoardDataStore::getInstance();
        $newRules  = [];
        $pageNames = [];

        /** @var Board $board */
        foreach ($store->getValues() as $board) {
            $object = get_post_type_object($board->getPostType());
            if (!$object) {
                continue;
            }

            $pageName = $board->getPageName();

            if ('archive_single' === $board->getBoardStyle()) {
                $archiveSlug = true === $object->has_archive ? $object->rewrite['slug'] : $object->has_archive;

                $newRules["{$archiveSlug}/p/(\d+)/?$"] = sprintf(
                    'index.php?post_type=%s&%s=$matches[1]',
                    $board->getPostType(),
                    self::VAR_BOARD_ID
                );
            } elseif ('shortcode' === $board->getBoardStyle() && $pageName) {
                $pageNames[$pageName] = 1;

                $newRules["^{$pageName}/p/([0-9]+)/?\$"] = sprintf(
                    'index.php?pagename=%s&%s=$matches[1]',
                    $pageName,
                    self::VAR_BOARD_ID
                );
            }
        }

        update_site_option(self::PAGE_NAMES, $pageNames);

        $rules = array_merge($newRules, $rules);

        return $rules;
    }

    public function queryVars($queryVars)
    {
        $queryVars[] = self::VAR_BOARD_ID;

        return $queryVars;
    }

    /**
     * @param \WP_Query $query
     */
    public function parseQuery(&$query)
    {
        if (!$query->is_main_query()) {
            return;
        }

        remove_action('parse_query', [$this, 'parseQuery']);

        $nrbrd_id = absint($query->get(self::VAR_BOARD_ID, '0'));
        if ($nrbrd_id && $query->is_post_type_archive) {
            $query->set('p', $nrbrd_id);
            $query->is_post_type_archive = false;
            $query->is_archive           = false;
            $query->is_single            = true;
            $query->is_singular          = true;
        }
    }


    public function wp()
    {
        if (
            is_singular('page') &&
            ($p = get_queried_object()) &&
            ($pageNames = get_site_option(self::PAGE_NAMES)) &&
            isset($pageNames[$p->post_name])
        ) {
            /**
             * 게시판이 초기화 액션.
             * 헤더가 전송되기 전에 먼저 호출되므로 여러 게시판의 사전 처리시 편리하게 쓸 수 있다.
             * 예를 들어 로그인 체크 같은 것들을 미리 처리할 수 있다.
             */
            do_action('nrbrd_board_init');
        }
    }
}