<?php
$var            = variables();
$url            = $var['url'];
$logo           = carbon_get_theme_option( 'crb_logo' );
$crb_phone      = carbon_get_theme_option( 'crb_phone' );
$crb_phone_link = carbon_get_theme_option( 'crb_phone_link' );
$social_links   = carbon_get_theme_option( 'social_links' );
?>
<!doctype html>
<html class="no-js  page" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!-- WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <meta name="robots" content="noindex, nofollow">
    <title><?php wp_title(); ?> </title>
	<?php wp_head(); ?>
</head>
<body>
<div class="page-wrapper">

    <header class="header" id="header">
        <div class="container">
            <div class="header-logo">
                <a href="<?php echo $url; ?>"><img src="<?php echo $logo; ?>" alt="logo"></a>
            </div>
            <div class="header-nav">
				<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
            </div>
			<?php if ( $social_links ) : ?>
                <div class="header-social">
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
            <div class="header-info">
                <div class="header-lang">
                    <div class="header-lang__title">
                        <span><?php echo pll_current_language(); ?></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/down.svg" alt="down"></div>
					<?php
					$current_lang = pll_current_language();
					$languages    = pll_languages_list();
					if ( ! empty( $languages ) ) {
						echo '<ul class="language-switcher">';
						foreach ( $languages as $lang ) {
							if ( $lang !== $current_lang ) {
								$translated_id = pll_get_post( get_the_ID(), $lang );
								if ( $translated_id ) {
									echo '<li><a href="' . esc_url( get_permalink( $translated_id ) ) . '">' . esc_html( $lang ) . '</a></li>';
								}
							}
						}
						echo '</ul>';
					}
					?>
                </div>
                <a href="/tours" class="btn btn-black"><?php echo pll_e( 'Book a tour' ); ?></a>
            </div>
            <div class="header-btn">
                <span></span>
            </div>
        </div>
        <div class="header-overlay"></div>
    </header>

    <div class="page-content">
