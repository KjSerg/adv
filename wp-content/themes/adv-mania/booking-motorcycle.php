<?php get_header();
/*
 * Template name: Booking moto
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
            <input type="text" class="tour_id" name="tour_id" placeholder="order_id">
            <input type="text" class="tour_name" name="tour_name" placeholder="tour name"
                   value="<?php echo get_the_title( $original_tour_id ); ?>">
            <input type="text" class="input" name="order_start" placeholder="start">
            <input type="text" class="input" name="order_end" placeholder="end">
            <input type="text" class="items" name="items" placeholder="items">
            <input type="text" class="motos" name="motos" placeholder="motos">
            <input type="text" class="postType" name="postType" placeholder="postType"
                   value="<?php echo $post_type; ?>">
            <input type="text" class="country" name="country" placeholder="country">
            <input type="text" class="equipment" name="equipment" placeholder="equipment">
            <input type="hidden" class="totalRenderSum" name="order_sum" value="500">
            <input type="hidden" class="count_total" name="count_total" value="10">
            <input type="hidden" class="checkout-data-js" name="checkout_data" value="[150,250]">
            <input type="hidden" name="action" value="create_order_temp">
        </div>
        <div class="rent_text"><?php echo pll_e( 'Rent for' ); ?></div>
        <div class="days_text"><?php echo pll_e( 'Days' ); ?></div>
        <div class="Add_to_booking"><?php echo pll_e( 'Add to booking' ); ?></div>
        <div class="Add_to_booking"><?php echo pll_e( 'Base on' ); ?></div>
    </section>
    <section class="top-page" data-title-test="<?php echo $id; ?>">
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
                    <div class="booking-nav__title active" data-step-title="step1"><?php if ( $post_type === 'tour' ) {
							echo pll_e( 'Choise Tour' );
						} else {
							echo pll_e( 'Choise Motorcycle' );
						} ?></div>
                    <div class="booking-nav__title "
                         data-step-title="step2"><?php echo pll_e( 'Booking information' ); ?></div>
                    <div class="booking-nav__title" data-step-title="step3"><?php echo pll_e( 'Success' ); ?></div>
                </div>
                <div class="step-inner">
                    <div class="step-item active" data-step="step1">
                        <div class="booking-moto__wrap">
                            <div class="booking-moto__info">
                                <div class="item-media" data-aos="fade-up" data-aos-delay="200">
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
                                </div>
                                <div class="item-desc" data-aos="fade-up" data-aos-delay="200">
                                    <div class="item-title"><a
                                                href="<?php echo get_the_permalink( $original_tour_id ); ?>"><?php echo get_the_title( $original_tour_id ); ?></a>
                                    </div>
									<?php
									$bike_stock     = get_post_meta( $original_tour_id, '_bike_stock', true );
									$bike_price     = get_post_meta( $original_tour_id, '_bike_price', true );
									$bike_price_old = get_post_meta( $original_tour_id, '_bike_price_old', true );
									if ( $infoList = carbon_get_post_meta( $original_tour_id, 'list' ) ) :
										?>
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
                                        <span class="item-price__info">Base on 3 day</span>
                                        <span class="item-price"><?php echo carbon_get_post_meta( $original_tour_id, 'price' ); ?></span>
										<?php $new_price_old = carbon_get_post_meta( $original_tour_id, 'price_old' );
										if ( $new_price_old ) { ?>
                                            <span class="item-price__old"><?php echo $new_price_old; ?></span>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-moto__dates">
                                <div class="booking-item selected" data-aos="fade-up">
                                    <div class="booking-item__title"><?php echo pll_e( 'Selected dates' ); ?></div>
                                    <div class="booking-item__dates">
                                        <span class="start">2024.11.03</span>
                                        -
                                        <span class="end">2024.11.10</span>
                                        <a href="<?php echo get_the_permalink( $original_tour_id ); ?>"
                                           class="btn btn-radial active"><?php echo pll_e( 'Change the date' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php if ( ! empty( $screens_bike ) ) :
							foreach ( $screens_bike as $index => $screen ) :
								$index = $index + 1;
								if ( $screen['_type'] == 'screen_3' ) :
									if ( ! $screen['screen_off'] ) : ?>
                                        <div class="other-section items-equipment">
                                            <div class="top-section" data-aos="fade-up">
                                                <div class="title-section"><?php echo pll_e( 'Additional equipment' ); ?></div>
                                                <div class="btn-blog hidden">
                                                    <a href="#" class="btn btn-red">View all equipment</a>
                                                </div>
                                            </div>
											<?php if ( $blogs = $screen['equipment'] ): ?>
                                                <div class="items">
													<?php foreach ( $blogs as $blog ):
														$_id = $blog['id'];
														if ( get_post( $_id ) ): $_img = get_the_post_thumbnail_url( $_id ) ?: ''; ?>

                                                            <div class="item" data-aos="fade-up"
                                                                 data-title="<?php echo get_the_title( $_id ); ?>"
                                                                 data-id="<?php echo $_id; ?>">
                                                                <div class="item-media">
                                                                    <img src="<?php echo $_img; ?>" alt="Tour">
                                                                </div>
                                                                <div class="item-desc">
                                                                    <div class="item-title">
																		<?php echo get_the_title( $_id ); ?>
                                                                        <div class="count hidden">
                                                                            <span class="minus">-</span>
                                                                            <input class="count_item"
                                                                                   data-price="2750 €" value="1" min="1"
                                                                                   step="1" type="number" tabindex="0">
                                                                            <span class="plus">+</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item-bottom">
                                                                        <span class="item-price__info"><?php echo pll_e( 'Add to rent' ); ?></span>
                                                                        <span class="item-price"
                                                                              data-price="<?php echo carbon_get_post_meta( $_id, 'new_price' ); ?>"><?php echo carbon_get_post_meta( $_id, 'new_price' ); ?></span>
																		<?php $oldPrice = carbon_get_post_meta( $_id, 'new_price_old' ); ?>
																		<?php if ( $oldPrice ) { ?>
                                                                            <span class="item-price__old"><?php echo $oldPrice; ?></span>
																		<?php } ?>
                                                                        <a href="#" class=" btn btn-red" >
                                                                            <span><?php echo pll_e( 'Booking' ); ?></span>
                                                                            <span class="hidden"><?php echo pll_e( 'Cancel' ); ?></span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
														<?php endif;
													endforeach; ?>
                                                </div>
											<?php endif; ?>
                                        </div>
									<?php endif; ?>
								<?php
								endif;
							endforeach;
						endif;
						?>
						<?php if ( ! empty( $screens ) ) :
							foreach ( $screens as $index => $screen ) :
								$index = $index + 1;
								if ( $screen['_type'] == 'screen_3' ) :
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
                                                                                                  data-price="<?php echo $bike_price ?>"><?php echo $bike_price ?></span>
																							<?php if ( $bike_price_old ) { ?>
                                                                                                <span class="item-price__old"><?php echo $bike_price_old ?></span>
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
									<?php echo pll_e( 'Total price bike' ); ?>
                                </div>
                                <div class="total-price__desc">
                                    <div class="total-price__price" data-total-price="12340 €"
                                         data-base-price="12340 €">9000 €
                                    </div>
                                    <a href="#" class="btn btn-red"><?php echo pll_e( 'Reserve' ); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-item " data-step="step2">
                        <div class="form-wrap">
                            <div class="form-info">
                                <div class="form-item__wrap">
                                    <div class="form-item">
                                        <div class="item-input">
                                            <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_name" ); ?>
                                                <span>*</span>
                                            </label>
                                            <input type="text"
                                                   placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_name" ); ?>"
                                                   class="item-name" required>
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
                                                   placeholder="<?php echo carbon_get_post_meta( get_the_ID(), "text_phone" ); ?>"
                                                   required>
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
                                                   class="item-email" required>
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/mail.svg"
                                                 alt="icon">
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="item-input hidden">
                                        <label><?php echo carbon_get_post_meta( get_the_ID(), "label_text_pay" ); ?></label>
										<?php if ( $selectListMes = carbon_get_post_meta( get_the_ID(), "text_pay" ) ) : ?>
                                            <select class="payment-sum">
												<?php foreach ( $selectListMes as $item ) : ?>
                                                    <option value="3000"><?php echo $item['text'] ?></option>
												<?php endforeach; ?>
                                            </select>
										<?php endif; ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/payment.svg"
                                             alt="icon">
                                    </div>
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
                                    <div class="text-info-days text-section">
                                        <br>
                                        <p>Rent for
                                            <span></span>
                                        </p>
                                    </div>
                                    <div class="tour-bottom">
                                        <span class="item-price"
                                              data-price="<?php echo carbon_get_post_meta( $original_tour_id, 'new_price' ); ?><?php echo carbon_get_post_meta( $original_tour_id, 'price' ); ?>"><?php echo carbon_get_post_meta( $original_tour_id, 'new_price' ); ?><?php echo carbon_get_post_meta( $tour_id, 'price' ); ?></span>
                                        <!-- <span class="item-price__old"><?php echo carbon_get_post_meta( $original_tour_id, 'new_price_old' ); ?><?php echo carbon_get_post_meta( $original_tour_id, 'price_old' ); ?></span> -->
                                        <button type="submit"
                                                class="btn btn-red"><?php echo carbon_get_post_meta( $id, "payments_btn" ); ?></button>
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
            <div class="conainer" style="text-align: center;"><br><a href="/motorcycle/"
                                                                     class="btn btn-red"><?php echo pll_e( 'Choose Motorcycle' ); ?></a>
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

<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/booking-motorcycle.js"></script>