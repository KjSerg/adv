<?php get_header();
/*
 * Template name: Blog
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
        <div class="text-section"><?php echo the_content(); ?></div>
    </div>
</section>
<section class="blog-page">
    <div class="container">
        <div class="top-blog">
            <div class="search-post">
                <form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" class="search-post">
                    <label class="label-search">
                        <input type="text" class="input-search" name="s" id="s" placeholder="<?php echo pll_e('Search');?>" value="<?php echo get_search_query(); ?>" />
                    </label>
                    <input type="hidden" name="post_type" value="post" />
                    <button type="submit" class="btn-search"><?php esc_html_e('', 'textdomain'); ?></button>
                </form>
            </div>
            <div class="sort-post">
            <form method="GET" id="sort-posts-form">
                <select class="select" name="sort_by" onchange="this.form.submit()">
                    <option value="date" <?php selected( $_GET['sort_by'], 'date' ); ?>><?php echo pll_e('Sort by Date');?></option>
                    <option value="title" <?php selected( $_GET['sort_by'], 'title' ); ?>><?php echo pll_e('Sort by Name');?></option>
                </select>
            </form>
            </div>
        </div>
        
        <?php
            $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date'; 
            $args = array(
                'post_type' => 'post', 
                'posts_per_page' => 9,
                'orderby' => ($sort_by === 'title') ? 'title' : 'date', 
                'order' => ($sort_by === 'title') ? 'ASC' : 'DESC', 
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
                                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                                }
                                ?>    
                            </a>
                        </div>  
                        <div class="item-desc">
                            <div class="item-date"><?php echo get_the_author_meta('first_name');?> <?php echo get_the_author_meta('last_name');  ?> • <?php $timestamp = get_the_time('U'); echo date('j M Y', $timestamp); ?></div>
                            <div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                            <div class="item-text"><?php the_excerpt(); ?></div>
                            <div class="item-bottom">
                                <a href="<?php the_permalink(); ?>" class="item-book btn btn-red"><?php echo pll_e('Read More');?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                $i++; endwhile;
                
                wp_reset_postdata();
            else :
                echo 'Немає постів для відображення.';
            endif;
            ?>
        <div class="bottom-blog" ></div>
    </div>
</section>
<?php get_footer(); ?>
