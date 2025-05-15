<?php get_header(); ?>
<?php
$terms = get_the_terms($id, 'additional-equipment');
$var          = variables();
$set          = $var['setting_home'];
$assets       = $var['assets'];
$url          = $var['url'];
$url_home     = $var['url_home'];
$id           = get_the_ID();

$size         = $isLighthouse ? 'thumbnail' : 'full';
$screens      = carbon_get_post_meta($id, 'equipment_screens');
$new_price = carbon_get_post_meta($id, 'new_price');
$new_price_old = carbon_get_post_meta($id, 'new_price_old');
$new_list_info = carbon_get_post_meta($id, 'list_info');
$new_list = carbon_get_post_meta($id, 'list');

$translated_post_id = get_the_ID(); 
$original_post_id = pll_get_post($translated_post_id, 'en');

?>
<section class="bread-crumbs">
    <div class="container">
        <ul>
            <li><a href="<?php echo $url; ?>"><?php echo pll_e('Main');?></a></li>
            <li><a href="/motorcycle">Equipment</a></li>
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
                        <div class="tour-media">
                            <?php foreach ($terms as $term) {
                                $class_color = carbon_get_term_meta($term->term_id, 'crb_class_color'); ?>
                                <span class="label <?php echo $class_color; ?>"><?php echo  $term->name; ?></span>
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
                        <div class="tour-desc">
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
                                <span class="item-price"><?php echo $new_price; ?></span>
                                <?php if ($new_price_old) { ?>
                                    <span class="item-price__old"><?php echo $new_price_old; ?></span>
                                <?php } ?>
                                <!-- <a href="/booking" data-id="<?php echo $id;?>" data-title="<?php echo the_title(); ?>" data-tour-id='' class="btn btn-red btn-book">Book</a> -->
                                <?php
                                    $page_id = '98';
                                    $translated_id = pll_get_post($page_id);    
                                ?>
                                <a href="<?php if ($translated_id) {$translated_url = get_permalink($translated_id);echo $translated_url;} else {$original_url = get_permalink($page_id); echo $original_url;}?>" data-id="<?php echo $original_post_id;?>" data-translated="<?php  echo $translated_post_id;?>" data-title="<?php echo the_title(); ?>" data-moto-id='' data-tour-id='' class="btn btn-red btn-book"><?php echo pll_e('Book');?></a>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif;
        elseif ($screen['_type'] == 'screen_2') :
            if (!$screen['screen_off']) : ?>
            <section class="other-section" id="<?php echo $screen['id']; ?>">
				<div class="container">
					<div class="top-section">
						<div class="title-section"><?php echo $screen['title']; ?></div>
						<div class="btn-blog">
							<a href="/motorcycle" class="btn btn-red"><?php echo $screen['text_btn']; ?></a>
						</div>
					</div>
                    <?php if ( $blogs = $screen['equipment'] ): ?>
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
                                            $screens_tours = carbon_get_post_meta( $_id, 'equipment_screens' );
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
                                                            <span class="item-price"><?php echo $screens_tour['price']; ?></span>
                                                            <?php $oldPrice =  $screens_tour['price_old']?>
                                                            <?php if($oldPrice){?>
                                                            <span class="item-price__old"><?php echo $screens_tour['price_old']?></span>
                                                            <?php }?>
                                                            <a href="<?php echo get_the_permalink( $_id ); ?>" class="item-book btn btn-red">Book</a>
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
        <?php
            endif;

        endif;
    endforeach;
endif;
?>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/moto-booking.js"></script>