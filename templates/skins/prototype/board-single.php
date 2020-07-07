<?php
/**
 * 게시판 스킨 'prototype'의 싱글 템플릿.
 *
 *
 * @var WP_Query $query
 * @var array    $item
 *
 * @see NRBRD_Shortcode_Board::single_page()
 */

?>

<div>
	<?php echo $item['post_content'] ?? ''; ?>
</div>