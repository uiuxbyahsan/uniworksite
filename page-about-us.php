<?php
/**
 * Template Name: About Us
 * Section 1 hero intro · Section 2 value strip · Section 3 "What We Do" image grid · closing block.
 * Copy is intentionally em-dash free.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$img          = get_template_directory_uri() . '/assets/img/about';
$contact_page = get_page_by_path( 'contact' );
$contact_url  = $contact_page ? get_permalink( $contact_page ) : home_url( '/#footer' );
$arrow        = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
?>
<main class="ut-about">

	<!-- Section 1 — Hero intro -->
	<section class="ut-abt-hero">
		<div class="container">
			<div class="ut-abt-hero-grid">
				<div class="ut-abt-hero-copy reveal">
					<span class="ut-abt-eyebrow">About Us</span>
					<h1 class="ut-abt-headline"><span>Manufacturing.</span><span>Sourcing.</span><span class="hl">Delivering value.</span></h1>
					<p>Unitourk Manufacturing &amp; Trading Group operates within manufacturing, sourcing and international trade of workwear, safety equipment and industrial products.</p>
					<p>Our activities include product development, manufacturing coordination, quality control, sourcing, logistics and international distribution. We work with professional customers and partners across different industries, with a focus on consistent quality, reliable processes and long-term cooperation.</p>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-primary">Contact Us <?php echo $arrow; // phpcs:ignore ?></a>
				</div>
				<div class="ut-abt-hero-media reveal">
					<img src="<?php echo esc_url( $img . '/hero.webp' ); ?>" alt="Unitourk manufacturing and operations">
					<span class="ut-abt-watermark" aria-hidden="true">
						<svg viewBox="0 0 100 116" fill="none"><path d="M50 4 92 28v46c0 6-3 11-8 14L50 112 16 88c-5-3-8-8-8-14V28z" stroke="#fff" stroke-width="4" opacity=".9"/><path d="M36 40v22a14 14 0 0 0 28 0V40" stroke="#fff" stroke-width="9" stroke-linecap="round"/></svg>
					</span>
				</div>
			</div>
		</div>
	</section>

	<!-- Section 2 — 4-item value strip -->
	<section class="ut-abt-values">
		<div class="container">
			<div class="ut-abt-values-grid reveal">
				<div class="ut-abt-value">
					<span class="ut-abt-vico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M3 12l9 4 9-4M3 17l9 4 9-4"/></svg></span>
					<h3>End-to-End Solutions</h3>
					<p>From product development and manufacturing to sourcing, logistics and delivery, coordinated within one group.</p>
				</div>
				<div class="ut-abt-value">
					<span class="ut-abt-vico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5z"/><path d="M9 12l2 2 4-4"/></svg></span>
					<h3>Quality &amp; Reliability</h3>
					<p>Consistent quality and reliable processes on every order, backed by structured quality control.</p>
				</div>
				<div class="ut-abt-value">
					<span class="ut-abt-vico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg></span>
					<h3>International Network</h3>
					<p>Manufacturing coordination and distribution across multiple markets and industries.</p>
				</div>
				<div class="ut-abt-value">
					<span class="ut-abt-vico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 3.1a4 4 0 0 1 0 7.8M22 21v-2a4 4 0 0 0-3-3.9M8 3.1a4 4 0 0 0 0 7.8"/><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2"/></svg></span>
					<h3>Long-Term Partnerships</h3>
					<p>We build lasting cooperation with professional customers and partners across their industries.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Section 3 — "What We Do" image grid -->
	<section class="section ut-abt-work">
		<div class="container">
			<div class="ut-abt-work-head reveal">
				<span class="ut-abt-eyebrow">What We Do</span>
				<h2>From production to delivery</h2>
			</div>
			<div class="ut-abt-grid reveal">
				<figure><img src="<?php echo esc_url( $img . '/eport.webp' ); ?>" alt="Export and logistics"><figcaption>Export &amp; Logistics</figcaption></figure>
				<figure><img src="<?php echo esc_url( $img . '/indu.webp' ); ?>" alt="Manufacturing"><figcaption>Manufacturing</figcaption></figure>
				<figure><img src="<?php echo esc_url( $img . '/manufacturing.webp' ); ?>" alt="Production"><figcaption>Production</figcaption></figure>
			</div>
		</div>
	</section>

	<!-- Closing block -->
	<section class="section ut-abt-closing">
		<div class="container">
			<div class="ut-abt-closing-inner reveal">
				<p>For product inquiries, technical requirements or quotation requests, our team can be contacted directly. We normally respond within 48 hours.</p>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-ghost">Send an inquiry <?php echo $arrow; // phpcs:ignore ?></a>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();
