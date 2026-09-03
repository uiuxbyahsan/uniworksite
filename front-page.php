<?php
/**
 * Homepage — built to the reference design spec.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$img  = get_template_directory_uri() . '/assets/img';
$shop = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );

$slides = array(
	array( 'slide1.webp', 'Unitourk workwear and uniforms, made to work and built to last' ),
	array( 'slide2.webp', 'Unitourk safety footwear, protection in every step' ),
	array( 'slide3.webp', 'Unitourk safety eyewear, clear vision and serious protection' ),
	array( 'slide4.webp', 'Unitourk work gloves, protection in your hands' ),
	array( 'slide5.webp', 'Unitourk safety protection and accessories, certified head-to-toe safety' ),
	array( 'slide6.webp', 'Unitourk military and tactical uniforms, engineered for performance' ),
	array( 'slide7.webp', 'Unitourk corporate and office uniforms, professional look and lasting impression' ),
);
$stars = str_repeat( '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>', 5 );
?>

<!-- HERO -->
<section class="hero" aria-label="Featured collections">
	<div class="hero-track" id="heroTrack">
		<?php foreach ( $slides as $i => $s ) :
			$ver = @filemtime( get_template_directory() . '/assets/img/hero/' . $s[0] ); ?>
			<article class="slide<?php echo 0 === $i ? ' active' : ''; ?>" data-slide>
				<img src="<?php echo esc_url( $img . '/hero/' . $s[0] . ( $ver ? '?v=' . $ver : '' ) ); ?>" alt="<?php echo esc_attr( $s[1] ); ?>"<?php echo 0 === $i ? '' : ' loading="lazy"'; ?>>
			</article>
		<?php endforeach; ?>
	</div>
	<div class="hero-nav"><div class="hero-dots" id="heroDots" role="tablist"></div></div>
</section>

<!-- SHOP BY CATEGORY -->
<section class="section" id="categories">
	<div class="container">
		<div class="section-head center reveal">
			<h2>Shop by Category</h2>
			<p>Specialised lines of certified protection, from head to toe.</p>
		</div>
		<div class="cat-grid">
			<?php foreach ( unitourk_homepage_categories() as $term ) :
				$slug = $term->slug;
				$link = get_term_link( $term );
				if ( is_wp_error( $link ) ) { $link = $shop; }
				$name = $term->name; // editable at Products → Categories ?>
				<a class="cat-card reveal" href="<?php echo esc_url( $link ); ?>">
					<div class="cat-media"><img src="<?php echo esc_url( unitourk_category_image( $slug ) ); ?>" alt="<?php echo esc_attr( $name ); ?>"></div>
					<span class="cat-badge"><?php echo unitourk_category_icon( $slug ); // phpcs:ignore ?></span>
					<div class="cat-foot">
						<b class="cat-name"><?php echo esc_html( $name ); ?></b>
						<span class="cat-btn">View Products
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>


<!-- ABOUT -->
<section class="section" id="about">
	<div class="container">
		<div class="about-grid">
			<div class="about-copy reveal">
				<h2>One partner for protection, from factory floor to field</h2>
				<p>As a manufacturing and trading group, Unitourk builds workwear and safety equipment around a single promise: <span class="hl">durability</span> that survives real conditions and <span class="hl">protection</span> you never have to second-guess. Every boot, garment and helmet is engineered to meet international <span class="hl">standards</span>, then priced and supplied for teams that order at scale.</p>
				<p>With offices in Sweden, Iran and Erbil, we combine European manufacturing discipline with responsive regional supply, so the gear you specify arrives certified, consistent and on time.</p>
				<div class="about-stats">
					<div class="stat"><b>3</b><span>Regional offices</span></div>
					<div class="stat"><b>500<em>+</em></b><span>Products in range</span></div>
					<div class="stat"><b>6</b><span>Certified categories</span></div>
				</div>
				<?php $ut_about_pg = get_page_by_path( 'about-us' ); if ( $ut_about_pg ) : ?>
					<a href="<?php echo esc_url( get_permalink( $ut_about_pg ) ); ?>" class="btn btn-ghost ut-about-more">
						Learn more about us
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
					</a>
				<?php endif; ?>
			</div>
			<div class="about-visual reveal">
				<div class="badge-float"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5z"/><path d="M9 12l2 2 4-4"/></svg><b>Certified<br>Manufacturing</b></div>
				<figure class="about-photo"><img src="<?php echo esc_url( $img . '/about/about-home.webp' ); ?>" alt="Unitourk team reviewing safety products with partners"></figure>
			</div>
		</div>
	</div>
</section>

<!--
	TESTIMONIALS — SAMPLE / DEMO CONTENT ONLY.
	The names, companies and quotes below are FICTIONAL, created for a design-review
	draft at the client's request. They are NOT real endorsements. Replace with genuine,
	permissioned client testimonials (or remove this section) before the site goes live
	to real visitors — publishing fabricated testimonials as genuine is deceptive advertising.
-->
<section class="section alt" id="testimonials">
	<div class="container">
		<div class="section-head center reveal">
			<h2>What our partners say</h2>
			<p>Trusted by industrial teams across construction, security, energy and emergency services.</p>
		</div>
		<div class="tst-slider reveal">
			<div class="tst-viewport">
				<div class="tst-track" id="tstTrack">
					<?php
					// SAMPLE data — fictional, for demo only. Swap for real testimonials before launch.
					$samples = array(
						array( 'Unitourk kitted out our entire site crew with certified boots and hi-vis in under two weeks. The gear has held up through a hard winter.', 'James Whitfield', 'Northbridge Construction', 'JW' ),
						array( 'Consistent quality and a team that actually answers the phone. Their tactical range is now standard issue for our field units.', 'Dana Alkhoury', 'Meridian Security Group', 'DA' ),
						array( 'We moved our whole PPE supply to Unitourk last year — certified, well priced at volume, and always on time.', 'Erik Lindqvist', 'Baltic Industrial Services', 'EL' ),
						array( 'From respirators to firefighting kit, everything arrives to spec. They understand what compliance means on the ground.', 'Sara Njoroge', 'Rift Valley Energy', 'SN' ),
					);
					foreach ( $samples as $t ) : ?>
						<article class="tst-card">
							<svg class="quote-ico" viewBox="0 0 24 24" fill="currentColor"><path d="M10 7H6a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2H5v2h1a4 4 0 0 0 4-4V7zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2h-1v2h1a4 4 0 0 0 4-4V7z"/></svg>
							<div class="stars" aria-hidden="true"><?php echo $stars; // phpcs:ignore ?></div>
							<p><?php echo esc_html( $t[0] ); ?></p>
							<div class="tst-foot">
								<div class="tst-avatar"><?php echo esc_html( $t[3] ); ?></div>
								<div><b><?php echo esc_html( $t[1] ); ?></b><span><?php echo esc_html( $t[2] ); ?></span></div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="tst-dots" id="tstDots" role="tablist"></div>
		</div>
	</div>
</section>

<?php
get_footer();
