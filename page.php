<?php
/**
 * Static page template.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
unitourk_breadcrumb();
// Narrow, form-style pages read better fully centered (heading + content together).
$centered = ( function_exists( 'is_account_page' ) && is_account_page() ) || is_page( array( 'contact', 'track-order' ) );
?>
<main class="section<?php echo $centered ? ' ut-page-centered' : ''; ?>">
	<div class="container ut-page">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="section-head<?php echo $centered ? ' center' : ''; ?>"><h2><?php the_title(); ?></h2></div>
			<div class="ut-content"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
