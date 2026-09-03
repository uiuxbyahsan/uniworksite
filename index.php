<?php
/**
 * Fallback template.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="section-head"><h2><?php echo is_home() ? 'Latest' : esc_html( get_the_archive_title() ); ?></h2></div>
			<div class="post-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'post-item' ); ?>>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="post-excerpt"><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="ut-pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '‹', 'next_text' => '›' ) ); ?></div>
		<?php else : ?>
			<div class="section-head"><h2>Nothing found</h2><p>Try the catalog or the homepage.</p></div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
