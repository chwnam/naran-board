<?php
/**
 * 게시판 스킨 'prototype'의 목록 템플릿.
 *
 * 그저 고전적인 테이블 코딩.
 *
 * @var WP_Query $query
 * @var string   $no_items
 * @var array    $items
 *
 * @see NRBRD_Shortcode_Board::list_page()
 */

?>

<table>
    <thead>
    <tr>
        <!-- 번호는 ID 와 무관하게 현재 읽고 있는 게시판의 글 순번입니다. 숫자를 통해 대략적으로 사용자가 목록 어디쯤 있다는 감각을 줄 수 있습니다. -->
        <th>번호</th>
        <th>제목</th>
        <th>작성자</th>
        <th>작성 시각</th>
        <th>조회수</th>
    </tr>
    </thead>
    <tbody>

	<?php if ( ! empty( $items ) ): ?>
		<?php foreach ( $items as $item ) : ?>
            <tr>
                <td>
					<?php echo esc_html( $item['number'] ?? '' ); ?>
                </td>
                <td>
                    <a href="<?php echo esc_url( $item['board_link'] ?? '#' ); ?>">
						<?php echo esc_html( $item['post_title'] ?? '' ); ?>
                    </a>
                </td>
                <td>
					<?php echo esc_html( $item['author_name'] ?? '' ); ?>
                </td>
                <td>
					<?php
					echo isset( $item['post_date'] ) ?
						nrbrd_time_tag( $item['post_date']->getTimestamp(), true, false ) : '';
					?>
                </td>
                <td>4</td>
            </tr>
		<?php endforeach; ?>
	<?php else : ?>
        <tr>
            <td colspan="4"><?php echo esc_html( $no_items ); ?></td>
        </tr>
	<?php endif; ?>

    </tbody>
    <tfoot>
    <tr>
        <!-- 번호는 ID 와 무관하게 현재 읽고 있는 게시판의 글 순번입니다. 숫자를 통해 대략적으로 사용자가 목록 어디쯤 있다는 감각을 줄 수 있습니다. -->
        <th>번호</th>
        <th>제목</th>
        <th>작성자</th>
        <th>작성 시각</th>
        <th>조회수</th>
    </tr>
    </tfoot>
</table>
