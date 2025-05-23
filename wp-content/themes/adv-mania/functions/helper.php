<?php

function variables() {


	return array(


		'url_home' => get_bloginfo( 'template_url' ) . '/',

		'assets' => get_bloginfo( 'template_url' ) . '/assets/',

		'setting_home' => get_option( 'page_on_front' ),

		'current_user' => wp_get_current_user(),

		'current_user_ID' => wp_get_current_user()->ID,

		'admin_ajax' => site_url() . '/wp-admin/admin-ajax.php',

		'url' => get_bloginfo( 'url' ),

	);


}


function get_term_parent_id( $term_id, $my_tax = 'product_cat' ) {


	if ( $term_id ) {

		while ( $parent_id = wp_get_term_taxonomy_parent_id( $term_id, $my_tax ) ) {

			$term_id = $parent_id;

		}


		if ( $term_id == 5 ) {
			return false;
		} else {
			return $term_id;
		}

	} else {

		return false;

	}


}


function escapeJavaScriptText( $string ) {

	return str_replace( "\n", '\n', str_replace( '"', '\"', addcslashes( str_replace( "\r", '', (string) $string ), "\0..\37'\\" ) ) );

}


add_filter( 'excerpt_length', function () {

	return 32;

} );


add_filter( 'excerpt_more', function ( $more ) {

	return '...';

} );


function _get_next_link( $label = null, $max_page = 0 ) {

	global $paged, $wp_query;

	if ( ! $max_page ) {

		$max_page = $wp_query->max_num_pages;

	}

	if ( ! $paged ) {

		$paged = 1;

	}

	$nextpage = intval( $paged ) + 1;

	$var = variables();

	$assets = $var['assets'];

	if ( ! is_single() ) {


		if ( $nextpage <= $max_page ) {

			return '<a class="next page-numbers" href="' . next_posts( $max_page, false ) . '"></a>';

		}


	}

}


function _get_previous_link( $label = null ) {

	global $paged;

	$var = variables();

	$assets = $var['assets'];

	if ( ! is_single() ) {

		if ( $paged > 1 ) {

			return '<a href="' . previous_posts( false ) . '" class="prev page-numbers"></a>';

		} else {

//            return '<a href="#" style="pointer-events: none; opacity: 0.6" class="prev page-numbers"></a>';

		}


	}

}


function get_term_name_by_slug( $slug, $taxonomy ) {

	$arr = get_term_by( 'slug', $slug, $taxonomy );

	return $arr->name;

}


function is_active_term( $slug, $arr ) {

	if ( $arr ) {

		foreach ( $arr as $item ) {

			if ( $slug == $item ) {
				return true;
			}

		}

	}

	return false;

}


function get_user_roles_by_user_id( $user_id ) {

	$user = get_userdata( $user_id );

	return empty( $user ) ? array() : $user->roles;

}


function is_user_in_role( $user_id, $role ) {

	return in_array( $role, get_user_roles_by_user_id( $user_id ) );

}


function filter_ptags_on_images( $content ) {

	return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );

}


function str_split_unicode( $str, $l = 0 ) {

	if ( $l > 0 ) {

		$ret = array();

		$len = mb_strlen( $str, "UTF-8" );

		for ( $i = 0; $i < $len; $i += $l ) {

			$ret[] = mb_substr( $str, $i, $l, "UTF-8" );

		}

		return $ret;

	}

	return preg_split( "//u", $str, - 1, PREG_SPLIT_NO_EMPTY );

}


function _s( $path, $return = false ) {

	if ( $return ) {

		return file_get_contents( $path );

	} else {

		echo file_get_contents( $path );

	}

}


function _i( $image_name ) {

	$var = variables();

	$assets = $var['assets'];

	return $assets . 'img/' . $image_name . '.svg';

}


function get_content_by_id( $id ) {

	if ( $id ) {
		return apply_filters( 'the_content', get_post_field( 'post_content', $id ) );
	}

	return false;

}


function the_phone_link( $phone_number ) {

	$s = array( '+', '-', ' ', '(', ')' );

	$r = array( '', '', '', '', '' );

	echo 'tel:' . str_replace( $s, $r, $phone_number );

}


function the_image( $id ) {

	if ( $id ) {


		$url = wp_get_attachment_url( $id );


		$pos = strripos( $url, '.svg' );


		if ( $pos === false ) {

			echo '<img src="' . $url . '" alt="">';

		} else {

			_s( $url );

		}


	}

}


function _t( $text, $return = false ) {

	if ( $return ) {

		return wpautop( $text );

	} else {

		echo wpautop( $text );

	}

}


