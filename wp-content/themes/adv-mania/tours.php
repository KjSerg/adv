<?php get_header();
/*
 * Template name: Tours
 * */
$var = variables();
$id = get_the_ID();

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
        <?php 
        $terms = get_terms(array(
            'taxonomy' => ['category-tour', 'tour-type'],
            'hide_empty' => true, 
        ));
        ?>
        <?php
        if ($terms && !is_wp_error($terms)) :?>
        <div class="filter-button">
            <a href="#" class="btn btn-radial active btn-reset-caledar" ><?php echo pll_e('All tours');?></a>
            <?php foreach ($terms as $term) :?>
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="btn btn-radial"><?php echo esc_html($term->name); ?></a>
            <?php endforeach;?>
            </div>
        <?php endif;?>
    </div>
</section>
<section class="tours-page">
    <div class="container">
    <div class="booking-calendar">
        <div class="calendar">
            <button id="prev" class="calendar-prev calendar-button"></button>
            <div class="month" id="current-month"></div>
            <div class="month" id="next-month"></div>
            <button id="next" class="calendar-next calendar-button"></button>
        </div>
    </div>
    <div class="booking-calendar__info text-section"><?php echo pll_e('info calendar tour');?></div>
    <div class="top-section top-blog">
        <div class="title-section"><?php echo pll_e('Choose Your Next');?> </div>
        <div class="sort-post">
            <form method="GET" id="sort-posts-form">
                <select class="select" name="sort_by" onchange="this.form.submit()">
                    <option value="date" <?php selected( $_GET['sort_by'], 'date' ); ?>><?php echo pll_e('Sort by Date');?></option>
                    <option value="title" <?php selected( $_GET['sort_by'], 'title' ); ?>><?php echo pll_e('Sort by Months');?></option>
                </select>
            </form>
        </div>
    </div>
    <?php
            $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date'; 
            $args = array(
                'post_type' => 'tour', 
                'posts_per_page' => 9,
                'orderby' => ($sort_by === 'title') ? 'title' : 'date', 
                'order' => ($sort_by === 'title') ? 'ASC' : 'DESC', 
            );
            
            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) :?>
            <div class="items"  id="tour-items">
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
                                endif; 
                                $termsType = get_the_terms(get_the_ID(), 'tour-type');
                                if ($termsType && !is_wp_error($termsType)) : 
                                    foreach ($termsType as $term) :?>
                                        <span class="label label-right label-red"><?php echo esc_html($term->name); ?></span>
                                    <?php endforeach; 
                                endif; 
                                ?>
                                <?php  $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                                }?>
                            </a>
                        </div>
                        <div class="item-desc">
                            <div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                            <?php if ($infoList = carbon_get_post_meta($id, 'list')) : ?>
                                <ul class="item-list">
                                <?php  
                                    $infoDates = carbon_get_post_meta($id, 'list_date'); 
                                    $ids = 1;
                                    
                                    $currentDate = new DateTime(); // Поточна дата
                                    
                                    foreach ($infoDates as $item) :
                                        $repeat_monthly = $item['repeat_monthly'];
                                        $repeat_count = $item['repeat_count'];
                                        $start_date = new DateTime($item['date_start']);
                                        $end_date = new DateTime($item['date_end']);
                                        
                                        // Якщо дата початку вже пройшла, пропускаємо цей тур
                                        if ($start_date < $currentDate) {
                                            continue; // Пропускаємо поточну ітерацію, якщо дата вже минула
                                        }
                                        
                                        // Додаємо клас hidden для всіх окрім першого елемента
                                        $hiddenClass = ($ids > 1) ? ' hidden' : '';
                                        echo '<li class="date-item' . $hiddenClass . '"><span>';
                                                pll_e('Tour dates');
                                                echo '</span> <span>' 
                                                    . $start_date->format('Y-m-d') 
                                                    . ' - ' 
                                                    . $end_date->format('Y-m-d') 
                                                    . '</span></li>';
                                        
                                        if ($repeat_monthly && is_numeric($repeat_count) && $repeat_count > 1) {
                                            for ($i = 1; $i < $repeat_count; $i++) {
                                                $start_date->modify('+1 month');
                                                $end_date->modify('+1 month');
                                                
                                                // Якщо дата початку вже пройшла, пропускаємо цей тур
                                                if ($start_date < $currentDate) {
                                                    continue; // Пропускаємо повторення, якщо дата вже минула
                                                }
                                                
                                                // Додаємо клас hidden для повторюваних елементів (всі вони не перші)
                                                $hiddenClass = ' hidden';
                                                echo '<li class="date-item' . $hiddenClass . '"><span>';
                                                    pll_e('Tour dates');
                                                    echo '</span> <span>' 
                                                        . $start_date->format('Y-m-d') 
                                                        . ' - ' 
                                                        . $end_date->format('Y-m-d') 
                                                        . '</span></li>';
                                            }
                                        }
                                        
                                        $ids++;
                                    endforeach;
                                    ?>

                                <?php foreach ($infoList as $item) : ?>
                                    <li><span><?php echo $item['text_start'] ?></span><span><?php echo $item['text_end'] ?></span></li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="item-bottom">
                            <span class="item-price__info"><?php echo pll_e('From');?></span>
                            <span class="item-price"><?php echo carbon_get_post_meta($id, 'price'); ?></span>
                            <?php $new_price_old = carbon_get_post_meta($id, 'price_old'); if ($new_price_old) { ?>
                                <span class="item-price__old"><?php echo $new_price_old; ?></span>
                            <?php } ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="item-book btn btn-red"><?php echo pll_e('View');?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                $i++; endwhile;
                wp_reset_postdata();
            else :
                echo '';
            endif;
            ?>
    </div>
</section>
<section class="hidden" style="display: none;">
$terms = get_terms(array(
    'taxonomy' => 'category-tour',
</section>
<section class="banner-bottom">
    <div class="container">
        <div class="banner-bottom-wrap">
            <div class="title-section">
                <?php echo pll_e('tours and trips');?>
            </div>
            <div class="text-section">
            <?php echo pll_e('new countries');?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/tours-calendar.js"></script>

