<?php
/**
 * @var bool                             $is_new
 * @var Naran\Board\Models\Objects\Board $board
 * @var WP_Post_Type[]                   $post_types
 * @var string                           $new_link
 * @var string                           $list_link
 * @var string                           $delete_link
 */

?>
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $is_new ? '새 보드 추가' : '보드 편집'; ?>
    </h1>

    <?php if (!$is_new) : ?>
        <a href="<?php echo esc_url($new_link); ?>" class="page-title-action">새로 추가</a>
    <?php endif; ?>

    <hr class="wp-header-end">

    <form id="board-edit"
          name="edit_bord"
          action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
          method="post">

        <input type="hidden" name="enabled" value="yes">

        <table id="" class="form-table">
            <tbody>
            <tr>
                <th scope="row" class="required">
                    <label for="post_type">포스트 타입</label>
                </th>
                <td>
                    <?php if ($is_new) : ?>

                        <?php if (!empty($post_types)) : ?>
                            <select id="post_type" name="post_type" required="required">
                                <?php foreach ($post_types as $pt) : ?>
                                    <option value="<?php echo esc_attr($pt->name); ?>">
                                        <?php echo esc_html($pt->label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p>아직 보드로 사용할 커스텀 포스트 타입이 만들어지지 않았네요.</p>
                        <?php endif; ?>

                    <?php else : ?>

                        <?php $type = get_post_type_object($board->getPostType()); ?>
                        <?php if ($type) : ?>
                            <span><?php echo esc_html($type->label); ?></span>
                            <input type="hidden" name="post_type" value="<?php echo esc_attr($type->name); ?>">
                        <?php endif; ?>

                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th scope="row" class="required">
                    게시판 구성 형식
                </th>
                <td>
                    <ul class="nrbrd-m0">
                        <li>
                            <input id="board_style-archive_single"
                                   name="board_style"
                                   value="archive_single"
                                   type="radio"
                                   required="required"
                                <?php checked('archive_single', $board->getBoardStyle()); ?>>
                            <label for="board_style-archive_single">아카이브/싱글</label>
                        </li>
                        <li>
                            <input id="board_style-shortcode"
                                   name="board_style"
                                   value="shortcode"
                                   type="radio"
                                   required="required"
                                <?php checked('shortcode', $board->getBoardStyle()); ?>>
                            <label for="board_style-shortcode">숏코드</label>
                        </li>
                    </ul>
                    <p class="description">
                        게시판을 구성할 방법을 선택합니다. 옵션 중 하나를 선택해야 합니다.<br>

                        - <strong>아카이브/싱글</strong>:
                        테마의 아카이브 템플릿, 싱글 템플릿을 그대로 사용합니다.
                        테마 루프를 그대로 사용하므로 원하는대로 자유롭게 게시판을 디자인합니다.<br>

                        - <strong>숏코드</strong>:
                        페이지에 숏코드 <em>'nrbrd_board'</em>를 삽입합니다.
                        기존에 제작된 페이지 헤더, 푸터, 사이드바 같은 요소와 같이 배치할 때 적합합니다.
                    </p>
                </td>
            </tr>
            <tr id="board_page">
                <th scope="row">숏코드 페이지</th>
                <td>
                    <?php
                    wp_dropdown_pages(
                        [
                            'id'               => 'page_id',
                            'name'             => 'page_id',
                            'show_option_none' => '-- 페이지 선택 --',
                            'selected'         => $board->getPageId(),
                        ]
                    );
                    ?>
                    <br>
                    <span class="description">숏코드 방식의 게시판을 사용하려면 다음 절차를 진행해야 합니다.<br>
                        1. 게시판을 사용하려는 페이지를 위 드롭다운에서 선택.<br>
                        2. 해당 페이지 본문에 아래 숏코드를 삽입.</span>
                    <pre><code>[nrbrd_board post_type="<?php echo sanitize_key($board->getPostType()); ?>"]</code></pre>
                </td>
            </tr>
            <?php if (!$is_new) : ?>
                <tr>
                    <th scope="row">
                        생성 시각
                    </th>
                    <td>
                        <?php \Naran\Board\Functions\timeTag($board->getCreated(), false); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">최종 수정 시각</th>
                    <td>
                        <?php \Naran\Board\Functions\timeTag($board->getModified(), false); ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <input type="hidden" name="action" value="nrbrd_edit_board">

        <?php if (!$is_new && $delete_link) : ?>
            <p class="remove">
                <a href="<?php echo esc_url($delete_link); ?>"
                   class="delete-board-link confirm-required"
                   data-confirm-message="이 게시판을 삭제할까요? (게시판 설정값만 삭제하며, 저장된 게시물들은 보존됩니다.)"
                >이 게시판을 삭제</a>
            </p>
        <?php endif; ?>

        <p class="submit">
            <?php wp_nonce_field('nrbrd', 'nonce'); ?>
            <button type="submit" class="button button-primary">저장</button>
            <a href="<?php echo esc_url($list_link); ?>" role="button" class="button button-secondary">목록으로</a>
        </p>
    </form>
</div>

<script>
    /* global jQuery */
    jQuery(function ($) {
        var pagename = $('#pagename'),
            boardStyle = $('[name="board_style"]');

        boardStyle.on('change', function (e) {
            if ('shortcode' === boardStyle.filter(':checked').val()) {
                pagename.attr('required', 'required');
            } else {
                pagename.removeAttr('required');
            }
        }).trigger('change');
    });
</script>