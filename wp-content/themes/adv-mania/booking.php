<?php get_header();
/*
 * Template name: Booking
 * */
$var        = variables();
$set        = $var['setting_home'];
$assets     = $var['assets'];
$admin_ajax = $var['admin_ajax'];
$url        = $var['url'];
$url_home   = $var['url_home'];
$id         = get_the_ID();
$size       = $isLighthouse ? 'thumbnail' : 'full';
if ( isset( $_COOKIE['input_tour_id'] ) ) {
	$tour_id          = intval( $_COOKIE['input_tour_id'] );
	$original_tour_id = pll_get_post( $tour_id, pll_current_language() );

	if ( ! $original_tour_id ) {
		$original_tour_id = intval( $_COOKIE['original_tour_id'] );
	}
}

$screens      = carbon_get_post_meta( $original_tour_id, 'tour_screens' );
$screens_bike = carbon_get_post_meta( $original_tour_id, 'bike_screens' );
?>

<form method="post" class="checkout-form" id="checkout-form" action="<?php echo $admin_ajax; ?>">
    <section style="position: absolute;visibility: hidden;z-index: -1;opacity: 0; ">
		<?php
		$post_type = get_post_type( $tour_id ); ?>
        <div class="container">
            <input type="text" class="stripeAdm"
                   value="<?php echo carbon_get_theme_option( 'crb_stripe_publishable_key' ) ?>">
            <input type="text" class="tour_id" name="tour_id" placeholder="order_id">
            <input type="text" class="tour_name" name="tour_name" placeholder="tour name">
            <input type="text" class="input" name="order_start" placeholder="start">
            <input type="text" class="input" name="order_end" placeholder="end">
            <input type="text" class="items" name="items" placeholder="items">
            <input type="text" class="motos" name="motos" placeholder="motos">
            <input type="text" class="price_tour_start" name="price_tour_start" placeholder="price_tour_start"
                   value="<?php echo carbon_get_post_meta( $original_tour_id, 'price' ); ?>">

            <input type="text" class="postType" name="postType" placeholder="postType"
                   value="<?php echo $post_type; ?>">
            <input type="text" class="country" name="country" placeholder="country">
            <input type="text" class="equipment" name="equipment" placeholder="equipment">
            <input type="text" class="people_count" name="people_count" placeholder="people_count">
            <input type="text" class="people_count_title" name="people_count_title" placeholder="people_count_title">
            <input type="text" class="accommodation_count" name="accommodation_count" placeholder="accommodation_count">
            <input type="text" class="accommodation_count_title" name="accommodation_count_title"
                   placeholder="accommodation_count_title">

            <input type="hidden" class="totalRenderSum" name="order_sum" value="500">
            <input type="hidden" class="count_total" name="count_total" value="10">
            <input type="hidden" class="checkout-data-js" name="checkout_data" value="[150,250]">
            <input type="text" class="bike_id" name="bike_id" placeholder="bike_id">
            <input type="hidden" name="action" value="create_order_temp">
        </div>
        <div class="rent_text"><?php echo pll_e( 'Rent for' ); ?></div>
        <div class="days_text"><?php echo pll_e( 'Days' ); ?></div>
        <div class="Add_to_booking"><?php echo pll_e( 'Add to booking' ); ?></div>
    </section>
    <section class="top-page">
        <div class="top-inner">
            <div class="bread-crumbs">
				<?php if ( $post_type === 'tour' ) { ?>
                    <ul>
                        <li><a href="/"><?php echo pll_e( 'Main' ); ?></a></li>
                        <li><a href="/tours"><?php echo pll_e( 'Tours' ); ?></a></li>
                        <li><?php echo pll_e( 'Booking tours' ); ?></li>
                    </ul>
				<?php } ?>
            </div>
            <div class="title-section"><?php echo pll_e( 'Booking' ); ?><?php $title = get_the_title( $original_tour_id );
				echo $title; ?></div>
        </div>
    </section>
	<?php if ( isset( $_COOKIE['input_tour_id'] ) ) { ?>
        <section class="booking">
            <div class="container">
                <div class="booking-nav">
                    <div class="booking-nav__title active"
                         data-step-title="step1"><?php echo pll_e( 'Choise Tour' ); ?></div>
                    <div class="booking-nav__title "
                         data-step-title="step2"><?php echo pll_e( 'Booking information' ); ?></div>
                    <div class="booking-nav__title" data-step-title="step3"><?php echo pll_e( 'Success' ); ?></div>
                </div>
                <div class="step-inner">
                    <div class="step-item active" data-step="step1">
						<?php $percent_data = carbon_get_post_meta( $tour_id, 'coun_percent_number' ); ?>
						<?php $percent_data_title = carbon_get_post_meta( $tour_id, 'coun_percent_title' ); ?>
                        <div class="booking-tour__wrap">

							<?php if ( $infoList = carbon_get_post_meta( $tour_id, 'list_date' ) ) : ?>
                                <div class="booking-tour__inner">
									<?php $int        = 1;
									foreach ( $infoList as $item ) :
										$repeat_monthly = $item['repeat_monthly'];
										$repeat_count = $item['repeat_count'];
										$start_date   = new DateTime( $item['date_start'] );
										$end_date     = new DateTime( $item['date_end'] ); ?>

                                        <div class="booking-item <?php if ( $int === 1 ) {
											echo 'selected';
										} ?>">
                                            <div class="booking-item__title"><?php echo pll_e( 'Tour dates' ); ?></div>
                                            <div class="booking-item__dates">
                                                <span><?php echo $start_date->format( 'Y-m-d' ); ?></span>
                                                -
                                                <span><?php echo $end_date->format( 'Y-m-d' ); ?></span>
                                            </div>
                                        </div>
										<?php if ( $repeat_monthly && is_numeric( $repeat_count ) && $repeat_count > 1 ) : ?>
										<?php for ( $i = 1; $i < $repeat_count; $i ++ ) : ?>
											<?php
											$start_date->modify( '+1 month' );
											$end_date->modify( '+1 month' );
											?>
                                            <div class="booking-item" data-aos="fade-up">
                                                <div class="booking-item__title"><?php echo pll_e( 'Tour dates' ); ?></div>
                                                <div class="booking-item__dates">
                                                    <span><?php echo $start_date->format( 'Y-m-d' ); ?></span>
                                                    -
                                                    <span><?php echo $end_date->format( 'Y-m-d' ); ?></span>
                                                </div>
                                            </div>
										<?php endfor; ?>
									<?php endif; ?>
										<?php $int ++; endforeach; ?>
                                </div>
							<?php endif; ?>

                            <div class="booking-tour__info">
                                <div class="item" data-aos="fade-up" data-aos-delay="200">
                                    <div class="item-media">
                                        <a href="#">
											<?php
											$terms       = get_the_terms( $original_tour_id, 'category-tour' );
											$class_color = '';
											if ( $terms && ! is_wp_error( $terms ) ) {
												$first_term  = reset( $terms );
												$class_color = carbon_get_term_meta( $first_term->term_id, 'crb_class_color' );
											}
											if ( $terms && ! is_wp_error( $terms ) ) {
												$term_names = wp_list_pluck( $terms, 'name' );
												echo '<span class="label ' . esc_attr( $class_color ) . '">' . implode( ', ', $term_names ) . '</span>';
											} else {
												echo '';
											}
											?>
											<?php $image_url = get_the_post_thumbnail_url( $original_tour_id, 'full' );
											if ( $image_url ) {
												echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
											} ?>
                                        </a>
                                    </div>
                                    <div class="item-desc">
                                        <div class="item-title"><a
                                                    href="<?php echo get_the_permalink( $original_tour_id ); ?>"><?php echo get_the_title( $original_tour_id ); ?></a>
                                        </div>
										<?php if ( $infoList = carbon_get_post_meta( $original_tour_id, 'list' ) ) : ?>
                                            <ul class="item-list">
												<?php foreach ( $infoList as $item ) : ?>
                                                    <li>
                                                        <span><?php echo $item['text_start'] ?></span>
                                                        <span><?php echo $item['text_end'] ?></span>
                                                    </li>
												<?php endforeach; ?>
                                            </ul>
										<?php endif; ?>
                                        <div class="item-bottom">
                                            <span class="item-price__info">Base</span>
                                            <span class="item-price"><?php echo carbon_get_post_meta( $original_tour_id, 'price' ); ?></span>
											<?php $new_price_old = carbon_get_post_meta( $original_tour_id, 'price_old' );
											if ( $new_price_old ) { ?>
                                                <span class="item-price__old"><?php echo $new_price_old; ?></span>
											<?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


						<?php if ( ! empty( $screens ) ) :
							foreach ( $screens as $index => $screen ) :
								$index = $index + 1;
								if ( $screen['_type'] == 'screen_8' ) :
									if ( ! $screen['screen_off'] ) : ?>
                                        <div class="people" id="<?php echo $screen['id'] ?>">
                                            <div class="title-section"
                                                 data-aos="fade-up"><?php echo $screen['title'] ?></div>
											<?php if ( $lists = $screen['list'] ) : $int = 1; ?>
                                                <div class="people-wrap">
													<?php foreach ( $lists as $item ) : ?>
                                                        <div class="people-item <?php if ( $int === 1 ) {
															echo 'selected';
														} ?>" data-aos="fade-up" data-people='<?php echo $int; ?>'>
                                                            <div class="people-item__title"><?php echo $item['title']; ?></div>
                                                            <div class="people-item__desc">
                                                                <div class="people-item__media"><img
                                                                            src="<?php echo $item['image']; ?>"
                                                                            alt="tours"></div>
                                                                <div class="people-item__price">
																	<?php if ( $item['included'] ) : ?>
																		<?php echo $item['text']; ?>
                                                                        <span class="add-price hidden"
                                                                              data-add-people="0 €">0 €</span>
																	<?php else : ?>
                                                                        <span><?php echo $item['text']; ?></span>
                                                                        <span class="add-price"
                                                                              data-add-people="<?php echo esc_html( $item['price'] ); ?>"><?php echo esc_html( $item['price'] ); ?></span>
																	<?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
														<?php $int ++; endforeach; ?>
                                                </div>
											<?php endif; ?>
                                        </div>
									<?php
									endif;
                                elseif ( $screen['_type'] == 'screen_9' ) :
									if ( ! $screen['screen_off'] ) : ?>
                                        <div class="accommodation" id="<?php echo $screen['id'] ?>">
                                            <div class="title-section"
                                                 data-aos="fade-up"><?php echo $screen['title'] ?></div>
											<?php if ( $lists = $screen['list'] ) : $i = 1; ?>
                                                <div class="accommodation-wrap">
													<?php foreach ( $lists as $item ) : ?>
                                                        <div class="accommodation-item <?php if ( $i === 1 ) {
															echo 'selected';
														} ?>" data-aos="fade-up">
                                                            <div class="accommodation-item__title"><img
                                                                        class="svg-convert"
                                                                        src="<?php echo $item['image']; ?>"
                                                                        alt="Double">
                                                                <span><?php echo $item['title']; ?></span>
                                                            </div>
                                                            <div class="accommodation-item__desc"><?php echo $item['text']; ?></div>
                                                            <div class="accommodation-item__price">
																<?php if ( $item['included'] ) : ?>
																	<?php echo $item['text_bottom']; ?>
                                                                    <span class="add-price hidden"
                                                                          data-add-accommodation="0 €">0 €</span>
																<?php else : ?>
                                                                    <span><?php echo $item['text_bottom']; ?></span>
                                                                    <span class="add-price"
                                                                          data-add-accommodation="<?php echo esc_html( $item['price'] ); ?>">
																<?php echo esc_html( $item['price'] ); ?>
															</span>
																<?php endif; ?>
                                                            </div>
                                                        </div>
														<?php $i ++; endforeach; ?>
                                                </div>
											<?php endif; ?>
                                        </div>
									<?php
									endif;
                                elseif ( $screen['_type'] == 'screen_3' ) :
									if ( ! $screen['screen_off'] ) : ?>

                                        <div class="other-section items-bikes" id="<?php echo $screen['id']; ?>">
                                            <div class="container">
                                                <div class="top-section">
                                                    <div class="title-section"><?php echo $screen['title']; ?></div>
													<?php if ( ! empty( $percent_data_title ) ) { ?>
                                                        <div class="subtitle-section"><?php echo $percent_data_title; ?></div>
													<?php } ?>
                                                    <div class="btn-blog hidden">
                                                        <a href="#"
                                                           class="btn btn-red"><?php echo $screen['text_btn']; ?></a>
                                                    </div>
                                                </div>
												<?php if ( $blogs = $screen['bike'] ): ?>
                                                    <div class="items bike-slider"
                                                         data-percent="<?php echo $percent_data; ?>">
														<?php

														foreach ( $blogs as $blog ):
															$_id = $blog['id'];
															if ( get_post( $_id ) ):
																$_img = get_the_post_thumbnail_url( $_id ) ?: '';
																?>

                                                                <div class="item" data-aos="fade-up"
                                                                     data-title="<?php echo get_the_title( $_id ); ?>"
                                                                     data-id="<?php echo $_id; ?>">
                                                                    <div class="item-media">
                                                                        <img src="<?php echo $_img; ?>" alt="Tour">
                                                                    </div>
                                                                    <div class="item-desc">
                                                                        <div class="item-title"><?php echo get_the_title( $_id ); ?></div>
																		<?php
																		$screens_tours = carbon_get_post_meta( $_id, 'bike_screens' );
																		if ( ! empty( $screens_tours ) ) :
																			foreach ( $screens_tours as $index => $screens_tour ) :
																				$index = $index + 1;
																				if ( $screens_tour['_type'] == 'screen_1' ) :
																					if ( ! $screens_tour['screen_off'] ) : ?>
																						<?php
																						$bike_stock     = get_post_meta( $_id, '_bike_stock', true );
																						$bike_price     = get_post_meta( $_id, '_bike_price', true );
																						$bike_price_old = get_post_meta( $_id, '_bike_price_old', true );
																						// $bike_price_old = get_post_meta($_id, '_bike_price_old', true);
																						?>

                                                                                        <div class="item-bottom">
                                                                                            <span class="item-price__info"><?php echo pll_e( 'Add to booking' ); ?></span>
                                                                                            <span class="item-price"
                                                                                                  data-base-price="<?php echo $bike_price * $percent_data; ?>"><?php echo $bike_price * $percent_data; ?></span>
																							<?php if ( $bike_price_old ) { ?>
                                                                                                <span class="item-price__old"><?php echo $bike_price_old * $percent_data; ?></span>
																							<?php } ?>
                                                                                            <button class="btn btn-red"
                                                                                                    tabindex="0">
                                                                                                <span><?php echo pll_e( 'Book' ); ?></span>
                                                                                                <span class="hidden"><?php echo pll_e( 'Cancel' ); ?></span>
                                                                                            </button>
                                                                                        </div>
																					<?php endif ?>
																				<?php endif ?>
																			<?php endforeach ?>
																		<?php endif ?>

                                                                    </div>
                                                                </div>

															<?php endif;
														endforeach; ?>
                                                    </div>
                                                    <div class="hidden moto-id">
														<?php if ( $blogs = $screen['bike'] ): ?>
                                                            <ul>
																<?php foreach ( $blogs as $blog ): $_id = $blog['id'];
																	if ( get_post( $_id ) ): ?>
                                                                        <li><?php echo $_id; ?></li>
																	<?php endif; ?>
																<?php endforeach; ?>
                                                            </ul>
														<?php endif; ?>
                                                    </div>
												<?php endif; ?>
                                            </div>
                                        </div>
									<?php endif;
                                elseif ( $screen['_type'] == 'screen_7' ) :
									if ( ! $screen['screen_off'] ) : ?>
                                        <div class="other-section items-equipment" id="<?php echo $screen['id']; ?>">
                                            <div class="container">
                                                <div class="top-section">
                                                    <div class="title-section"><?php echo $screen['title']; ?></div>
                                                    <div class="btn-blog hidden">
                                                        <a href="#"
                                                           class="btn btn-red"><?php echo $screen['text_btn']; ?></a>
                                                    </div>
                                                </div>
												<?php if ( $blogs = $screen['equipment'] ): ?>
                                                    <div class="items equipment-slider">
														<?php foreach ( $blogs as $blog ):
															$_id = $blog['id'];
															if ( get_post( $_id ) ):
																$_img = get_the_post_thumbnail_url( $_id ) ?: '';
																?>
                                                                <div class="item" data-aos="fade-up"
                                                                     data-title="<?php echo get_the_title( $_id ); ?>"
                                                                     data-id="<?php echo $_id; ?>">
                                                                    <div class="item-media">
                                                                        <img src="<?php echo $_img; ?>" alt="Tour">
                                                                    </div>
                                                                    <div class="item-desc">
                                                                        <div class="item-title">
                                                                            <span><?php echo get_the_title( $_id ); ?></span>
                                                                            <div class="count hidden">
                                                                                <span class="minus">-</span>
                                                                                <input class="count_item"
                                                                                       data-price="2750 €" value="1"
                                                                                       min="1" step="1" type="number"
                                                                                       tabindex="0">
                                                                                <span class="plus">+</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item-bottom">
                                                                            <span class="item-price__info"><?php echo pll_e( 'Add to booking' ); ?></span>
                                                                            <span class="item-price"
                                                                                  data-price="<?php echo carbon_get_post_meta( $_id, 'new_price' ); ?>"><?php echo carbon_get_post_meta( $_id, 'new_price' ); ?></span>
																			<?php $oldPrice = carbon_get_post_meta( $_id, 'new_price_old' ); ?>
																			<?php if ( $oldPrice ) { ?>
                                                                                <span class="item-price__old"><?php echo $oldPrice; ?></span>
																			<?php } ?>
                                                                            <a href="#" class=" btn btn-red">
                                                                                <span><?php echo pll_e( 'Book' ); ?></span>
                                                                                <span class="hidden"><?php echo pll_e( 'Cancel' ); ?></span>
                                                                            </a>
                                                                        </div>

                                                                    </div>
                                                                </div>
															<?php endif;
														endforeach; ?>
                                                    </div>
                                                    <div class="hidden moto-id">
														<?php if ( $blogs = $screen['bike'] ): ?>
                                                            <ul>
																<?php foreach ( $blogs as $blog ): $_id = $blog['id'];
																	if ( get_post( $_id ) ): ?>
                                                                        <li><?php echo $_id; ?></li>
																	<?php endif; ?>
																<?php endforeach; ?>
                                                            </ul>
														<?php endif; ?>
                                                    </div>
												<?php endif; ?>
                                            </div>
                                        </div>

									<?php
									endif;
								endif;
							endforeach;
						endif;
						?>

                        <div class="total-price">
                            <div class="total-price__item" data-aos="fade-up">
                                <div class="total-price__title">
									<?php echo pll_e( 'Total price' ); ?>
                                </div>
                                <div class="total-price__desc">
                                    <div class="total-price__price" data-total-price="12340 €" data-base-price="3250 €">
                                        12340 €
                                    </div>
                                    <a href="#" class="btn btn-red"><?php echo pll_e( 'Reserve' ); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-item " data-step="step2">
                        <div class="form-wrap">
                            <div class="form-info">
                                <div class="form-item__wrap" id="participants-container">
                                    <div class="form-item">
                                        <div class="item-input">
                                            <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_name" ); ?>
                                                <span>*</span>
                                            </label>
                                            <input type="text"
                                                   placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_name" ); ?>"
                                                   class="item-name">
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/user.svg"
                                                 alt="icon">
                                        </div>
                                        <div class="item-input label_counnrty">
                                            <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_counnrty" ); ?></label>
											<?php if ( $selectList = carbon_get_post_meta( get_the_ID(), "text_country" ) ) : ?>
                                                <select class="select">
													<?php foreach ( $selectList as $item ) : ?>
                                                        <option value="<?php echo $item['text'] ?>"><?php echo $item['text'] ?></option>
													<?php endforeach; ?>
                                                </select>
											<?php endif; ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/globe.svg"
                                                 alt="icon">
                                        </div>
                                        <div class="item-input">
                                            <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_phone" ); ?>
                                                <span>*</span>
                                            </label>
                                            <input type="text"
                                                   placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_phone" ); ?>">
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/phone.svg"
                                                 alt="icon">
                                        </div>
                                        <div class="item-input">
                                            <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_contacts" ); ?></label>
											<?php if ( $selectListMes = carbon_get_post_meta( get_the_ID(), "text_contacts" ) ) : ?>
                                                <select class="select">
													<?php foreach ( $selectListMes as $item ) : ?>
                                                        <option value="<?php echo $item['text'] ?>"><?php echo $item['text'] ?></option>
													<?php endforeach; ?>
                                                </select>
											<?php endif; ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/headset_mic.svg"
                                                 alt="icon">
                                        </div>
                                        <div class="item-input">
                                            <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_email" ); ?>
                                                <span>*</span>
                                            </label>
                                            <input type="email"
                                                   placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_email" ); ?>"
                                                   class="item-email">
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/mail.svg"
                                                 alt="icon">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-item-template"
                                       value='<div class="form-item"><div class="item-input"><label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_name" ); ?><span>*</span></label><input type="text" placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_name" ); ?>" class="item-name"><img src="<?php echo get_template_directory_uri(); ?>/img/user.svg" alt="icon"></div><div class="item-input label_counnrty"><label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_counnrty" ); ?></label><?php if ( $selectList = carbon_get_post_meta( get_the_ID(), "text_country" ) ) : ?><select class="select"><?php foreach ( $selectList as $item ) : ?><option value="<?php echo $item['text'] ?>"><?php echo $item['text'] ?></option><?php endforeach; ?></select><?php endif; ?><img src="<?php echo get_template_directory_uri(); ?>/img/globe.svg" alt="icon"></div><div class="item-input"><label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_phone" ); ?> <span>*</span></label><input type="text" placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_phone" ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/phone.svg" alt="icon"></div><div class="item-input"><label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_contacts" ); ?></label><?php if ( $selectListMes = carbon_get_post_meta( get_the_ID(), "text_contacts" ) ) : ?><select class="select"><?php foreach ( $selectListMes as $item ) : ?><option value="<?php echo $item['text'] ?>"><?php echo $item['text'] ?></option><?php endforeach; ?></select><?php endif; ?><img src="<?php echo get_template_directory_uri(); ?>/img/headset_mic.svg" alt="icon"></div><div class="item-input"><label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_email" ); ?> <span>*</span></label><input type="email" placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_email" ); ?>" class="item-email"><img src="<?php echo get_template_directory_uri(); ?>/img/mail.svg" alt="icon"></div></div>'>
                            </div>
                            <div class="form-desc">
                                <div class="form-desc__inner">
                                    <div class="tour-desc__title"><?php $title = get_the_title( $original_tour_id );
										echo $title; ?></div>
									<?php if ( $infoList = carbon_get_post_meta( $original_tour_id, 'list' ) ) : ?>
                                        <div class="tour-desc__list">
                                            <ul>
												<?php foreach ( $infoList as $item ) : ?>
                                                    <li>
                                                        <span><?php echo $item['text_start'] ?></span>
                                                        <span><?php echo $item['text_end'] ?></span>
                                                    </li>
												<?php endforeach; ?>
                                            </ul>
                                        </div>
									<?php endif; ?>
                                    <!-- <div class="item-input">
										<label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_pay" ); ?></label>
										<?php if ( $selectListMes = carbon_get_post_meta( get_the_ID(), "text_pay" ) ) : ?>
											<select class="payment-sum">
												<?php foreach ( $selectListMes as $item ) : ?>
													<option value="3000"><?php echo $item['text'] ?></option>
												<?php endforeach; ?>
											</select>
										<?php endif; ?>
										<img src="<?php echo get_template_directory_uri(); ?>/img/payment.svg" alt="icon">
									</div> -->
									<?php if ( $informList = carbon_get_post_meta( $original_tour_id, 'list_info' ) ) : $i = 1; ?>
                                        <div class="tour-accordion">
											<?php foreach ( $informList as $item ) : ?>
                                                <div class="accordion-item <?php if ( $i === 1 ) {
													echo 'active';
												} ?>">
                                                    <div class="accordion-item__title"><?php echo $item['title'] ?></div>
                                                    <div class="accordion-item__text"><?php echo $item['text'] ?></div>
                                                </div>
												<?php $i ++;
											endforeach; ?>
                                        </div>
									<?php endif; ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="offer_agreement" checked required>
                                        <label for="offer_agreement"><?php echo carbon_get_post_meta( get_the_ID(), "page_offer_agreement" ); ?></label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="privacy_policy" checked required>
                                        <label for="privacy_policy"><?php echo carbon_get_post_meta( get_the_ID(), "privacy_policy" ); ?></label>
                                    </div>
                                    <div class="tour-form">
                                        <div class="pay-item">
                                            <span class="pay-info-label"><?php echo pll_e( 'Card name' ); ?></span>
                                            <input type="text" class="input-tr input-full card-name"
                                                   placeholder="<?php echo pll_e( 'Card name' ); ?>" required>
                                        </div>
                                        <div class="pay-item pay-number">
                                            <span class="pay-info-label"><?php echo pll_e( 'Card number' ); ?></span>
                                            <input type="text" class="input-tr card-number"
                                                   placeholder="0000-0000-0000-0000" required>
                                        </div>
                                        <div class="pay-item pay-year">
                                            <span class="pay-info-label"><?php echo pll_e( 'MM/YY' ); ?></span>
                                            <input type="text" class="input-tr card-m" placeholder="00" required>/<input
                                                    type="text" class="input-tr card-y" placeholder="00" required>
                                        </div>
                                        <div class="pay-item pay-cvv">
                                            <span class="pay-info-label"><?php echo pll_e( 'CVV' ); ?></span>
                                            <input type="text" class="input-tr card-cvv" name="card-cvv"
                                                   placeholder="000" required>
                                        </div>
                                    </div>
	                                <?php adv_mania_promo_form_render(); ?>
                                    <div class="tour-bottom">
                                        <span class="item-price"
                                              data-price="<?php echo carbon_get_post_meta( $original_tour_id, 'new_price' ); ?><?php echo carbon_get_post_meta( $original_tour_id, 'price' ); ?>"><?php echo carbon_get_post_meta( $original_tour_id, 'new_price' ); ?><?php echo carbon_get_post_meta( $tour_id, 'price' ); ?></span>
                                        <!-- <span class="item-price__old"><?php echo carbon_get_post_meta( $original_tour_id, 'new_price_old' ); ?><?php echo carbon_get_post_meta( $original_tour_id, 'price_old' ); ?></span> -->
                                        <button type="submit"
                                                class="btn btn-red btn-submit-order"><?php echo carbon_get_post_meta( get_the_ID(), "payments_btn" ); ?></button>
                                    </div>
	                                <?php adv_mania_advance_form_render() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-item " data-step="step3">
                        <div class="success">
                            <div class="success-item">
                                <div class="success-item__media">
									<svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
										<rect width="80" height="80" rx="40" fill="#E7343F"/>
										<path d="M35.7874 45.4338L50.4029 31.1724C50.7478 30.8359 51.1502 30.6676 51.6101 30.6676C52.07 30.6676 52.4724 30.8359 52.8173 31.1724C53.1622 31.509 53.3346 31.9086 53.3346 32.3714C53.3346 32.8341 53.1622 33.2338 52.8173 33.5703L36.9946 49.0517C36.6496 49.3882 36.2473 49.5565 35.7874 49.5565C35.3275 49.5565 34.9251 49.3882 34.5802 49.0517L27.1646 41.8158C26.8197 41.4793 26.6545 41.0796 26.6688 40.6169C26.6832 40.1541 26.8628 39.7545 27.2078 39.4179C27.5527 39.0814 27.9622 38.9131 28.4365 38.9131C28.9107 38.9131 29.3203 39.0814 29.6652 39.4179L35.7874 45.4338Z"
                                              fill="white"/>
									</svg>
                                </div>
                                <div class="success-item__desc">
                                    <h4><?php echo carbon_get_post_meta( get_the_ID(), "success_title" ); ?></h4>
                                    <p><?php echo carbon_get_post_meta( get_the_ID(), "success_text" ); ?></p>
                                </div>
                                <div class="success-item__link">
                                    <a href="/" class="btn btn-red"><?php echo pll_e( 'Main' ); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
	<?php } else { ?>
        <section class="booking">
            <div class="conainer" style="text-align: center;"><br><a href="/tours" class="btn btn-red">Choose tours</a>
            </div>
        </section>
	<?php } ?>
