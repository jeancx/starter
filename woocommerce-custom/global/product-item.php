<?php
/**
 * Product item
 *
 * @package starter
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$starter_img             = $product->get_image_id();
$starter_thumbnails      = $product->get_gallery_image_ids()[0] ? $product->get_gallery_image_ids()[0] : $starter_img;
$starter_price           = $product->get_regular_price();
$starter_sale_price      = $product->get_sale_price();
$starter_comment_enabled = wc_reviews_enabled() && $product->get_reviews_allowed() ? 1 : 0;
$starter_comment_count   = $product->get_review_count();
$starter_comment_rating  = $product->get_average_rating();

// new label
$terms      = wp_get_post_terms( get_the_id(), 'product_tag' );
$term_array = array();
foreach( $terms as $term ){
	$term_array[] = $term->name;
}
// END new label
?>

<div class="box_product_item">

	<!-- main image -->
	<div class="position-relative">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>">
			<picture class="main_img item_img">
				<?php echo do_shortcode( "[img img_src='w600' img_sizes=\"$starter_img_sizes\" img_object=\"$starter_img\"]" ); ?>
			</picture>
			<picture class="thumbnail_img item_img">
				<?php echo do_shortcode( "[img img_src='w600' img_sizes=\"$starter_img_sizes\" img_object=\"$starter_thumbnails\"]" ); ?>
			</picture>
		</a>
		<ul class="list flex-column label_product_list">
			<?php if ( is_plugin_active( 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php' ) && tinv_get_option( 'add_to_wishlist_catalog', 'show_in_loop' ) ) : ?>
				<li><div class="wrap_label"><?php echo do_shortcode( "[ti_wishlists_addtowishlist loop=yes]" ); ?></div></li>
			<?php endif; ?>
			<?php if ( in_array( 'new', $term_array ) ) : ?>
				<li><div class="wrap_label"><?php esc_html_e( 'New', 'starter' ); ?></div></li>
			<?php endif; ?>
			<?php if ( $product->is_on_sale() ) : ?>
				<li><div class="wrap_label"><?php echo starter_get_svg( array( 'icon' => 'percent' ) ); ?></div></li>
			<?php endif; ?>
		</ul>
	</div>
	<!-- END main image -->

	<div class="description_block">
		<div class="top_part_description">
			<h3 class="main_heading"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( the_title() ); ?></a></h3>

			<!-- rating -->
			<?php if ( $starter_comment_enabled ) : ?>
				<div class="d-flex align-items-center justify-content-center">
					<?php
						if ( wc_review_ratings_enabled() && $starter_comment_rating ) {
							require get_stylesheet_directory() . '/woocommerce-custom/global/rating.php';
						} else {
							esc_html_e( 'Reviews&nbsp;', 'starter' );
						}
					?>
					<span class="count_votes">(<?php echo esc_html( $starter_comment_count ) ?>)</span>
				</div>
			<?php endif; ?>
			<!-- END rating -->

			<!-- price -->
			<div class="wrap_price">
				<?php if ( $starter_price ) : ?>
					<?php if ( $product->is_in_stock() ) : ?>
						<?php if ( $product->is_on_sale() ) : ?>
							<span class="new_price"><?php echo wc_price( $starter_sale_price ); ?></span>
							<del class="old_price"><?php echo wc_price( $starter_price ); ?></del>
						<?php else : ?>
							<span><?php echo wc_price( $starter_price ); ?></span>
						<?php endif; ?>
					<?php else : ?>
						<span><?php esc_html_e( 'Sold Out', 'starter' ); ?></span>
					<?php endif; ?>
				<?php elseif ( $product->is_type( 'variable' ) ) : ?>
					<span><?php echo $product->get_price_html(); ?></span>
				<?php endif; ?>
			</div>
			<!-- END price -->

		</div>

		<!-- add to cart -->
		<div class="pl-md-3 pr-md-3">
			<?php
				$button_class = 'btn-outline-primary btn-sm';
				require get_stylesheet_directory() . '/woocommerce-custom/global/add-to-cart.php';
			?>
		</div>
		<!-- END add to cart -->

	</div><!-- END description_block -->

</div><!-- END box_product_item -->