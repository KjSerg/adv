<?php get_header(); ?>
<?php
$terms = get_the_terms($id, 'category-tour');
$var          = variables();
$set          = $var['setting_home'];
$assets       = $var['assets'];
$url          = $var['url'];
$url_home     = $var['url_home'];
$id           = get_the_ID();
$size         = $isLighthouse ? 'thumbnail' : 'full';
$screens      = carbon_get_post_meta($id, 'bike_screens');
$new_price = carbon_get_post_meta($id, 'new_price');
$new_price_old = carbon_get_post_meta($id, 'new_price_old');
$new_list_info = carbon_get_post_meta($id, 'list_info');
$new_list = carbon_get_post_meta($id, 'list');
$bike_stock = get_post_meta($post->ID, '_bike_stock', true);
$bike_price = get_post_meta($post->ID, '_bike_price', true);
$bike_price_old = get_post_meta($post->ID, '_bike_price_old', true);
$translated_post_id = get_the_ID(); 
$original_post_id = pll_get_post($translated_post_id, 'en');
?>
<section class="bread-crumbs">
    <div class="container">
        <ul data-aos="fade-up" data-aos-delay="100">
            <li><a href="<?php echo $url; ?>"><?php echo pll_e('Main');?></a></li>
            <li><a href="/motorcycle"><?php echo pll_e('Motorcycle');?></a></li>
            <li><?php echo the_title();?></li>
        </ul>
    </div>
