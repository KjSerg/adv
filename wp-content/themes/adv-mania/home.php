<?php get_header();
/*
 * Template name: home
 * */
$var      = variables();
$set      = $var['setting_home'];
$assets   = $var['assets'];
$url      = $var['url'];
$url_home = $var['url_home'];
$id       = get_the_ID();
$screens  = carbon_get_post_meta( $id, 'screens' );
?>
<?php if ( ! empty( $screens ) ) :
	foreach ( $screens as $index => $screen ) :
		$index = $index + 1;
		if ( $screen['_type'] == 'screen_1' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="hero" id="<?php echo $screen['id']; ?>">
                    <div class="hero-wrap">
                        <div class="hero-desc" data-aos="fade-up">
							<?php if ( $heroList = $screen['list'] ) : ?>
                                <ul class="hero-list">
									<?php foreach ( $heroList as $item ) : ?>
                                        <li><?php echo $item['title'] ?></li>
									<?php endforeach; ?>
                                </ul>
							<?php endif; ?>
                            <div class="hero-title"><?php echo $screen['title']; ?></div>
                            <div class="hero-text"><?php echo $screen['text']; ?></div>
                            <div class="hero-link"><a href="#" class="btn btn-yellow btn-popup"
                                                      data-popup="info"><?php echo $screen['text_btn']; ?></a></div>
                        </div>
                        <div class="hero-media" data-aos="fade-up">
                            <img src="<?php echo $screen['image_bg']; ?>" alt="main-img">
                        </div>
                    </div>
                </section>
			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_2' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="essence" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="essence-wrap">
                            <div class="essence-media" data-aos="fade-up"><img src="<?php echo $screen['img']; ?>"
                                                                               alt="essence">
                                <button class="play btn-popup" aria-label="video popup" data-popup="video"
                                        data-video-poster="<?php echo $screen['img']; ?>"
                                        data-video-link="<?php echo $screen['video']; ?>"></button>
                            </div>
                            <div class="essence-desc" data-aos="fade-up">
                                <div class="subtitle-section"><?php echo $screen['subtitle']; ?></div>
                                <div class="title-section"><?php echo $screen['title']; ?></div>
                                <div class="text-section"><?php echo $screen['text']; ?></div>
                            </div>
                        </div>
                    </div>
                </section>
			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_3' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="decore <?php echo $screen['class']; ?>" id="<?php echo $screen['id']; ?>">
                    <div class="marquee marquee-reverce">
                        <span class="marquee-text"><?php if ( $marqueeList = $screen['list'] ) : foreach ( $marqueeList as $item ) : ?>
                                <span><?php echo $item['title']; ?></span><?php endforeach; endif; ?></span>
                        <span class="marquee-text"><?php if ( $marqueeList = $screen['list'] ) : foreach ( $marqueeList as $item ) : ?>
                                <span><?php echo $item['title']; ?></span><?php endforeach; endif; ?></span>
                    </div>
                    <div class="marquee">
                        <span class="marquee-text reverce"><?php if ( $marqueeList = $screen['list'] ) : foreach ( $marqueeList as $item ) : ?>
                                <span><?php echo $item['title']; ?></span><?php endforeach; endif; ?></span>
                        <span class="marquee-text reverce"><?php if ( $marqueeList = $screen['list'] ) : foreach ( $marqueeList as $item ) : ?>
                                <span><?php echo $item['title']; ?></span><?php endforeach; endif; ?></span>
                    </div>
                </section>
			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_4' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="info" <?php echo $screen['id']; ?>>
                    <div class="container">
                        <div class="info-wrap">
                            <div class="info-desc">
                                <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                                <div class="text-section" data-aos="fade-up"><?php echo $screen['text']; ?></div>
                                <div class="link-section" data-aos="fade-up"><a href="/tours"
                                                                                class="btn btn-yellow"><?php echo $screen['text_btn']; ?></a>
                                </div>
                            </div>
                            <div class="info-media">
								<?php if ( $sliderList = $screen['list'] ) : ?>
                                    <div class="info-slider" data-aos="fade-up">
										<?php foreach ( $sliderList as $item ) : ?>
                                            <div class="slide">
                                                <img src="<?php echo $item['image']; ?>" alt="slide">
                                            </div>
										<?php endforeach; ?>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_5' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="tour" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                        <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
						<?php if ( $blogs = $screen['tours'] ): ?>
                            <div class="items">
								<?php foreach ( $blogs as $blog ):
									$_id = $blog['id'];
									if ( get_post( $_id ) ):
										$_img = get_the_post_thumbnail_url( $_id ) ?: '';
										$terms = get_the_terms( $_id, 'category-tour' );
										$class_color = '';
										if ( $terms && ! is_wp_error( $terms ) ) {
											$first_term  = reset( $terms );
											$class_color = carbon_get_term_meta( $first_term->term_id, 'crb_class_color' );
										}
										?>
                                        <div class="item" data-aos="fade-up" data-aos-delay="200">
                                            <div class="item-media">
                                                <a href="<?php echo get_the_permalink( $_id ); ?>">
													<?php
													if ( $terms && ! is_wp_error( $terms ) ) {
														$term_names = wp_list_pluck( $terms, 'name' );
														echo '<span class="label ' . esc_attr( $class_color ) . '">' . implode( ', ', $term_names ) . '</span>';
													} else {
														echo '';
													}
													?>
                                                    <img src="<?php echo $_img; ?>" alt="Tour">
                                                </a>
                                            </div>
                                            <div class="item-desc">
                                                <div class="item-title"><a
                                                            href="<?php echo get_the_permalink( $_id ); ?>"><?php echo get_the_title( $_id ); ?></a>
                                                </div>
												<?php if ( $infoList = carbon_get_post_meta( $_id, 'list' ) ) : ?>
                                                    <ul class="item-list">
														<?php $infoDates = carbon_get_post_meta( $_id, 'list_date' );
														if ( ! empty( $infoDates ) ) : $firstDate = $infoDates[0]; ?>
                                                            <li>
                                                                <span><?php echo pll_e( 'Tour dates' ); ?></span>
                                                                <span><?php echo $firstDate['date_start'] ?> - <?php echo $firstDate['date_end'] ?></span>
                                                            </li>
														<?php endif; ?>
														<?php foreach ( $infoList as $item ) : ?>
                                                            <li>
                                                                <span><?php echo $item['text_start'] ?></span>
                                                                <span><?php echo $item['text_end'] ?></span>
                                                            </li>
														<?php endforeach; ?>
                                                    </ul>
												<?php endif; ?>
                                                <div class="item-bottom">

                                                    <span class="item-price"><?php echo carbon_get_post_meta( $_id, 'price' ); ?></span>
													<?php $new_price_old = carbon_get_post_meta( $_id, 'price_old' );
													if ( $new_price_old ) { ?>
                                                        <span class="item-price__old"><?php echo $new_price_old; ?></span>
													<?php } ?>
                                                    <a href="<?php echo get_the_permalink( $_id ); ?>"
                                                       class="item-book btn btn-red">Book</a>
                                                </div>
                                            </div>
                                        </div>
									<?php endif; endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php
			endif;

        elseif ( $screen['_type'] == 'screen_6' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="catalog" <?php echo $screen['id']; ?>>
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                            <div class="suptitle-section" data-aos="fade-up"><?php echo $screen['suptitle']; ?> </div>
                        </div>
                        <div class="catalog-wrap">
							<?php if ( $catalogs = $screen['catalog'] ): ?>
								<?php foreach ( $catalogs as $item ): ?>
									<?php
									$term_id = $item['id'];
									$term    = get_term( $term_id, 'category-tour' );
									if ( ! is_wp_error( $term ) && $term ) {
										$class_color = carbon_get_term_meta( $term_id, 'crb_class_color' );
										$price       = carbon_get_term_meta( $term_id, 'crb_title_price' );
										$list        = carbon_get_term_meta( $term_id, 'list' );
										$imageTerm   = carbon_get_term_meta( $term_id, 'crb_image' );
										$desc        = $term->description;
										?>
                                        <div class="catalog-item" data-aos="fade-up">
                                            <div class="catalog-item__media">
                                                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><img
                                                            src="<?php echo esc_html( $imageTerm ); ?>"
                                                            alt="catalog"></a>
                                            </div>
                                            <div class="catalog-item__desc">
                                                <div class="catalog-item__title">
													<?php echo esc_html( $term->name ); ?>
                                                </div>
												<?php if ( $list && is_array( $list ) ): ?>
                                                    <ul class="catalog-item__list">
														<?php foreach ( $list as $list_item ): ?>
                                                            <li>
                                                                <span><?php echo esc_html( $list_item['title_start'] ); ?></span>
                                                                <span><?php echo esc_html( $list_item['title_end'] ); ?></span>
                                                            </li>
														<?php endforeach; ?>
                                                    </ul>
												<?php else: ?>
												<?php endif; ?>
												<?php if ( $desc ) {
													echo '<div class="text-section" style="margin-bottom: 10px; color:unset;">' . _t( $desc, 1 ) . '</div>';
												} ?>

                                                <div class="catalog-item__bottom">
                                                    <span class="catalog-item__price"><?php if ( $price ): ?><?php echo esc_html( $price ); ?><?php endif; ?></span>
                                                    <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
                                                       class="btn btn-black"><?php echo pll_e( 'Find out more' ); ?></a>
                                                </div>
                                            </div>
                                        </div>
									<?php } ?>
								<?php endforeach; ?>

							<?php else: ?>
                                <!-- <p>Категорії не знайдені.</p> -->
							<?php endif; ?>
                        </div>
						<?php
						$button_text = $screen['button_text'];
						$button_url  = $screen['button_url'];
						if ( $button_url && $button_text ) :
							?>
                            <div class="section-link">
                                <a href="<?php echo esc_url( $button_url ); ?>" class="link-line"
                                   data-aos="fade-up"><?php echo esc_html( $button_text ); ?></a>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_7' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="benefits" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="benefits-wrap">
                            <div class="benefits-desc">
                                <div class="subtitle-section"
                                     data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                                <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                                <div class="text-section" data-aos="fade-up"><?php echo $screen['text']; ?></div>
                            </div>
							<?php if ( $heroList = $screen['list'] ) : ?>
                                <div class="benefits-inner">
									<?php foreach ( $heroList as $item ) : ?>
                                        <div class="benefits-item" data-aos="fade-up">
                                            <div class="benefits-item__title"><?php echo $item['title'] ?></div>
                                            <div class="benefits-item__text"><?php echo $item['text'] ?></div>
                                        </div>
									<?php endforeach; ?>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </section>
			<?php endif;

        elseif ( $screen['_type'] == 'screen_8' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="banner" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="banner-item <?php echo $screen['class']; ?>" data-aos="fade-up">
                            <div class="banner-desc">
                                <div class="title-section"><?php echo $screen['title']; ?></div>
                                <div class="text-section"><?php echo $screen['text']; ?></div>
                                <div class="banner-link">
                                    <a href="<?php echo $screen['link_btn']; ?>"
                                       class="btn btn-red"><?php echo $screen['text_btn']; ?></a>
                                </div>
                            </div>
                            <div class="banner-media">
                                <img src="<?php echo $screen['image']; ?>" alt="banner">
                            </div>
                        </div>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_9' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="rental" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                            <div class="suptitle-section" data-aos="fade-up"><?php echo $screen['text']; ?></div>
                        </div>
						<?php if ( $blogs = $screen['list'] ): ?>
                            <div class="rental-wrap">
								<?php foreach ( $blogs as $blog ): ?>
                                    <div class="rental-item" data-aos="fade-up">
                                        <div class="rental-item__top">
                                            <div class="rental-item__title"><?php echo $blog['title']; ?></div>
                                            <div class="rental-item__price"><?php echo $blog['price']; ?></div>
                                        </div>
                                        <div class="rental-item__info">
											<?php echo $blog['text']; ?>
                                        </div>
                                        <div class="rental-item__media"><img src="<?php echo $blog['image']; ?>"
                                                                             alt="rental"></div>
                                        <div class="rental-item__button"><a href="<?php echo $blog['link']; ?>"
                                                                            class="btn btn-black"><?php echo pll_e( 'Find out more' ); ?></a>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_10' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="equipment" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-block" data-aos="fade-up"><?php echo $screen['title']; ?></div>
						<?php if ( $catalogs = $screen['catalog'] ): ?>
                            <div class="items">
							<?php foreach ( $catalogs as $item ): ?>
								<?php
								$term_id = $item['id'];
								$term    = get_term( $term_id, 'additional-equipment' );
								if ( ! is_wp_error( $term ) && $term ) {
									$imageTerm = carbon_get_term_meta( $term_id, 'crb_image' );
									?>
                                    <div class="item" data-aos="fade-up" data-aos-delay="200">
                                        <div class="item-media">
                                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><img
                                                        src="<?php echo esc_html( $imageTerm ); ?>" alt="catalog"></a>
                                        </div>
                                        <div class="item-desc">
                                            <div class="item-title"><a
                                                        href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
                                            </div>
                                            <div class="item-text"><?php echo esc_html( $term->description ); ?></div>
                                            <div class="item-bottom">
                                                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
                                                   class="item-book btn btn-red"><?php echo pll_e( 'Choose' ); ?></a>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
							<?php endforeach; ?>

						<?php else: ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>

			<?php endif;
        elseif ( $screen['_type'] == 'screen_10_1' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="equipment" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-block" data-aos="fade-up"><?php echo $screen['title']; ?></div>
						<?php if ( $catalogs = $screen['catalog'] ): ?>
                            <div class="items">
							<?php foreach ( $catalogs as $item ): ?>
								<?php
								$_id  = $item['id'];
								$post = get_post( $_id );
								if ( ! is_wp_error( $post ) && $post ) {
									$imageTerm = get_the_post_thumbnail_url( $_id ) ?: $assets . 'img/equipment-1.png';
									?>
                                    <div class="item" data-aos="fade-up" data-aos-delay="200">
                                        <div class="item-media">
                                            <a href="<?php echo esc_url( get_the_permalink( $_id ) ); ?>">
                                                <img src="<?php echo esc_url( $imageTerm ); ?>"
                                                     loading="lazy"
                                                     alt="catalog">
                                            </a>
                                        </div>
                                        <div class="item-desc">
                                            <div class="item-title">
                                                <a href="<?php echo esc_url( get_the_permalink( $_id ) ); ?>">
													<?php echo esc_html( get_the_title( $_id ) ); ?>
                                                </a>
                                            </div>
											<?php if ( $infoList = carbon_get_post_meta( $_id, 'list' ) ) : ?>
                                                <ul class="item-list">
													<?php foreach ( $infoList as $_item ) : ?>
                                                        <li>
                                                            <span><?php echo $_item['text_start'] ?></span>
                                                            <span><?php echo $_item['text_end'] ?></span>
                                                        </li>
													<?php endforeach; ?>
                                                </ul>
											<?php endif; ?>
                                            <div class="item-bottom">
                                                <a href="<?php echo esc_url( get_the_permalink( $_id ) ); ?>"
                                                   class="item-book btn btn-red"><?php echo pll_e( 'Choose' ); ?></a>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
							<?php endforeach; ?>

						<?php else: ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_11' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="safety" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="safety-wrap">
                            <div class="safety-desc" data-aos="fade-up">
                                <div class="safety-inner">
                                    <div class="subtitle-section"><?php echo $screen['subtitle']; ?></div>
                                    <div class="title-section"><?php echo $screen['title']; ?></div>
                                    <div class="text-section"><?php echo $screen['text']; ?></div>
                                </div>
                            </div>
							<?php if ( $heroList = $screen['list'] ) : ?>
                                <div class="safety-media">
                                    <div class="safety-slider" data-aos="fade-up">
										<?php foreach ( $heroList as $item ) : ?>
                                            <div class="slide">
                                                <img src="<?php echo $item['image'] ?>" alt="safety">
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_12' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="advantages" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                        </div>
						<?php if ( $heroList = $screen['list'] ) : $i = 2; ?>
                            <div class="advantages-wrap">
								<?php foreach ( $heroList as $item ) : ?>
                                    <div class="advantages-item" data-aos="fade-up"
                                         data-aos-delay="<?php echo $i; ?>00">
                                        <div class="advantages-item__title"><?php echo $item['title'] ?></div>
                                        <div class="advantages-item__text"><?php echo $item['text'] ?></div>
                                    </div>
									<?php $i ++; endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_13' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="about" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="about-wrap">
                            <div class="about-media" data-aos="fade-up">
                                <img src="<?php echo $screen['image']; ?>" alt="about-img">
                            </div>
                            <div class="about-desc">
                                <div class="subtitle-section"
                                     data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                                <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                                <div class="text-section" data-aos="fade-up"><?php echo $screen['text']; ?></div>
								<?php if ( $screen['btn_url'] ): ?>
                                    <div class="link-section" data-aos="fade-up">
                                        <a href="<?php echo esc_url( $screen['btn_url'] ); ?>"
                                           class="btn btn-yellow"><?php echo esc_html( $screen['btn_text'] ); ?></a>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_14' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="services" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                        </div>
						<?php if ( $heroList = $screen['list'] ) : ?>
                            <div class="services-wrap">
								<?php foreach ( $heroList as $item ) : ?>
                                    <div class="services-item" data-aos="fade-up">
                                        <div class="services-item__title"><h4><?php echo $item['title'] ?></h4>
                                            <span><?php echo $item['label'] ?></span>
                                        </div>
                                        <div class="text-section"><?php echo $item['text'] ?></div>
                                    </div>
								<?php endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_15' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="suitable" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?>  </div>
                            <div class="suptitle-section" data-aos="fade-up"><?php echo $screen['text']; ?></div>
                        </div>
						<?php if ( $heroList = $screen['list'] ) : $int = 1; ?>
                            <div class="suitable-wrap">
								<?php foreach ( $heroList as $item ) : ?>
                                    <div class="suitable-item" data-aos="fade-up"
                                         data-aos-delay="<?php echo $int; ?>00">
                                        <div class="suitable-item__title"><?php echo $item['title']; ?></div>
                                        <div class="suitable-item__text"><?php echo $item['text_item']; ?></div>
                                        <div class="suitable-item__button">
                                            <a href="/tours" class="btn btn-red"><?php echo $item['btn_text']; ?></a>
                                        </div>
                                    </div>
									<?php $int ++; endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_16' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="reviews" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                            <div class="suptitle-section" data-aos="fade-up"><a
                                        href="<?php echo $screen['link_btn']; ?>"
                                        class="btn btn-yellow"><?php echo $screen['text_btn']; ?></a></div>
                        </div>
                        <div class="reviews-wrap">
							<?php if ( $lists = $screen['list'] ) : ?>
                                <div class="reviews-info" data-aos="fade-up">
                                    <div class="reviews-slider">
										<?php foreach ( $lists as $item ) : ?>
                                            <div class="slide">
                                                <div class="reviews-title"><?php echo $item['title']; ?></div>
                                                <div class="text-section"><?php echo $item['text']; ?></div>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
							<?php endif; ?>
							<?php if ( $lists = $screen['list'] ) : ?>
                                <div class="reviews-video" data-aos="fade-up">
									<?php foreach ( $lists as $item ) : ?>
                                        <div class="slide"><img src="<?php echo $item['image']; ?>" alt="video">
                                            <button class="play btn-popup" aria-label="video popup" data-popup="video"
                                                    data-video-link="<?php echo $item['video']; ?>"></button>
                                        </div>
									<?php endforeach; ?>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_17' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="audio" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-block" data-aos="fade-up"><?php echo $screen['title']; ?></div>
						<?php if ( $lists = $screen['list'] ) : $intAudio = 1; ?>
                            <div class="audio-wrap">
								<?php foreach ( $lists as $item ) : ?>
                                    <div class="audio-item" data-aos="fade-up" data-aos-delay="100">
                                        <div class="audio-media">
                                            <div class="audio-image">
                                                <img src="<?php echo $item['image']; ?>" alt="audio">
                                            </div>
                                            <button class="play-btn" aria-label="video play" type="button">
                                                <span><img src="<?php echo get_template_directory_uri(); ?>/img/play-btn.svg"
                                                           alt="audio"></span>
                                                <span class="hidden"><img
                                                            src="<?php echo get_template_directory_uri(); ?>/img/pause-btn.svg"
                                                            alt="audio"></span>
                                            </button>
                                        </div>
                                        <div class="audio-desc">
                                            <div class="audio-title"><?php echo $item['title']; ?></div>
                                            <div class="audio-player">
                                                <audio class="lazy-audio" data-src="<?php echo $item['audio']; ?>"
                                                       controls preload="none"></audio>
                                                <div class="progress-container">
                                                    <div class="progress-bar-background"><img
                                                                src="<?php echo get_template_directory_uri(); ?>/img/audio.svg"
                                                                class="svg-convert" alt="audio"></div>
                                                    <div class="progress-bar" style="width: 0;"></div>
                                                    <input type="range" id="<?php echo $item['title'];
													echo $intAudio; ?>" class="seek-bar" value="0" step="1" min="0">
                                                    <label for="<?php echo $item['title'];
													echo $intAudio; ?>"></label>
                                                </div>
                                                <div class="time-display">
                                                    <span class="current-time">0:00</span>
                                                    /
                                                    <span class="duration-time">0:00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<?php $intAudio ++; endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>
			<?php endif;
        elseif ( $screen['_type'] == 'screen_18' ) :
			if ( ! $screen['screen_off'] ) : ?>
                <section class="faq" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="faq-wrap">
                            <div class="faq-desc">
                                <div class="subtitle-section"
                                     data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                                <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                                <div class="link-section" data-aos="fade-up"><a href="#"
                                                                                class="btn btn-yellow btn-popup"
                                                                                data-popup="info"><?php echo $screen['text_btn']; ?></a>
                                </div>
                            </div>
							<?php if ( $lists = $screen['list'] ) : ?>
                                <div class="faq-inner">
									<?php foreach ( $lists as $item ) : ?>
                                        <div class="faq-item" data-aos="fade-up">
                                            <div class="faq-item__title"><?php echo $item['title']; ?></div>
                                            <div class="faq-item__text"><?php echo $item['text']; ?></div>
                                        </div>
									<?php endforeach; ?>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </section>

			<?php
			endif;

		endif;
	endforeach;
endif;
?>

<?php get_footer(); ?>
<script>
    document.cookie = "page_type=; path=/; max-age=0; SameSite=Strict";
</script>