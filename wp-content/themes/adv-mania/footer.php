<?php
$var               = variables();
$url               = $var['url'];
$logo              = carbon_get_theme_option( 'crb_logo_white' );
$copyright         = carbon_get_theme_option( 'crb_copyright' );
$social_links      = carbon_get_theme_option( 'social_links' );
$links_footer      = carbon_get_theme_option( 'crb_link' );
$text_links_footer = carbon_get_theme_option( 'crb_link_text' );
$policy_page_id    = (int) get_option( 'wp_page_for_privacy_policy' ) ?: 0;
?>
</div>
<footer id="footer" class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-logo">
                <a href="<?php echo $url; ?>"><img src="<?php echo $logo; ?>" alt="logo"></a>
            </div>
			<?php if ( $social_links ) : ?>
                <div class="footer-social">
                    <ul>
						<?php foreach ( $social_links as $social_link ) : ?>
                            <li>
                                <a href="<?php echo $social_link['link'] ?>"
                                   target="_blank"><?php echo $social_link['text'] ?></a>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </div>
			<?php endif; ?>
        </div>
        <div class="footer-bottom">
            <a href="<?php echo $links_footer; ?>"><?php echo $text_links_footer; ?></a>
            <span><?php echo pll_e( 'copyright' ); ?></span>
        </div>
    </div>
</footer>

<div class="popup " data-popup="video">
    <div class="popup-wrap">
        <div class="popup-close"></div>
        <div class="popup-video">
            <video src="" poster="" controls></video>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div>
<div class="popup popup-info" data-popup="info">
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
                        <label><?php echo pll_e( 'Phone ' ); ?>
                            <span>*</span>
                        </label>
                        <input type="tel" placeholder="<?php echo pll_e( 'phone placeholder' ); ?>" name='phone'
                               required>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/phone.svg" alt="icon">
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
<div class="popup popup-info" data-popup="dialog">
    <div class="popup-wrap">
        <div class="popup-close"></div>
        <div class="popup-inner">
            <div class="popup-suptitle"></div>
            <div class="popup-title"></div>
            <div class="popup-text"></div>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div>
<script>
    var bookingData = {
        advanceCoefficient: 0.2
    };
</script>
<?php wp_footer(); ?>
</div>
</body>
</html>