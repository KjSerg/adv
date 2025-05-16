<?php
/*
 * Template name: Promo-code generator
 * */
get_header(  );
$var            = variables();
$set            = $var['setting_home'];
$assets         = $var['assets'];
$url            = $var['url'];
$url_home       = $var['url_home'];
$id             = get_the_ID();
$policy_page_id = (int) get_option( 'wp_page_for_privacy_policy' ) ?: 0;
?>
<section class="hero" id="promo-head" style="margin-bottom: 100px">
    <div class="hero-wrap">
        <div class="hero-desc" data-aos="fade-up">
            <div class="hero-title"><?php echo get_the_title(); ?></div>
            <div class="hero-text"><?php the_post();
				the_content(); ?></div>
            <div class="hero-link">
                <a href="#" class="btn btn-yellow btn-popup" data-popup="promo">
					<?php echo pll_e( 'Получить скидку' ) ?>
                </a>
            </div>
        </div>
        <div class="hero-media" data-aos="fade-up">
            <img src="<?php echo get_the_post_thumbnail_url() ?: $url_home . 'img/img_4372.jpg"'; ?>" alt="main-img">
        </div>
    </div>
</section>

<div class="popup popup-info" data-popup="promo">
    <div class="popup-wrap">
        <div class="popup-close"></div>
        <div class="popup-inner">
            <div class="popup-suptitle"></div>
            <div class="popup-title"><?php echo pll_e( 'Leave a Request' ); ?></div>
            <div class="popup-text"><?php echo pll_e( 'Got a question' ); ?> </div>
            <div class="popup-form">
                <form class='popup-form' id="ajax-form">
                    <div class="item-input">
                        <label><?php echo pll_e( 'First Name and Last Name' ); ?>
                            <span>*</span>
                        </label>
                        <input type="text" placeholder="<?php echo pll_e( 'Name' ); ?>" name='name' required>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/user.svg" alt="icon">
                    </div>
                    <div class="item-input">
                        <label><?php echo pll_e( 'Country ' ); ?>
                            <span>*</span>
                        </label>
                        <input type="text" placeholder="<?php echo pll_e( 'Country placeholder' ); ?>" name='country'
                               required>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/globe.svg" alt="icon">
                    </div>
                    <div class="item-input">
                        <label><?php echo pll_e( 'Email' ); ?>
                            <span>*</span>
                        </label>
                        <input type="email" placeholder="<?php echo pll_e( 'email placeholder' ); ?>" name='email'
                               required>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/mail.svg" alt="icon">
                    </div>
					<?php if ( $policy_page_id ): ?>
                        <div class="form-consent">
                            <label class="form-consent-box">
                                <input type="checkbox" name="consent" required value="yes">
                                <span></span>
                            </label>
                            <div class="form-consent-text">
                                <p><?php echo pll_e( 'Вы соглашаетесь с' ) ?> <a
                                            href="<?php echo get_the_permalink( $policy_page_id ) ?>"><?php echo get_the_title( $policy_page_id ) ?></a>.
                                </p>
                            </div>
                        </div>
					<?php endif; ?>
                    <button type="submit" class="btn btn-red popup-form-btn"
                            id="submit-btn"><?php echo pll_e( 'Send' ); ?></button>
                </form>
            </div>
            <div class="popup-success hidden">
                <div class="popup-title"><?php echo pll_e( 'Success Message' ); ?></div>
                <div class="popup-text"><?php echo pll_e( 'Thank you' ); ?> </div>
            </div>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div>
<?php get_footer(); ?>
