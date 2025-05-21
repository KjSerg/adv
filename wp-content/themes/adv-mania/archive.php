<?php get_header();
$var = variables();

?>
<section class="top-page">
    <div class="top-inner"  style="background-image: url('<?php echo get_template_directory_uri() ?>/assets/img/top-bg.jpg');">
        <div class="container">
            <div class="bread-crumbs" data-aos="fade-up">
                <ul>
                    <li><a href="/">Main</a></li>
                    <li><?php echo get_the_archive_title(); ?></li>
                </ul>
            </div>
            <div class="top-wrap">
                <div class="title-section title-decor" data-aos="fade-up"><?php echo  get_the_archive_title(); ?></div>
                <div class="text-section" data-aos="fade-up"><?php the_content(); ?></div>
            </div>
        </div>
    </div>
</section>
<section class="tours-page">
    <div class="container">
    <div class="items">
        <?php if ($heroList = $screen['list']) : $i = 1; ?>
        <?php foreach ($heroList as $item) : ?>
            <?php echo ($i - 1) % 3 + 1;?>
            <?php $i++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
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
