<?php get_header('promo');
/*
 * Template name: Promo-code generator
 * */
$var      = variables();
$set      = $var['setting_home'];
$assets   = $var['assets'];
$url      = $var['url'];
$url_home = $var['url_home'];
$id       = get_the_ID();
?>
<section class="hero" id="promo-head" style="margin-bottom: 100px">
    <div class="hero-wrap">
        <div class="hero-desc" data-aos="fade-up">
            <div class="hero-title"><?php echo get_the_title(); ?></div>
            <div class="hero-text"><?php the_post();
				the_content(); ?></div>
            <div class="hero-link">
                <a href="#" class="btn btn-yellow btn-popup" data-popup="info">
					<?php echo pll_e( 'Получить скидку' ) ?>
                </a>
            </div>
        </div>
        <div class="hero-media" data-aos="fade-up">
            <img src="<?php echo get_the_post_thumbnail_url() ?: $url_home . 'img/img_4372.jpg"'; ?>" alt="main-img">
        </div>
    </div>
</section>
<?php get_footer(); ?>
