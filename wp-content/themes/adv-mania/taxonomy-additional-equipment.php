<?php get_header();

$var          = variables();
$id           = get_the_ID();
$current_term = get_queried_object();
if ( $current_term ) {
	$term_name = ucfirst( $current_term->name );
}
?>
<section class="top-page">
    <div class="top-inner">
        <div class="bread-crumbs">
            <ul>
                <li><a href="/">Main</a></li>
                <li><?php echo esc_html( $term_name ); ?></li>
            </ul>
        </div>
        <div class="title-section"><?php echo esc_html( $term_name ); ?></div>
		<?php
		$terms           = get_terms( array(
			'taxonomy'   => 'additional-equipment',
			'hide_empty' => true,
		) );
		$current_term_id = get_queried_object_id(); ?>

		<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
            <div class="filter-button">
				<?php foreach ( $terms as $term ) :
					$active_class = ( $term->term_id === $current_term_id ) ? 'active' : '';
					?>
                    <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
                       class="btn btn-radial <?php echo esc_attr( $active_class ); ?>"><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
    </div>
</section>
<section class="tours-page">
    <div class="container">
		<?php
		if ( have_posts() ) : ?>
        <div class="items">
			<?php $i = 1;
			while ( have_posts() ) : the_post(); ?>
                <div class="item" data-aos="fade-up" data-aos-delay="<?php echo ( $i - 1 ) % 3 + 1; ?>00">
                    <div class="item-media">
                        <a href="<?php the_permalink(); ?>">
							<?php
							$terms = get_the_terms( get_the_ID(), 'additional-equipment' );
							if ( $terms && ! is_wp_error( $terms ) ) :
								foreach ( $terms as $term ) :
									$class_color = carbon_get_term_meta( $term->term_id, 'crb_class_color' ); ?>
                                    <span class="label <?php echo esc_attr( $class_color ); ?>"><?php echo esc_html( $term->name ); ?></span>
								<?php endforeach;
							endif; ?>
							<?php
							$image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
							if ( $image_url ) {
								echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
							} ?>
                        </a>
                    </div>
                    <div class="item-desc">
                        <div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
						<?php if ( $infoList = carbon_get_post_meta( $id, 'list' ) ) : ?>
                            <ul class="item-list">
								<?php foreach ( $infoList as $item ) : ?>
                                    <li>
                                        <span><?php echo esc_html( $item['text_start'] ); ?></span>
                                        <span><?php echo esc_html( $item['text_end'] ); ?></span>
                                    </li>
								<?php endforeach; ?>
                            </ul>
						<?php endif; ?>
                        <div class="item-bottom">
                            <span class="item-price"><?php echo esc_html( carbon_get_post_meta( $id, 'new_price' ) ); ?></span>
							<?php $new_price_old = carbon_get_post_meta( $id, 'new_price_old' );
							if ( $new_price_old ) { ?>
                                <span class="item-price__old"><?php echo esc_html( $new_price_old ); ?></span>
							<?php } ?>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>"
                               class="item-book btn btn-red">Book</a>
                        </div>
                    </div>
                </div>
				<?php
				$i ++;
			endwhile;

			wp_reset_postdata();
			else :
				echo 'Немає постів для відображення.';
			endif;
			?>
        </div>
        <div class="bottom-blog" ><?php custom_pagination(); ?></div>
    </div>
</section>
<?php get_footer(); ?>
