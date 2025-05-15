<?php get_header();

$var = variables();

?>
<section class="top-page">
    <div class="top-inner"  style="background-image: url('<?php echo get_template_directory_uri() ?>/assets/img/top-bg.jpg');">
        <div class="container">
            <div class="bread-crumbs" data-aos="fade-up">
                <ul>
                    <li><a href="/">Main</a></li>
                    <li><?php the_title(); ?></li>
                </ul>
            </div>
            <div class="top-wrap">
                <div class="title-section title-decor" data-aos="fade-up"><span><?php the_title(); ?></span></div>
                <div class="text-section" data-aos="fade-up"><?php the_content(); ?></div>
            </div>
        </div>
    </div>
</section>
<section class="tours-page">
    <div class="container">
    <div class="items">
    <?php  
    if( have_posts() ) :
 
        while( have_posts() ) : the_post();
     
            echo the_title();
     
        endwhile;
     
    else :
        echo 'Ничего не найдено на этой странице';
    endif;
     ?>
    </div>
    </div>
</section>

<?php get_footer(); ?>