</section>
<?php if (!empty($screens)) :
    foreach ($screens as $index => $screen) :
        $index = $index + 1;
        if ($screen['_type'] == 'screen_1') :
            if (!$screen['screen_off']) : ?>
                <section class="tour-card" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="tour-media" data-aos="fade-up" data-aos-delay="100">
                            <?php foreach ($terms as $term) { $class_color = carbon_get_term_meta($term->term_id, 'crb_class_color'); ?>
                                <span class="label <?php echo $class_color; ?>"><?php echo  $term->name; ?></span>
                            <?php } ?>
                            <?php if ($galleryList = $screen['gallery']) : ?>
                                <div class="tour-for">
                                    <?php foreach ($galleryList as $item) : ?><div class="slide"><img src="<?php echo $item['image'] ?>" alt="slide"></div><?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($galleryList = $screen['gallery']) : ?>
                                <div class="tour-nav">
                                    <?php foreach ($galleryList as $item) : ?>
                                        <div class="slide"><div class="slide-image"><img src="<?php echo $item['image'] ?>" alt="slide"></div></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tour-desc" data-aos="fade-up" data-aos-delay="100">
                            <div class="tour-desc__title"><?php echo the_title(); ?></div>
                            <?php if ($infoList = carbon_get_post_meta($id, 'list')) : ?>
                                <div class="tour-desc__list">
                                    <ul>
                                        <li><span><?php echo pll_e('In stock');?></span><span><?php echo $bike_stock; ?></span></li>
                                        <?php foreach ($infoList as $item) : ?>
                                            <li><span><?php echo $item['text_start']; ?></span><span><?php echo $item['text_end'];?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if ($informList = carbon_get_post_meta($id, 'list_info')) : $i = 1; ?>
                                <div class="tour-accordion">
                                    <?php foreach ($informList as $item) : ?>
                                        <div class="accordion-item <?php if ($i === 1) { echo 'active'; } ?>">
                                            <div class="accordion-item__title"><?php echo $item['title'] ?></div>
                                            <div class="accordion-item__text"><?php echo $item['text'] ?></div>
                                        </div>
                                    <?php $i++;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="tour-bottom">
                                <span class="item-price__info"><?php echo pll_e('Basу rent a day');?></span>
                                <span class="item-price" data-base-price="<?php echo $bike_price;?>"><?php echo $bike_price;?></span>
                                <?php if ($bike_price_old) { ?>
                                    <span class="item-price__old"><?php echo $bike_price_old; ?></span>
                                <?php } ?>
                                <?php
                                    $page_id = '931';
                                    $translated_id = pll_get_post($page_id);    
                                ?>
                                <a href="#" data-href="<?php if ($translated_id) {$translated_url = get_permalink($translated_id);echo $translated_url;} else {$original_url = get_permalink($page_id); echo $original_url;}?>"
                                 data-id="<?php echo $original_post_id;?>" 
                                 data-translated="<?php  echo $translated_post_id;?>" 
                                 data-title="<?php echo the_title(); ?>"
                                  data-moto-id='' data-tour-id='' data-dates-start='' data-total-days='' data-dates-booking='' data-dates-end='' class="btn btn-red btn-book"><?php echo pll_e('Book');?></a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="tour-calendar">
				<div class="container">
					<div class="booking-calendar">
						<div class="calendar">
							<button id="prev" class="calendar-prev calendar-button"></button>
							<div class="month" id="current-month"></div>
							<div class="month" id="next-month"></div>
							<button id="next" class="calendar-next calendar-button"></button>
						</div>
					</div>
                    <div class="booking-calendar__info text-section"><?php echo pll_e('info calendar bike');?></div>
				</div>
			</section>
                <?php endif;
        elseif ($screen['_type'] == 'screen_2') :
            if (!$screen['screen_off']) : ?>
            <section class="other-section" id="<?php echo $screen['id']; ?>">
				<div class="container">
					<div class="top-section">
						<div class="title-section"><?php echo $screen['title']; ?></div>
						<div class="btn-blog hidden">
							<a href="#" class="btn btn-red"><?php echo $screen['text_btn']; ?></a>
						</div>
					</div>
                    <?php if ( $blogs = $screen['bike'] ): ?>
                    <div class="blog-slider">
                        <?php foreach ( $blogs as $blog ): 
                            $_id = $blog['id']; 
                            if ( get_post( $_id ) ): 
                                $_img = get_the_post_thumbnail_url( $_id ) ?: '';
                                ?>
                                <div class="slide">
                                <div class="item" data-aos="fade-up">
                                    <div class="item-media">
                                        <a href="<?php echo get_the_permalink( $_id ); ?>">
                                            <img src="<?php echo $_img; ?>" alt="Tour">
                                        </a>
                                    </div>
                                    <div class="item-desc">
                                        <div class="item-title"><a href="<?php echo get_the_permalink( $_id ); ?>"><?php echo get_the_title( $_id ); ?></a></div>
                                        <?php
                                            $screens_tours = carbon_get_post_meta( $_id, 'bike_screens' );
                                            if (!empty($screens_tours)) :
                                                foreach ($screens_tours as $index => $screens_tour) :
                                                    $index = $index + 1;
                                                    if ($screens_tour['_type'] == 'screen_1') :
                                                        if (!$screens_tour['screen_off']) : ?>
                                                        <?php if ($infoList = $screens_tour['list']) : ?>
                                                            <ul class="item-list">
                                                            <?php foreach ($infoList as $item) : ?>
                                                                <li><span><?php echo $item['text_start']?></span><span><?php echo $item['text_end']?></span></li>
                                                            <?php  endforeach;?>
                                                            </ul>
                                                        <?php endif; ?>
                                                        <div class="item-bottom">               
                                                            <?php 
                                                            $bike_stock = get_post_meta($_id, '_bike_stock', true);
                                                            $bike_price = get_post_meta($_id, '_bike_price', true);
                                                            $bike_price_old = get_post_meta($_id, '_bike_price_old', true);                                         
                                                            ?>
                                                            <span class="item-price__info"><?php echo pll_e('Basу rent a day');?></span>
                                                            <span class="item-price"><?php echo $bike_price; ?></span>
                                                            <?php $oldPrice =  $bike_price_old;?>
                                                            <?php if($oldPrice){?>
                                                            <span class="item-price__old"><?php echo $bike_price_old;?></span>
                                                            <?php }?>
                                                            <a href="<?php echo get_the_permalink( $_id ); ?>" class="item-book btn btn-red"><?php echo pll_e('View');?></a>
                                                        </div>
                                                        <?php endif?>
                                                    <?php endif?>
                                                <?php endforeach?>
                                                <?php endif?>
                                    </div>
                                </div>
                                </div>
                            <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>
				</div>
			</section>
            <?php endif;
        elseif ($screen['_type'] == 'screen_3') :
            if (!$screen['screen_off']) : ?>
            <div class="hidden moto-id">
                    <?php if ( $blogs = $screen['equipment'] ): ?>
                        <ul>
                        <?php foreach ( $blogs as $blog ):  $_id = $blog['id']; 
                            if ( get_post( $_id ) ): ?>
                                    <li><?php echo $_id;?></li>
                            <?php endif;?>
                        <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                    </div>
        <?php
            endif;
        endif;
    endforeach;
endif;
?>
<div class="hidden">
    <div class="rentfor"><?php echo pll_e('Rent for');?></div>
    <div class="rentdays"><?php echo pll_e('Days');?></div>
</div>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/single-bike.js"></script>