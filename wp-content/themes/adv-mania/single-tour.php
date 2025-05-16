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
$screens      = carbon_get_post_meta($id, 'tour_screens');
$new_price = carbon_get_post_meta($id, 'price');
$new_price_old = carbon_get_post_meta($id, 'price_old');

$translated_post_id = get_the_ID(); 
$original_post_id = pll_get_post($translated_post_id, 'en');

?>
<section class="bread-crumbs">
    <div class="container">
        <ul data-aos="fade-up" data-aos-delay="100">
            <li><a href="<?php echo $url; ?>"><?php echo pll_e('Main');?></a></li>
            <li><a href="/tours"><?php echo pll_e('Tours');?></a></li>
	        <?php if($terms) echo "<li>".$terms[0]->name."</li>" ?>
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
                            <?php foreach ($terms as $term) {
                                $class_color = carbon_get_term_meta($term->term_id, 'crb_class_color'); ?>
                                <span class="label <?php echo $class_color; ?>"><?php echo  $term->name; ?></span>
                            <?php } ?>
                            <?php
                                $termsType = get_the_terms($id, 'tour-type');
                                foreach ($termsType as $term) { ?>
                                <span class="label label-right label-red"><?php echo  $term->name; ?></span>
                            <?php } ?>
                            <?php if ($galleryList = $screen['gallery']) : ?>
                                <div class="tour-for">
                                    <?php foreach ($galleryList as $item) : ?><div class="slide"><img src="<?php echo $item['image'] ?>" alt="slide"></div><?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($galleryList = $screen['gallery']) : ?>
                                <div class="tour-nav">
                                    <?php foreach ($galleryList as $item) : ?><div class="slide">
                                            <div class="slide-image"><img src="<?php echo $item['image'] ?>" alt="slide"></div>
                                        </div><?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tour-desc" data-aos="fade-up" data-aos-delay="100">
                            <div class="tour-desc__title"><?php echo the_title(); ?></div>
                            
                            <?php if ($infoList = carbon_get_post_meta($id, 'list')) : ?>
                                <div class="tour-desc__list">
                                    <ul>
                                        <?php foreach ($infoList as $item) : ?>
                                            <li><span><?php echo $item['text_start'] ?></span><span><?php echo $item['text_end'] ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if ($informList = carbon_get_post_meta($id, 'list_date')) : ?>
                                <div class="tour-accordion">
                                    <div class="accordion-item active tour-accordion-first">
                                        <div class="accordion-item__title"><?php echo pll_e('Tour dates');?></div>
                                        <div class="accordion-item__text">
                                            <ul class="tour-desc__list">
                                            <?php  
                                                $infoDates = carbon_get_post_meta($id, 'list_date'); 
                                                $pll_eTourDates = pll__('Tour dates'); 
                                                $ids = 1;
                                                $currentDate = new DateTime(); // Поточна дата
                                                foreach ($infoDates as $item) :
                                                    $repeat_monthly = $item['repeat_monthly'];
                                                    $repeat_count = $item['repeat_count'];
                                                    $start_date = new DateTime($item['date_start']);
                                                    $end_date = new DateTime($item['date_end']);
                                                    if ($start_date < $currentDate) {
                                                        continue; 
                                                    }
                                                    $hiddenClass = ($ids > 1) ? ' hidden' : '';
                                                    echo '<li class="date-item"><span>' . $pll_eTourDates . '</span> <span>' . $start_date->format('Y-m-d') . ' - ' . $end_date->format('Y-m-d') . '</span></li>';
                                                    if ($repeat_monthly && is_numeric($repeat_count) && $repeat_count > 1) {
                                                        for ($i = 1; $i < $repeat_count; $i++) {
                                                            $start_date->modify('+1 month');
                                                            $end_date->modify('+1 month');
                                                            if ($start_date < $currentDate) {
                                                                continue;
                                                            }
                                                            $hiddenClass = ' hidden';
                                                            echo '<li class="date-item"><span>' . $pll_eTourDates . '</span> <span>' . $start_date->format('Y-m-d') . ' - ' . $end_date->format('Y-m-d') . '</span></li>';
                                                        }
                                                    }
                                                    $ids++;
                                                endforeach;
                                            ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($informList = carbon_get_post_meta($id, 'list_info')) : ?>
                                <div class="tour-accordion">
                                    <?php foreach ($informList as $item) : ?>
                                        <div class="accordion-item">
                                            <div class="accordion-item__title"><?php echo $item['title'] ?></div>
                                            <div class="accordion-item__text"><?php echo _t($item['text']); ?></div>
                                        </div>
                                    <?php
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="tour-bottom">
                                <span class="item-price__info"><?php echo pll_e('From');?></span>
                                <span class="item-price"><?php echo $new_price; ?></span>
                                <?php if ($new_price_old) { ?>
                                    <span class="item-price__old"><?php echo $new_price_old; ?></span>
                                <?php } ?>
                                

                                
                                <?php if(!empty($termsType)): ?>
                                    <?php
                                        $page_id = '931';
                                        $translated_id = pll_get_post($page_id);?>
                                    <a href="#" data-href="<?php if ($translated_id) {$translated_url = get_permalink($translated_id);echo $translated_url;} else {$original_url = get_permalink($page_id); echo $original_url;}?>"
                                    data-id="<?php echo $original_post_id;?>" 
                                    data-translated="<?php  echo $translated_post_id;?>" 
                                    data-title="<?php echo the_title(); ?>"
                                    data-moto-id='' data-tour-id='' data-percent-start='<?php echo $bike_price_percent;?>' data-dates-start='' data-total-days='' data-dates-booking='' data-dates-end='' class="btn btn-red btn-book"><?php echo pll_e('Book');?></a>
                                <?php else : ?>
                                    <?php $page_id = '98'; $translated_id = pll_get_post($page_id); ?>
                                    <a href="<?php if ($translated_id) {$translated_url = get_permalink($translated_id);echo $translated_url;} else {$original_url = get_permalink($page_id); echo $original_url;}?>" data-id="<?php echo $original_post_id;?>" data-translated="<?php  echo $translated_post_id;?>" data-title="<?php echo the_title(); ?>" data-price='' data-moto-id='' data-tour-id='' class="btn btn-red btn-book"><?php echo pll_e('Book a tour');?></a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php if(!empty($termsType)): ?>
                
                <section class="tour-calendar ">
                    <div class="container">
                        <div class="booking-calendar one-day-calendar">
                            <div class="calendar">
                                <button id="prev" class="calendar-prev calendar-button"></button>
                                <div class="month" id="current-month"></div>
                                <div class="month" id="next-month"></div>
                                <button id="next" class="calendar-next calendar-button"></button>
                            </div>
                        </div>
                        <div class="booking-calendar__info text-section"><?php echo pll_e('info calendar single tour');?></div>
                    </div>
                </section>
                <?php endif; ?>
            <?php endif;
        elseif ($screen['_type'] == 'screen_2') :
            if (!$screen['screen_off']) : ?>
                <section class="sction-map" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section"><?php echo $screen['title']; ?></div>
                            <div class="suptitle-section"><?php echo $screen['text']; ?></div>
                        </div>
                        <?php if ($list = $screen['list']) : $i = 0; ?>
                            <div class="map-wrap">
                                <div class="map-inner" data-map-center="<?php echo $screen['text_center_map']; ?>">
                                    <div id="map" class="map-item"></div>
                                </div>
                                <div class="map-items">
                                    <?php foreach ($list as $item) : ?>
                                        <div class="item route-btn" data-index="<?php echo $i; ?>" data-lat="<?php echo $item['lat'] ?>" data-lng="<?php echo $item['lng'] ?>">
                                            <h5><?php echo $item['title'] ?></h5>
                                            <?php echo _t($item['text']); ?>
                                        </div>
                                    <?php $i++;
                                    endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif;
        elseif ($screen['_type'] == 'screen_3') :
            if (!$screen['screen_off']) : ?>
            <section class="other-section <?php if(!empty($termsType)){echo 'hidden';} ?> " id="<?php echo $screen['id']; ?>">
				<div class="container">
					<div class="top-section">
						<div class="title-section"><?php echo $screen['title']; ?></div>
						<div class="btn-blog hidden">
							<a href="#" class="btn btn-red"><?php echo $screen  ['text_btn']; ?></a>
						</div>
					</div>
                    <?php if ( $blogs = $screen['bike'] ): ?>
                    <div class="items">
                        <?php foreach ( $blogs as $blog ): 
                            $_id = $blog['id']; 
                            if ( get_post( $_id ) ): 
                                $_img = get_the_post_thumbnail_url( $_id ) ?: '';
                                ?>
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
                                                        <?php
                                                        $bike_stock = get_post_meta($_id, '_bike_stock', true);
                                                        $bike_price = get_post_meta($_id, '_bike_price', true);
                                                        $bike_price_old = get_post_meta($_id, '_bike_price_old', true);
                                                        if ($infoList = carbon_get_post_meta($_id, 'list')) :
                                                            ?>
                                                            <ul class="item-list">
                                                                <li><span><?php echo pll_e('In stock');?></span><span><?php echo $bike_stock;?></span></li>
                                                            <?php foreach ($infoList as $item) : ?>
                                                                <li><span><?php echo $item['text_start']?></span><span><?php echo $item['text_end']?></span></li>
                                                            <?php  endforeach;?>
                                                            </ul>
                                                        <?php endif; ?>
                                                        <div class="item-bottom">                                                            
                                                            <span class="item-price__info"><?php echo pll_e('Rent on day');?></span>
                                                            <span class="item-price"><?php echo $bike_price; ?></span>
                                                            <?php if($bike_price_old){?>
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
                            <?php endif; endforeach; ?>
                    </div>
                    <div class="hidden moto-id">
                    <?php if ( $blogs = $screen['bike'] ): ?>
                        <ul>
                        <?php foreach ( $blogs as $blog ):  $_id = $blog['id']; 
                            if ( get_post( $_id ) ): ?>
                                    <li><?php echo $_id;?></li>
                            <?php endif;?>
                        <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                    </div>
                <?php endif; ?>
				</div>
			</section>
            <?php endif;
        elseif ($screen['_type'] == 'screen_7') :
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
				
			</section>
            <?php endif;
            elseif ($screen['_type'] == 'screen_4') :
                if (!$screen['screen_off']) : ?>
                <section class="faq" id="<?php echo $screen['id']; ?>">
				<div class="container">
					<div class="faq-wrap">
						<div class="faq-desc">
							<div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
							<div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
							<div class="link-section" data-aos="fade-up"><a href="#" class="btn btn-yellow"><?php echo $screen['text_btn']; ?></a></div>
						</div>
                        <?php if ($lists = $screen['list']) :  ?>    
						<div class="faq-inner">
						    <?php foreach ($lists as $item) : ?>	
                            <div class="faq-item" data-aos="fade-up">
								<div class="faq-item__title"><?php echo $item['title']; ?></div>
								<div class="faq-item__text"><?php echo $item['text']; ?></div>
							</div>
                            <?php endforeach;?>
						</div>
                        <?php endif; ?>
					</div>
				</div>
			</section>
            <?php endif;
        elseif ($screen['_type'] == 'screen_5') :
            if (!$screen['screen_off']) : ?>
                <section class="reviews" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="top-section">
                            <div class="subtitle-section" data-aos="fade-up"><?php echo $screen['subtitle']; ?></div>
                            <div class="title-section" data-aos="fade-up"><?php echo $screen['title']; ?></div>
                            <div class="suptitle-section" data-aos="fade-up"><a href="<?php echo $screen['link_btn']; ?>" class="btn btn-yellow"><?php echo $screen['text_btn']; ?></a></div>
                        </div>
                        <div class="reviews-wrap">
                            <?php if ($lists = $screen['list']) :  ?>
                                <div class="reviews-info" data-aos="fade-up">
                                    <div class="reviews-slider">
                                        <?php foreach ($lists as $item) : ?>
                                            <div class="slide">
                                                <div class="reviews-title"><?php echo $item['title']; ?></div>
                                                <div class="text-section"><?php echo $item['text']; ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lists = $screen['list']) :  ?>
                                <div class="reviews-video" data-aos="fade-up">
                                    <?php foreach ($lists as $item) : ?>
                                        <div class="slide"><img src="<?php echo $item['image']; ?>" alt="video"><button class="play btn-popup" data-popup="video" data-video-link="<?php echo $item['video']; ?>"></button></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif;
            elseif ($screen['_type'] == 'screen_6') :
            if (!$screen['screen_off']) : ?>
            <section class="other-section" id="<?php echo $screen['id']; ?>">
                <div class="container">
					<div class="top-section">
						<div class="title-section"><?php echo $screen['title']; ?></div>
						<div class="btn-blog">
							<a href="/tours" class="btn btn-red"><?php echo $screen['text']; ?></a>
						</div>
					</div>
                    <?php if ($blogs = $screen['equipment']): ?>
                        
					<div class="blog-slider">
                    <?php foreach ( $blogs as $blog ): 
                            $_id = $blog['id']; 
                            if ( get_post( $_id ) ): 
                                $_img = get_the_post_thumbnail_url( $_id ) ?: '';
                                $terms = get_the_terms( $_id, 'category-tour' );
                                $class_color = '';
                                if ($terms && ! is_wp_error($terms)) {
                                    $first_term = reset($terms);
                                    $class_color = carbon_get_term_meta($first_term->term_id, 'crb_class_color');
                                }
                                ?>
                                <div class="slide">
                                <div class="item" >
                                    <div class="item-media">
                                        <a href="<?php echo get_the_permalink( $_id ); ?>">
                                            <?php 
                                            if ($terms && ! is_wp_error($terms)) {
                                                $term_names = wp_list_pluck($terms, 'name');
                                                echo '<span class="label ' . esc_attr($class_color) . '">' . implode(', ', $term_names) . '</span>';
                                            } else {
                                                echo ''; 
                                            }
                                            ?>
                                            <img src="<?php echo $_img; ?>" alt="Tour">
                                        </a>
                                    </div>
                                    <div class="item-desc">
                                        <div class="item-title"><a href="<?php echo get_the_permalink( $_id ); ?>"><?php echo get_the_title( $_id ); ?></a></div>
                                        <?php if ($infoList = carbon_get_post_meta($_id, 'list')) : ?>
                                            <ul class="item-list">
                                            <?php  $infoDates = carbon_get_post_meta($_id, 'list_date'); 
                                                if (!empty($infoDates)) :  $firstDate = $infoDates[0];  ?>
                                                <li><span><?php echo pll_e('Tour dates'); ?></span><span><?php echo $firstDate['date_start'] ?> - <?php echo $firstDate['date_end'] ?></span></li>
                                            <?php  endif; ?>
                                            <?php foreach ($infoList as $item) : ?>
                                                <li><span><?php echo $item['text_start'] ?></span><span><?php echo $item['text_end'] ?></span></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <div class="item-bottom">

                                        <span class="item-price"><?php echo carbon_get_post_meta($_id, 'price'); ?></span>
                                        <?php $new_price_old = carbon_get_post_meta($_id, 'price_old'); if ($new_price_old) { ?>
                                            <span class="item-price__old"><?php echo $new_price_old; ?></span>
                                        <?php } ?>
                                            <a href="<?php echo get_the_permalink( $_id ); ?>" class="item-book btn btn-red">Book</a>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <?php endif; endforeach; ?>

					</div>
                    <?php endif; ?>
                    <div class="section-link"><a href="<?php if ($translated_id) {$translated_url = get_permalink($translated_id);echo $translated_url;} else {$original_url = get_permalink($page_id); echo $original_url;}?>" data-id="<?php echo $original_post_id;?>" data-translated="<?php  echo $translated_post_id;?>" data-title="<?php echo the_title(); ?>" data-price='' data-moto-id='' data-tour-id='' class="btn btn-red btn-book"><?php echo pll_e('Book a tour');?></a></div>
				</div>
            </section>
            <?php
            endif;

        endif;
    endforeach;
endif;
?>
<section class="banner-bottom">
    <div class="container">
        <div class="banner-bottom-wrap">
            <div class="title-section"><?php echo pll_e('tours and trips');?></div>
            <div class="text-section"><?php echo pll_e('new countries');?></div>
        </div>
    </div>
</section>
<section style="display: none;"><a href="<?php if ($translated_id) {$translated_url = get_permalink($translated_id);echo $translated_url;} else {$original_url = get_permalink($page_id); echo $original_url;}?>" data-id="<?php echo $original_post_id;?>" data-translated="<?php  echo $translated_post_id;?>" data-title="<?php echo the_title(); ?>" data-price='' data-moto-id='' data-tour-id='' class="btn btn-red btn-book"><?php echo pll_e('Book');?></a></section>
<?php get_footer(); ?>
<style>
    .items .item{
        margin-bottom: 30rem;
    }

</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/map.js"></script>
<?php if(!empty($termsType)): ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/single-tour-calendar.js"></script>
<?php endif; ?>