function _rt( $text, $return = false, $remove_br = false ) {

	if ( $return ) {

		return $remove_br ? strip_tags( wpautop( $text ) ) : strip_tags( wpautop( $text ), '<br>' );

	} else {

		echo $remove_br ? strip_tags( wpautop( $text ) ) : strip_tags( wpautop( $text ), '<br>' );

	}

}


function is_even( $number ) {

	return ! ( $number & 1 );

}


function img_to_base64( $path ) {

	$type = pathinfo( $path, PATHINFO_EXTENSION );

	$data = file_get_contents( $path );

	$base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );

	return $base64;

}




function get_ids_screens() {


	$res = array();


	$var = variables();

	$set = $var['setting_home'];


	$screens = carbon_get_post_meta( $set, 'screens' );


	if ( ! empty( $screens ) ):

		foreach ( $screens as $index => $screen ):

			if ( ! $screen['screen_off'] ):

				if ( ! in_array( $screen['id'], $res ) ) {
					$res[ $screen['id'] ] = '(' . $screen['id'] . ') ' . strip_tags( $screen['title'] );
				}

			endif;

		endforeach;

	endif;


	return $res;

}

function cc_mime_types( $mimes ) {

	$mimes['svg'] = 'image/svg+xml'; // Добавление MIME-типа для SVG

	return $mimes;

}

add_filter( 'upload_mimes', 'cc_mime_types' );

function custom_pagination() {
	global $wp_query;

	// Отримуємо поточну сторінку та загальну кількість сторінок
	$current_page = max(1, get_query_var('paged') ? get_query_var('paged') : 1);
	$total_pages = $wp_query->max_num_pages;

	// Якщо сторінок менше 2, пагінацію не виводимо
	if ($total_pages < 2) {
		return;
	}

	// Налаштування для десктопної пагінації
	$desktop_range = 2; // Кількість сторінок ліворуч і праворуч від активної
	$show_dots = ($total_pages > 5); // Показувати три крапки, якщо сторінок більше 5

	// Початок десктопної пагінації
	?>
	<div class="pagination" data-aos="fade-up">
		<ul>
			<?php
			// Попередня сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link($current_page - 1)) . '" ' . ($current_page == 1 ? 'class="disabled"' : '') . '></a></li>';

			// Перша сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link(1)) . '" ' . ($current_page == 1 ? 'class="active"' : '') . '>1</a></li>';

			// Показуємо три крапки, якщо потрібно
			if ($show_dots && $current_page > $desktop_range + 1) {
				echo '<li>...</li>';
			}

			// Показуємо сторінки в межах діапазону
			for ($i = max(2, $current_page - $desktop_range); $i <= min($total_pages - 1, $current_page + $desktop_range); $i++) {
				echo '<li><a href="' . esc_url(get_pagenum_link($i)) . '" ' . ($current_page == $i ? 'class="active"' : '') . '>' . $i . '</a></li>';
			}

			// Показуємо три крапки перед останньою сторінкою
			if ($show_dots && $current_page < $total_pages - $desktop_range) {
				echo '<li>...</li>';
			}

			// Остання сторінка
			if ($total_pages > 1) {
				echo '<li><a href="' . esc_url(get_pagenum_link($total_pages)) . '" ' . ($current_page == $total_pages ? 'class="active"' : '') . '>' . $total_pages . '</a></li>';
			}

			// Наступна сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link($current_page + 1)) . '" ' . ($current_page == $total_pages ? 'class="disabled"' : '') . '></a></li>';
			?>
		</ul>
	</div>

	<?php
	// Початок мобільноїa мобільної пагінації
	?>
	<div class="pagination pagination-mob" data-aos="fade-up">
		<ul>
			<?php
			// Попередня сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link($current_page - 1)) . '" ' . ($current_page == 1 ? 'class="disabled"' : '') . '></a></li>';

			// Перша сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link(1)) . '" ' . ($current_page == 1 ? 'class="active"' : '') . '>1</a></li>';

			// Показуємо три крапки
			if ($current_page > 2) {
				echo '<li>...</li>';
			}

			// Поточна сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link($current_page)) . '" class="active">' . $current_page . '</a></li>';

			// Остання сторінка
			if ($current_page < $total_pages) {
				echo '<li><a href="' . esc_url(get_pagenum_link($total_pages)) . '">' . $total_pages . '</a></li>';
			}

			// Наступна сторінка
			echo '<li><a href="' . esc_url(get_pagenum_link($current_page + 1)) . '" ' . ($current_page == $total_pages ? 'class="disabled"' : '') . '></a></li>';
			?>
		</ul>
	</div>
	<?php
}