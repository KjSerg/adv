<?php get_header();
/*
 * Template name: Search
 * */
$var = variables();

?>
<section class="top-page">
    <div class="top-inner">
        <div class="bread-crumbs">
            <ul>
                <li><a href="/">Main</a></li>
                <li><?php echo get_the_title(); ?></li>
            </ul>
        </div>
        <div class="title-section"><?php printf(__('Search Results for: %s', 'textdomain'), '<span>' . get_search_query() . '</span>'); ?></div>
    </div>
</section>
<section class="blog-page">
    <div class="container">
        <div class="top-blog">
            <div class="search-post">
            <form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" >
                <label class="label-search">
                    <input type="text" class="input-search" name="s" id="s" placeholder="Search" value="<?php echo get_search_query(); ?>" />
                </label>
                <input type="hidden" name="post_type" value="post" />
                <button type="submit" class="btn-search"><?php esc_html_e('', 'textdomain'); ?></button>
            </form>
            </div>
            <div class="sort-post">
                <!-- <form method="GET" id="sort-posts-form">
                    <select class="select" name="sort_by" onchange="this.form.submit()">
                        <option value="date" <?php selected( $_GET['sort_by'], 'date' ); ?>>Sort by Date</option>
                        <option value="title" <?php selected( $_GET['sort_by'], 'title' ); ?>>Sort by Name</option>
                    </select>
                </form> -->
            </div>
        </div>
        <?php if (have_posts()) : ?>
        <div class="items">
            <?php $i = 1; while (have_posts()) : the_post(); ?>
                <div class="item" data-aos="fade-up" data-aos-delay="<?php echo ($i - 1) % 3 + 1;?>00">
                    <div class="item-media">
                    <a href="<?php the_permalink(); ?>">
                    <?php  $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        if ($image_url) {
                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                        }
                        ?>    
                    </a>
                    </div>
                    <div class="item-desc">
                        <div class="item-date"><?php echo get_the_author_meta('first_name');?> <?php echo get_the_author_meta('last_name');  ?> • <?php $timestamp = get_the_time('U'); echo date('j M Y', $timestamp); ?></div>
                        <div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                        <div class="item-text">
                        <?php the_excerpt(); ?>
                        </div>
                        <div class="item-bottom">
                            <a href="<?php the_permalink(); ?>" class="item-book btn btn-red">Read More</a>
                        </div>
                    </div>
                </div>
            <?php $i++; endwhile; ?>
        </div>
    <?php else : ?>
        <p><?php _e('No results found.', 'textdomain'); ?></p>
    <?php endif; ?>
            <!-- <?php the_posts_navigation(); ?> -->
        <div class="bottom-blog" style="display: none;">
            <div class="pagination" data-aos="fade-up">
                <ul>
                    <li><a href="#"></a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a class="active" href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li>...</li>
                    <li><a href="#">8</a></li>
                    <li><a href="#">9</a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
            <div class="pagination pagination-mob" data-aos="fade-up">
                <ul>
                    <li><a href="#"></a></li>
                    <li><a href="#">1</a></li>
                    <li>...</li>
                    <li><a class="active" href="#">3</a></li>
                    <li><a href="#">9</a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
