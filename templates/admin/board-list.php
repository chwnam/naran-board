<?php
/**
 * @var Naran\Board\Models\Objects\Board[] $items
 * @var string                             $new_link
 * @var callable                           $get_edit_link
 * @var callable                           $get_delete_link
 */

?>
<div class="wrap">
    <h1 class="wp-heading-inline">
        게시판 목록
    </h1>

    <a href="<?php echo esc_url($new_link); ?>" class="page-title-action">
        새로 추가
    </a>

    <hr class="wp-header-end">

    <div class="subsubsub">
    </div>

    <table id="nrbrd-board-list" class="wp-list-table fixed striped widefat">
        <thead>
        <tr>
            <th class="manage-column">포스트 타입</th>
            <th class="manage-column">게시판 구성 형식</th>
            <th class="manage-column">생성 시간</th>
            <th class="manage-column">최종 수정</th>
            <th class="manage-column">동작</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($items)) : ?>
            <?php foreach ($items as $item): ?>
                <?php if (($type = get_post_type_object($item->getPostType()))) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url(add_query_arg('board', $type->name)); ?>">
                                <?php echo esc_html($type->label); ?>
                            </a>
                        </td>
                        <td>
                            <?php if ('archive_single' === $item->getBoardStyle() ?? '') : ?>
                                <span class="board-style archive-single">아카이브/싱글</span>
                                [<a href="<?php echo esc_url(get_post_type_archive_link($item->getPostType())); ?>"
                                    target="_blank">방문</a>]
                            <?php elseif ('shortcode' === $item->getBoardStyle()) : ?>
                                <span class="board-style shortcode">숏코드</span>
                                [<a href="<?php echo esc_url(get_permalink(get_page_by_path($item->getPageName()))); ?>"
                                    target="_blank">방문</a>]
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php \Naran\Board\Functions\timeTag($item->getCreated()); ?>
                        </td>
                        <td>
                            <?php \Naran\Board\Functions\timeTag($item->getModified()); ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url($get_edit_link($type->name)); ?>" role="button">수정</a> |
                            <a href="<?php echo esc_url($get_delete_link($type->name)); ?>"
                               class="delete-board-link confirm-required"
                               data-confirm-message="이 게시판을 삭제할까요? (게시판 설정값만 삭제하며, 저장된 게시물들은 보존됩니다.)"
                               role="button">삭제</a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="5">게시판으로 사용하는 커스텀 포스트가 없습니다.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