</form>
<!-- Форма для оплати через PayTR (спочатку прихована) -->
<form id="payment-form" action="https://www.paytr.com/odeme" method="post" style="display:none;">
    <input type="text" class="input-tr cc_owner" name="cc_owner" value="" required>
    <input type="text" class="input-tr card_number" name="card_number" value="" required>
    <input type="text" class="input-tr expiry_month" name="expiry_month" value="" required>
    <input type="text" class="input-tr expiry_year" name="expiry_year" value="" required>
    <input type="text" class="input-tr cvv" name="cvv" value="" required>
    <input type="hidden" name="merchant_id" value="">
    <input type="hidden" name="user_ip" value="">
    <input type="hidden" name="merchant_oid" value="">
    <input type="hidden" name="email" value="">
    <input type="hidden" name="payment_type" value="">
    <input type="hidden" name="payment_amount" value="">
    <input type="hidden" name="currency" value="">
    <input type="hidden" name="test_mode" value="">
    <input type="hidden" name="non_3d" value="">
    <input type="hidden" name="merchant_ok_url" value="">
    <input type="hidden" name="merchant_fail_url" value="">
    <input type="hidden" name="user_name" value="">
    <input type="hidden" name="user_address" value="">
    <input type="hidden" name="user_phone" value="">
    <input type="hidden" name="user_basket" value="">
    <input type="hidden" name="debug_on" value="">
    <input type="hidden" name="client_lang" value="">
    <input type="hidden" name="paytr_token" value="">
    <input type="hidden" name="non3d_test_failed" value="">
    <input type="hidden" name="installment_count" value="">
    <input type="hidden" name="card_type" value="">
    <div id="exchange-rate" data-exchange-rate="">Loading rate…</div>
