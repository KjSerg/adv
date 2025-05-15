<?php get_header();
/*
 * Template name: Motorcycle
 * */
$var = variables();

?>
<section class="top-page">
    <div class="top-inner">
        <div class="bread-crumbs">
            <ul>
                <li><a href="/"><?php echo pll_e('Main');?></a></li>
                <li><?php echo get_the_title(); ?></li>
            </ul>
        </div>
        <div class="title-section"><?php echo get_the_title(); ?></div>
    </div>
</section>
<section class="tours-page">
    <div class="container">
    <?php
            $args = array(
                'post_type' => 'bike', 
                'posts_per_page' => 9,
            );
            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) :?>
            <div class="items">
            <?php $i = 1;
                while ($the_query->have_posts()) : $the_query->the_post();
                    ?>
                    <div class="item" data-aos="fade-up" data-aos-delay="<?php echo ($i - 1) % 3 + 1;?>00">
                        <div class="item-media">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                $terms = get_the_terms(get_the_ID(), 'category-tour');
                                if ($terms && !is_wp_error($terms)) : 
                                    foreach ($terms as $term) :
                                        $class_color = carbon_get_term_meta($term->term_id, 'crb_class_color'); ?>
                                        <span class="label <?php echo esc_attr($class_color); ?>"><?php echo esc_html($term->name); ?></span>
                                    <?php endforeach; 
                                endif; ?>
                                <?php  $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                                }?>
                            </a>
                        </div>
                        <div class="item-desc">
                            <div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>                            
                                <?php
                                $_id = get_the_ID();
                                $bike_stock = get_post_meta($_id, '_bike_stock', true);
                                $bike_price = get_post_meta($_id, '_bike_price', true);
                                $bike_price_old = get_post_meta($_id, '_bike_price_old', true);
                                if ($infoList = carbon_get_post_meta($_id, 'list')) : ?>
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
                                <a href="<?php echo get_the_permalink( $_id ); ?>" class="item-book btn btn-red"><?php echo pll_e('Book');?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                $i++; endwhile;
                wp_reset_postdata();
            else :
                echo '<h4>Not found post</h4>';
            endif;
            ?>
    </div>
</section>
<?php get_footer(); ?>
<script>
    document.cookie = "page_type=; path=/; max-age=0; SameSite=Strict";
</script>