</form>

<div class="preloader"><img src="/wp-content/themes/adv-mania/img/spinner-1.gif" alt="preloader"></div>
<?php get_footer(); ?>
<style>
    .step-item .form-info input {
        padding: 14rem 40rem 14rem 68rem;
    }

    .step-item {
        top: -1000%;
    }

    .items-bikes .item-book {
        margin: 0;
    }

    #card-element {
        width: 100% !important;
    }

    .tour-form {
        position: relative;
        padding: 0;
        background: transparent;
        justify-content: space-between;
    }

    .items .item {
        margin-bottom: 30rem;
    }

    .expiry_year, .cvv, .expiry_month {
        max-width: 30%;
        margin: 0 auto;
    }

    .payment-tr input {
        margin-bottom: 20rem;
        padding: 14rem 20rem 14rem;
    }

    .tour-form .pay-item {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        margin: 0 0 20rem 0;
    }

    .tour-form .pay-item input {
        width: 100%;
        font: 400 20rem/100% "PP Radio Grotesk";
        position: relative;
        z-index: 2;
        margin: 0;
    }

    .pay-info-label {
        font: 400 20rem/100% "PP Radio Grotesk";
        margin-bottom: 8rem;
        width: 100%;
        display: block;
    }

    .pay-number {
        max-width: 300rem;
    }

    .pay-year {
        max-width: 180rem;
        position: relative;
        font-size: 20rem;
        line-height: 260%;
        z-index: 2;
    }

    .pay-year:before {
        position: absolute;
        content: '';
        left: 0;
        z-index: -1;
        width: 100%;
        background-color: #dedcd3;
        border-color: transparent;
        border-radius: 40rem;
        height: 55.78rem;
        bottom: 0;
    }

    .tour-form .pay-year input {
        max-width: 30rem;
        background-color: transparent;
        padding: 14rem 0 14rem;
        margin: 0;
    }

    .tour-form .pay-year input:nth-child(2) {
        margin-left: 15rem;
    }

    .pay-cvv {
        max-width: 180rem;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>

    jQuery(function ($) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'GET',
            dataType: 'json',
            data: {action: 'get_exchange_rate'},
            success(res) {
                if (res.success) {
                    $('#exchange-rate').text(`1 EUR = ${res.data.rate} TRY`);
                } else {
                    console.error('API error:', res.data);
                }
            },
            error(xhr, status, err) {
                console.error('AJAX error:', status, err);
            }
        });
    });

    $rent_text = $('.rent_text').text();
    $days_text = $('.days_text').text();

    $('.card-m').on('change', function () {
        var value = parseInt($(this).val(), 10);
        if (isNaN(value) || value < 1 || value > 12) {
            $(this).val('');
        }
    });
    // Маскуємо поля введення
    if ($.fn.mask) {
        $('.card-number').mask('0000 0000 0000 0000');
        $('.card-m').mask('00');
        $('.card-y').mask('00');
        $('.card-cvv').mask('000');
    }

    // Маппінг полів для копіювання значення у приховані поля форми оплати
    var fieldMapping = {
        'card-name': 'cc_owner',
        'card-number': 'card_number',
        'card-m': 'expiry_month',
        'card-y': 'expiry_year',
        'card-cvv': 'cvv'
    };

    $.each(fieldMapping, function (sourceClass, targetClass) {
        $('.' + sourceClass).on('input change', function () {
            var val = $(this).val();
            if ($('.' + sourceClass).hasClass('card-name')) {
                $('.' + targetClass).val(val);
            } else {
                val = val.replace(/\s/g, '');
                $('.' + targetClass).val(val);
            }
        });
    });

    const tour_id = localStorage.getItem('tour_id');
    const tour_name = localStorage.getItem('tour_title');
    $('.tour_id').val(tour_id);
    $('.tour_name').val(tour_name);

    $(document).on('change', '.item-name', function (e) {
        $('#booking-form input[name="user_name"]').val($(this).val())
    });
    $(document).on('change', '.item-email', function (e) {
        $('#booking-form input[name="user_email"]').val($(this).val())
    });
    $('#checkout-form').submit(function (e) {
        e.preventDefault();
        var form = $('#checkout-form');
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: form.serialize(),
            success: (response) => {
                if (response.type === 'success') {
                    $('.preloader').addClass('active');
                    $('#payment-form input[name="merchant_id"]').val(response.merchant_id);
                    $('#payment-form input[name="user_ip"]').val(response.user_ip);
                    $('#payment-form input[name="merchant_oid"]').val(response.merchant_oid);
                    $('#payment-form input[name="email"]').val(response.email);
                    $('#payment-form input[name="payment_type"]').val(response.payment_type);
                    $('#payment-form input[name="payment_amount"]').val(response.payment_amount);
                    $('#payment-form input[name="currency"]').val(response.currency);
                    $('#payment-form input[name="test_mode"]').val(response.test_mode);
                    $('#payment-form input[name="non_3d"]').val(response.non_3d);
                    $('#payment-form input[name="merchant_ok_url"]').val(response.merchant_ok_url);
                    $('#payment-form input[name="merchant_fail_url"]').val(response.merchant_fail_url);
                    $('#payment-form input[name="user_name"]').val(response.user_name);
                    $('#payment-form input[name="user_address"]').val(response.user_address);
                    $('#payment-form input[name="user_phone"]').val(response.user_phone);
                    $('#payment-form input[name="user_basket"]').val(response.user_basket);
                    $('#payment-form input[name="debug_on"]').val(response.debug_on);
                    $('#payment-form input[name="client_lang"]').val(response.client_lang);
                    $('#payment-form input[name="paytr_token"]').val(response.paytr_token);
                    $('#payment-form input[name="non3d_test_failed"]').val(response.non3d_test_failed);
                    $('#payment-form input[name="installment_count"]').val(response.installment_count);
                    $('#payment-form input[name="card_type"]').val(response.card_type);
                    $('#payment-form').submit();
                    form.trigger('reset');
                    setTimeout(function () {
                        // $('.booking-nav__title.active').addClass('success').removeClass('active').next().addClass('active');
                        // $('.step-item.active').removeClass('active').next().addClass('active');
                        // $('.link-section .btn').remove();
                        $('.preloader').removeClass('active');
                        console.log('Thank you. Your order has been sent.');
                    }, 3000);
                } else {
                    $('#card-errors').text(response.message);
                    console.log('card errors in pay');
                }
            },
            error: function (xhr, str) {
                console.log('Error occurred: ', xhr);
            }
        });
    });
    let selectedItems = [];
    let selectedItemsEquipment = [];

    function handleItemSelection(selector, targetArray, outputField) {
        $(document).on('click', `${selector} .item .btn-red`, function (e) {
            e.preventDefault();
            const days = tourDateSelected() || 1;
            const $item = $(this).closest('.item');
            const itemData = {
                "name": $item.attr('data-title'),
                "id": $item.attr('data-id'),
                "price": parseFloat($item.find('.item-price').text().replace(/[^0-9.-]+/g, '')) * days || 0
            };
            const index = targetArray.findIndex(item => item.id === itemData.id);
            if (index === -1) {
                targetArray.push(itemData);
            } else {
                targetArray.splice(index, 1);
            }
            $(outputField).val(JSON.stringify(targetArray));
        });
    }

    handleItemSelection('.items-bikes', selectedItems, '.motos');
    handleItemSelection('.items-equipment', selectedItemsEquipment, '.equipment');
    $('.total-price__price').each(function () {
        const basePrice = $('.booking-tour__info .item-price').text().trim();
        $(this).text(basePrice);
        $(this).attr('data-total-price', basePrice);
        $(this).attr('data-base-price', basePrice);

    });

    // Функція для обчислення кількості днів між обраними датами
    function tourDateSelected() {
        let startDateStr = $('.booking-item.selected .booking-item__dates span').eq(0).text().trim();
        let endDateStr = $('.booking-item.selected .booking-item__dates span').eq(1).text().trim();
        let startDate = new Date(startDateStr);
        let endDate = new Date(endDateStr);
        if (startDateStr === endDateStr) {
            return 1;
        } else if (isNaN(startDate) || isNaN(endDate)) {
            console.error('Некоректна дата!');
            return 0; // Повертаємо 0, якщо дати некоректні
        } else {
            let diffInTime = endDate - startDate;
            let diffInDays = diffInTime / (1000 * 60 * 60 * 24);

            return diffInDays;
        }


    }

    // Функція для оновлення загальної ціни
    function updateTotalPriceDisplay(price) {
        const $totalPriceElement = $('.total-price__price');
        $totalPriceElement.text(`${price.toFixed(0)} €`);
        $totalPriceElement.attr('data-base-price', price.toFixed(0));
        $('.totalRenderSum').val(price.toFixed(0));
    }

    $(document).on('click', '.people-item', function () {
        const $this = $(this);

        $('.people_count').val($(this).find('.add-price').text());
        $('.people_count_title').val($(this).find('.people-item__title').text());
        $(this).val($('.people-item.selected').find('.add-price'));
        if ($this.hasClass('selected')) {
            return;
        } else {
            const $previousSelected = $('.people-item.selected');
            const previousPrice = parseFloat($previousSelected.find('.add-price').attr('data-add-people').replace(/[^0-9.-]+/g, '')) || 0;
            const $totalPriceElement = $('.total-price__price');
            let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
            currentTotalPrice -= previousPrice;
            let thisItemPrice = parseFloat($this.find('.add-price').attr('data-add-people').replace(/[^0-9.-]+/g, '')) || 0;
            currentTotalPrice += thisItemPrice;
            updateTotalPriceDisplay(currentTotalPrice);
            $previousSelected.removeClass('selected');
            $this.addClass('selected');

        }
    });


    $(document).on('click', '.accommodation-item', function () {
        const $this = $(this);
        $('.accommodation_count').val($(this).find('.add-price').attr('data-add-accommodation'));
        $('.accommodation_count_title').val($(this).find('.accommodation-item__title span').text());
        if ($this.hasClass('selected')) {
            return;
        } else {
            const $previousSelected = $('.accommodation-item.selected');
            const previousPrice = parseFloat($previousSelected.find('.add-price').attr('data-add-accommodation').replace(/[^0-9.-]+/g, '')) || 0;
            const $totalPriceElement = $('.total-price__price');
            let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
            currentTotalPrice -= previousPrice;
            let thisItemPrice = parseFloat($this.find('.add-price').attr('data-add-accommodation').replace(/[^0-9.-]+/g, '')) || 0;
            currentTotalPrice += thisItemPrice;
            updateTotalPriceDisplay(currentTotalPrice);
            $previousSelected.removeClass('selected');
            $this.addClass('selected');
        }
    });

    // Обробник для кнопки "додати/видалити" велосипеди
    $(document).on('click', '.items-bikes .item .btn-red', function (e) {
        e.preventDefault;

        const $this = $(this).closest('.item');

        const $t = $(this);
        var $selector = $(document).find('.items-bikes');
        const thisItemPrice = parseFloat($this.find('.item-price').attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        const dataPercent = $('.bike-slider').attr('data-percent');
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        const days = tourDateSelected();
        const totalItemPrice = thisItemPrice * days;

        // console.log(`Ціна за день: ${thisItemPrice}, Загальна ціна за ${days} днів: ${totalItemPrice}`);

        const textInfoDays = $this.find('.item-price__info');

        const textInfoDaysUpdate = $rent_text + ' ' + days + ' ' + $days_text;

        $this.find('.item-price').toggleClass('item-book');
        $this.find('.item-price span').toggleClass('hidden');
        if ($this.hasClass('selected')) {
            currentTotalPrice -= totalItemPrice;
            $this.removeClass('selected');
            $this.find('.btn-red').removeClass('item-book');
            $this.find('.btn-red span').toggleClass('hidden');

            $this.find('.item-price__old').removeClass('hidden');
            const $Add_to_booking = $('.Add_to_booking').text();
            textInfoDays.text($Add_to_booking);

            $this.find('.item-price').text(thisItemPrice + ' €');
            $days_text = $('.days_text').text();
            $('.text-info-days p').text($rent_text + ' ' + days + ' ' + $days_text);
            $selector.find('.item .btn-red').not($t).removeAttr('disabled');

        } else {
            currentTotalPrice += totalItemPrice;
            $this.addClass('selected');
            $this.find('.btn-red').addClass('item-book');
            textInfoDays.text(textInfoDaysUpdate);
            $this.find('.btn-red span').toggleClass('hidden');
            $this.find('.item-price').text(totalItemPrice + ' €');
            $this.find('.item-price__old').addClass('hidden');
            $('.text-info-days p').text($rent_text + ' ' + days + ' ' + $days_text)
            $selector.find('.item .btn-red').not($t).attr('disabled', 'disabled');

        }
        updateTotalPriceDisplay(currentTotalPrice);
    });

    // Обробник для кнопки "додати/видалити" обладнання
    $(document).on('click', '.items-equipment .item .btn-red', function (e) {
        e.preventDefault();
        const $item = $(this).closest('.item');
        const $t = $(this);
        var $selector = $(document).find('.items-bikes');
        const basePrice = parseFloat($item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
        let countItemVal = parseInt($item.find('.count_item').val()) || 1;
        const days = tourDateSelected() || 1;
        const totalItemPrice = basePrice * countItemVal * days;

        updatePrice($item);
        const textInfoDays = $item.find('.item-price__info');
        const textInfoDaysUpdate = $rent_text + ' ' + days + ' ' + $days_text;

        $item.find('.count').toggleClass('hidden');
        $(this).toggleClass('item-book');
        $(this).find('span').toggleClass('hidden');
        $item.toggleClass('selected');

        // Оновлюємо загальну ціну
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;

        if ($item.hasClass('selected')) {
            currentTotalPrice += totalItemPrice;
            textInfoDays.text(textInfoDaysUpdate);
            $item.find('.item-price__old').addClass('hidden');
            $('.text-info-days p').text($rent_text + ' ' + days + ' ' + $days_text)
        } else {
            currentTotalPrice -= totalItemPrice;
            const $Add_to_booking = $('.Add_to_booking').text();
            textInfoDays.text($Add_to_booking);
            $item.find('.item-price').text(basePrice + ' €');
            $item.find('.item-price__old').removeClass('hidden');

            $('.text-info-days p').text($rent_text + ' ' + days + ' ' + $days_text)
        }
        updateTotalPriceDisplay(currentTotalPrice);
    });

    // Функція для оновлення ціни елемента
    function updatePrice(item) {
        const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
        const quantity = parseInt(item.find('.count_item').val()) || 1;
        const days = tourDateSelected() || 1;
        const newPrice = basePrice * quantity * days;
        item.find('.item-price').text(newPrice.toFixed(0) + ' €');
    }

    // Клік по кнопці + (збільшуємо кількість товару)
    $('.plus').click(function () {
        const item = $(this).closest('.item');
        const input = item.find('.count_item');
        const currentValue = parseInt(input.val()) || 1;
        const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
        const days = tourDateSelected() || 1;

        input.val(currentValue + 1);

        // Оновлюємо загальну ціну цього товару
        const totalPrice = basePrice * (currentValue + 1) * days;
        item.find('.item-price').text(totalPrice.toFixed(0) + ' €');

        // Оновлюємо загальну ціну
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice += basePrice * days;

        updateTotalPriceDisplay(currentTotalPrice);
    });

    // Клік по кнопці - (зменшуємо кількість товару)
    $('.minus').click(function () {
        const item = $(this).closest('.item');
        const input = item.find('.count_item');
        const currentValue = parseInt(input.val()) || 1;
        const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
        const days = tourDateSelected() || 1;

        if (currentValue > 1) {
            input.val(currentValue - 1);

            // Оновлюємо загальну ціну цього товару
            const totalPrice = basePrice * (currentValue - 1) * days;
            item.find('.item-price').text(totalPrice.toFixed(0) + ' €');

            // Оновлюємо загальну ціну
            const $totalPriceElement = $('.total-price__price');
            let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
            currentTotalPrice -= basePrice * days;

            updateTotalPriceDisplay(currentTotalPrice);
        }
    });

    // Функція для оновлення загальної ціни
    function updateTotalPriceDisplay(price) {
        const $totalPriceElement = $('.total-price__price');
        $totalPriceElement.text(`${price.toFixed(0)} €`);
        $totalPriceElement.attr('data-base-price', price.toFixed(0));
    }


    // Функція для оновлення загальної ціни
    function updateTotalPrice() {
        let totalPrice = 0;
        // Додаємо всі ціни вибраних елементів
        $('.items-bikes .item.selected, .items-equipment .item.selected').each(function () {
            const itemPrice = parseFloat($(this).find('.item-price').text().replace(/[^0-9.-]+/g, '')) || 0;
            totalPrice += itemPrice;
        });

        updateTotalPriceDisplay(totalPrice);
    }

    $('.booking-item.selected').each(function () {
        $('input[name="order_start"]').val($(this).find('span:first-child').text());
        $('input[name="order_end"]').val($(this).find('span:last-child').text())
        console.log($('input[name="order_start"]').val());
    });
    $('.booking-item').on('click', function () {
        $('input[name="order_start"]').val($(this).find('span:first-child').text());
        $('input[name="order_end"]').val($(this).find('span:last-child').text())
    });

    function addParticipantFields() {
        const template = document.querySelector('.form-item-template').value;
        return template;
    }


    $('.people-item').on('click', function () {

        const countPeopele = $(this).data('people');

        function updateParticipants() {
            $('#participants-container').empty();
            for (let i = 0; i < countPeopele; i++) {
                $('#participants-container').append(addParticipantFields());
            }
        }

        updateParticipants();
        countrySelect();
        $('.select').selectric('refresh');
    });


    function sumCalc() {
        const priceTextSum = $('.total-price__item .total-price__price').attr('data-base-price');
        const priceBookingSum = parseFloat(priceTextSum);
        const $selectPrice = $('.payment-sum');
        const priceBookingSumPercent = (Number(priceTextSum) * 40) / 100;
        $selectPrice.find('option').eq(0).val(priceBookingSum);
        $selectPrice.find('option').eq(1).val(priceBookingSumPercent);
        // $('.totalRenderSum').val(priceBookingSum);
        // $selectPrice.addClass('select');

    }


    $('.payment-sum').on('change', function (e) {
        sumCalc();
        const selectedValue = $(this).val();
        // $('.totalRenderSum').val(selectedValue);
        $('.item-price').text(selectedValue + ' €');
    });


    $('.total-price .btn-red').on('click', function (e) {
        $('.step-item.active, .booking-nav__title.active').removeClass('active').next().addClass('active');

        $('.totalRenderSum').val($('.total-price__price').text());

        $('.form-desc .tour-bottom .item-price').attr('data-price', $('.total-price__price').attr('data-total-price'));
        $('.form-desc .tour-bottom .item-price').text($('.total-price__price').text());
        $('.payment-sum').selectric('refresh');
        $('.select').selectric('refresh');
        sumCalc();
    });


    $(document).on('change', '#participants-container input, #participants-container select', function (e) {
        const participantsData = [];
        $('#participants-container .form-item').each(function () {
            const participant = {
                name: $(this).find('input[type="text"]').val(),
                country: $(this).find('select').eq(0).val(),
                phone: $(this).find('input[type="text"]').eq(1).val(),
                communication: $(this).find('select').eq(1).val(),
                email: $(this).find('input[type="email"]').val()
            };
            participantsData.push(participant);
        });
        const jsonData = JSON.stringify(participantsData);
        $('.items').val(jsonData);
    });


    function showMore() {
        const items = $('.items-bikes .items .item');
        const itemsCount = items.length;
        if (itemsCount > 3) {
            $('.items-bikes .btn-blog').removeClass('hidden');
            items.slice(3).addClass('hidden');
        }
    }

    showMore();

    $('.items-bikes .btn-blog .btn-red').on('click', function (e) {
        e.preventDefault();
        $('.items .item.hidden').removeClass('hidden');
        $(this).closest('.btn-blog').addClass('hidden');
    });

    $('.people_count').each(function () {
        $(this).val($('.people-item.selected').find('.add-price').attr('data-add-people'));
    });
    $('.accommodation_count').each(function () {
        $(this).val($('.accommodation-item.selected').find('.add-price').attr('data-add-accommodation'));
    });
    $('.people_count_title').each(function () {
        $(this).val($('.people-item.selected').find('.people-item__title').text());
    });
    $('.accommodation_count_title').each(function () {
        $(this).val($('.accommodation-item.selected').find('.accommodation-item__title span').text());
    });


    function countrySelect() {
        const $select = $('.label_counnrty select');
        if (!$select.length) return console.error('Select not found');
        $.getJSON('/wp-content/themes/adv-mania/assets/js/countries.json')
            .done(countries => {
                countries.forEach(c => $select.append(`<option value="${c.country}">${c.country}</option>`));
                $.get("https://get.geojs.io/v1/ip/geo.json")
                    .done(data => {
                        console.log('GeoIP:', data.country);
                        if ($select.find(`option[value="${data.country}"]`).length) {
                            $select.val(data.country).selectric('refresh');
                        }
                    })
                    .fail(() => console.warn('GeoIP failed'));
                $select.selectric({
                    onOpen() {
                        const $items = $('.selectric-items');
                        const $scroll = $items.find('.selectric-scroll');
                        if (!$items.find('.selectric-search-wrapper').length) {
                            $('<div class="selectric-search-wrapper"><input type="text" class="selectric-search" placeholder="Type to search…"></div>')
                                .insertBefore($scroll);
                            $items.on('input', '.selectric-search', function () {
                                const term = $(this).val().toLowerCase();
                                $items.find('ul li').each(function () {
                                    $(this).toggle($(this).text().toLowerCase().includes(term));
                                });
                            });
                        }
                        $items.find('.selectric-search').focus();
                    }
                });

            })
            .fail(() => console.error('countries.json load failed'));
    }

    countrySelect();


    $('.items-equipment .item').each(function () {
        const days = tourDateSelected() || 1;
        $(this).find('.item-price__old').addClass('hidden');
        $('.item-price__info').text($rent_text + ' ' + days + ' ' + $days_text)
        const basePrice = parseFloat($(this).find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
        let countItemVal = parseInt($(this).find('.count_item').val()) || 1;
        const totalItemPrice = basePrice * days;
        $(this).find('.item-price').text(totalItemPrice + ' €')
    });
    $('.bike-slider .item').each(function () {
        const days = tourDateSelected() || 1;
        $(this).find('.item-price__old').addClass('hidden');
        $('.item-price__info').text($rent_text + ' ' + days + ' ' + $days_text)
        const basePrice = parseFloat($(this).find('.item-price').data('base-price').toString().replace(/[^0-9.-]+/g, '')) || 0;
        let countItemVal = parseInt($(this).find('.count_item').val()) || 1;
        const totalItemPrice = basePrice * days;
        $(this).find('.item-price').text(totalItemPrice + ' €')
    });
</script>
<!-- <script src="<?php echo get_template_directory_uri(); ?>/assets/js/calendar.js"></script> -->



