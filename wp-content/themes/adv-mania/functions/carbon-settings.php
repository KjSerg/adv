<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {

	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels        = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_modals = array(
		'plural_name'   => 'модальные окна',
		'singular_name' => 'модальное окно',
	);

	Container::make( 'theme_options', "Настройки сайта" )
	         ->add_tab( 'Логотип', array(
		         Field::make( 'image', 'crb_logo', 'Логотип' )
		              ->set_value_type( 'url' )
		              ->set_width( 50 )
		              ->set_required( true ),
		         Field::make( 'image', 'crb_logo_white', 'Логотип в футере' )
		              ->set_value_type( 'url' )
		              ->set_width( 50 )
		              ->set_required( true ),

	         ) )
	         ->add_tab( 'Копирайт', array(
		         Field::make( 'text', 'crb_link', 'Ссылка в футере' )->set_width( 50 ),
		         Field::make( 'text', 'crb_link_text', 'Текст ссылки' )->set_width( 50 ),
		         Field::make( 'text', 'crb_copyright', 'Копирайт' ),
	         ) )
	         ->add_tab( 'Информация', array(
		         Field::make( 'text', 'crb_stripe_api_key', 'stripe api key' ),
		         Field::make( 'text', 'crb_stripe_publishable_key', 'stripe Publishable key' ),

		         Field::make( 'text', 'crb_merchant_id', 'PAYTR merchant id' ),
		         Field::make( 'text', 'crb_merchant_key', 'PAYTR merchant key' ),
		         Field::make( 'text', 'crb_merchant_salt', 'PAYTR merchant salt' ),
		         Field::make( 'text', 'crb_test_mode', 'PAYTR test mode' ),
		         Field::make( 'text', 'crb_currency_paytr', 'PAYTR currency' ),

		         Field::make( 'text', 'crb_tg_bot', 'tg bot' ),
		         Field::make( 'complex', 'telegram_links', 'telegram chat id' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text', 'chat id' )->set_required( true ),
		              ) )
	         ) )
	         ->add_tab( 'Соц. сети', array(
		         Field::make( 'complex', 'social_links', 'Список' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'text', 'link', 'Ссылка' )->set_required( true ),
		              ) )
	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_user_options' );
function crb_attach_user_options() {

	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels        = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_modals = array(
		'plural_name'   => 'модальные окна',
		'singular_name' => 'модальное окно',
	);

	Container::make( 'user_meta', __( 'Properties' ) )
	         ->add_fields( array(
		         Field::make( 'image', 'crb_image', __( 'Изображенние' ) )->set_value_type( 'url' ),

	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_catygory_options' );
function crb_attach_catygory_options() {
	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);
	$labels         = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_modals  = array(
		'plural_name'   => 'модальные окна',
		'singular_name' => 'модальное окно',
	);
	Container::make( 'term_meta', __( 'Category Properties' ) )
	         ->where( 'term_taxonomy', '=', 'category-tour' )
	         ->add_fields( array(
		         Field::make( 'text', 'crb_class_color', __( 'Класс для фононвого цвета' ) ),
		         Field::make( 'complex', 'list', 'Список' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'title_start', 'Текст' )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'title_end', 'Текст' )->set_required( true )->set_width( 50 ),
		              ) ),
		         Field::make( 'text', 'crb_title_price', __( 'Цена' ) ),
		         Field::make( 'image', 'crb_image', __( 'Изображенние' ) )->set_value_type( 'url' ),
	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_catygory_additional' );
function crb_attach_catygory_additional() {

	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels        = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_modals = array(
		'plural_name'   => 'модальные окна',
		'singular_name' => 'модальное окно',
	);

	Container::make( 'term_meta', __( 'Category Properties' ) )
	         ->where( 'term_taxonomy', '=', 'additional-equipment' )
	         ->add_fields( array(
		         Field::make( 'image', 'crb_image', __( 'Изображенние' ) )->set_value_type( 'url' ),
		         Field::make( 'text', 'crb_class_color', __( 'Класс для фононвого цвета' ) ),
	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_front_page' );
function crb_attach_in_front_page() {
	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels         = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_columns = array(
		'plural_name'   => 'колонки',
		'singular_name' => 'колонку',
	);
	$labels_tabs    = array(
		'plural_name'   => 'вкладки',
		'singular_name' => 'вкладку',
	);
	Container::make( 'post_meta', 'Секции на главной странице' )
	         ->show_on_template( 'home.php' )
	         ->add_fields( array(
		         Field::make( 'complex', 'screens', 'Секции' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Секция 1 - Баннер', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключи  ть секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'image', 'image_bg', 'Изображение' )->set_value_type( 'url' ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Список' )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Текст' )->set_required( true ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_2', 'Секция 2 - Для кого', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'image', 'img', 'Изображение видео' )->set_value_type( 'url' ),
			              Field::make( 'file', 'video', 'Видео' )->set_value_type( 'url' ),
		              ) )
		              ->add_fields( 'screen_3', 'Секция 3 -  Бегущая строка', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'class', 'Клас для стилизации' ),
			              Field::make( 'complex', 'list', 'Список' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_4', 'Секция 4 -  Запомнился навсегда', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Галерея' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_5', 'Секция 5 - Список туров на главной', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'association', 'tours', 'Тур' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'tour',
				                   )
			                   ) )
		              ) )
		              ->add_fields( 'screen_6', 'Секция 6 - Категории', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Подзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'suptitle', 'Подзаголовок' )->set_required( true ),
			              Field::make( 'association', 'catalog', 'Категории' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'term',
					                   'post_type' => 'category-tour',
				                   )
			                   ) ),
			              Field::make( 'text', 'button_text', 'Текст кнопки' )->set_width( 50 ),
			              Field::make( 'text', 'button_url', 'URL кнопки' )->set_width( 50 ),
		              ) )
		              ->add_fields( 'screen_7', 'Секция 7 - Преимущества', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Преимущества' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' ),
				                   Field::make( 'text', 'text', 'Текст' ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_8', 'Секция 8 - Баннер', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'class', 'Клас для стилизации' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'text', 'link_btn', 'Ссылка кнопки' )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true )->set_width( 50 ),
			              Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true ),
		              ) )
		              ->add_fields( 'screen_9', 'Секция 9 - Аренда Мотоциклов', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Аренда Мотоциклов' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' ),
				                   Field::make( 'text', 'price', 'Цена' ),
				                   Field::make( 'rich_text', 'text', 'Текст' ),
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true ),
				                   Field::make( 'text', 'link', 'Ссылка' ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_10', 'Секция 10 - Категории Дополнительного оборудования', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'association', 'catalog', 'Категории' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'term',
					                   'post_type' => 'additional-equipment',
				                   )
			                   ) )

		              ) )
		              ->add_fields( 'screen_10_1', ' Дополнительное оборудование', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'association', 'catalog', 'оборудование' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'equipment',
				                   )
			                   ) )

		              ) )
		              ->add_fields( 'screen_11', 'Секция 11 - Безопасность', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Галерея' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_12', 'Секция 12 - Превосходство', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Превосходство' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_13', 'Секция 13 - О нас', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'text', 'btn_text', 'Текст кнопки' )->set_width( 50 ),
			              Field::make( 'text', 'btn_url', 'URL кнопки' )->set_width( 50 ),
			              Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
		              ) )
		              ->add_fields( 'screen_14', 'Секция 14 - Включенные услуги', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Услуги' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true )->set_width( 50 ),
				                   Field::make( 'text', 'label', 'Метка' )->set_required( true )->set_width( 50 ),
				                   Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_15', 'Секция 15 - Для кого подойдет', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Для кого ' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'rich_text', 'text_item', 'Текст' )->set_required( true ),
				                   Field::make( 'text', 'btn_text', 'Текст кнопки' )->set_required( true ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_16', 'Секция 16 - Видео отзывы', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'link_btn', 'Ссылка кнопки' )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true )->set_width( 50 ),
			              Field::make( 'complex', 'list', 'Отзывы' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true )->set_width( 50 ),
				                   Field::make( 'file', 'video', 'Видео' )->set_value_type( 'url' )->set_required( true )->set_width( 50 ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_17', 'Секция 17 - Аудио отзывы', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Отзывы' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true )->set_width( 50 ),
				                   Field::make( 'file', 'audio', 'Аудио' )->set_value_type( 'url' )->set_required( true )->set_width( 50 ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_18', 'Секция 18 - FAQ', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'complex', 'list', 'FAQ' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			                   ) )

		              ) )
	         ) );


}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_tour_page' );
function crb_attach_in_tour_page() {
	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels         = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_columns = array(
		'plural_name'   => 'колонки',
		'singular_name' => 'колонку',
	);
	$labels_tabs    = array(
		'plural_name'   => 'вкладки',
		'singular_name' => 'вкладку',
	);
	Container::make( 'post_meta', 'Секции на странице тура' )
	         ->where( 'post_type', '=', 'tour' )
	         ->add_fields( array(
		         Field::make( 'complex', 'list', 'Краткая информация' )->set_required( true )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text_start', 'Текст' )->set_required( true )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'text_end', 'Текст' )->set_required( true )->set_required( true )->set_width( 50 ),
		              ) ),
		         Field::make( 'complex', 'list_info', 'информация' )->set_required( true )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
		              ) ),

		         Field::make( 'complex', 'list_date', 'Информация даты' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'date', 'date_start', 'Начало' )->set_required( true )->set_width( 33 ),
			              Field::make( 'date', 'date_end', 'Конец' )->set_required( true )->set_width( 33 ),
			              Field::make( 'text', 'coun_people', 'Количество участников' )->set_required( true )->set_width( 33 ),
			              Field::make( 'checkbox', 'repeat_monthly', 'Повторять каждый месяц' )->set_help_text( 'Если выбрано, событие будет повторяться каждый месяц.' ),
			              Field::make( 'select', 'repeat_count', 'Количество повторений' )
			                   ->set_options( [
				                   1  => '1 месяц',
				                   2  => '2 месяца',
				                   3  => '3 месяца',
				                   4  => '4 месяца',
				                   5  => '5 месяцев',
				                   6  => '6 месяцев',
				                   7  => '7 месяцев',
				                   8  => '8 месяцев',
				                   9  => '9 месяцев',
				                   10 => '10 месяцев',
				                   11 => '11 месяцев',
				                   12 => '12 месяцев',
			                   ] )
			                   ->set_default_value( 12 )
			                   ->set_help_text( 'Количество месяцев, на которые повторится событие' ),

			              Field::make( 'association', 'bike_for', 'Мото' )
			                   ->set_help_text( 'Выбор мотоциклов для тура: редактирование последовательности бронирования берется с основного языка сайта (английская версия).' )
			                   ->set_width( 50 )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'bike',
				                   )
			                   ) ),
			              Field::make( 'association', 'equipment_for', 'Экипировки' )
			                   ->set_help_text( 'Выбор экипировки для тура: редактирование последовательности бронирования берется с основного языка сайта (английская версия).' )
			                   ->set_width( 50 )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'equipment',
				                   )
			                   ) ),
		              ) ),
		         Field::make( 'text', 'coun_percent_number', 'Коэффициент к мотоциклам' )->set_required( true ),
		         Field::make( 'text', 'coun_percent_title', 'Коэффициент заголовок' ),
		         Field::make( 'text', 'price', 'Цена' )->set_required( true )->set_width( 50 ),
		         Field::make( 'text', 'price_old', 'Цена без скидки' )->set_width( 50 ),
		         Field::make( 'complex', 'tour_screens', 'Секции' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Секция 1 - Информация', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'complex', 'gallery', 'Галерея' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
			                   ) ),
		              ) )
		              ->add_fields( 'screen_2', 'Секция 2 - Маршрут', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'text', 'text_center_map', 'Центр карты' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Маршрут' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
				                   Field::make( 'text', 'lat', 'lat' )->set_required( true )->set_width( 50 ),
				                   Field::make( 'text', 'lng', 'lng' )->set_required( true )->set_width( 50 ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_3', 'Секция 3 - Мото для тура', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'association', 'bike', 'Мото' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'bike',
				                   )
			                   ) )
		              ) )
		              ->add_fields( 'screen_4', 'Секция 4 - FAQ', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'complex', 'list', 'FAQ' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_5', 'Секция 5 - Видео отзывы', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'link_btn', 'Ссылка кнопки' )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true )->set_width( 50 ),
			              Field::make( 'complex', 'list', 'Отзывы' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true )->set_width( 50 ),
				                   Field::make( 'file', 'video', 'Видео' )->set_value_type( 'url' )->set_required( true )->set_width( 50 ),
			                   ) )

		              ) )
		              ->add_fields( 'screen_6', 'Секция 6 - Другие туры', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'subtitle', 'Надзаголовок' )->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text', 'Текст' )->set_required( true ),
			              Field::make( 'association', 'equipment', 'Тур' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'tour',
				                   )
			                   ) )
		              ) )
		              ->add_fields( 'screen_7', 'Секция 7 - Экипировка для тура', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'association', 'equipment', 'Экипировка' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'equipment',
				                   )
			                   ) )
		              ) )
		              ->add_fields( 'screen_8', 'Секция 8 - Для бронирования тура (количество)', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Информация ' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'text', 'text', 'Текст' )->set_required( true ),
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true ),
				                   Field::make( 'checkbox', 'included', 'Цена включена' )->set_width( 50 ),
				                   Field::make( 'text', 'price', 'Цена' )
				                        ->set_conditional_logic( [
					                        [
						                        'field'   => 'included',
						                        'value'   => false,
						                        'compare' => '='
					                        ]
				                        ] )->set_width( 50 ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_9', 'Секция 9 - Для бронирования тура(размещение)', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'complex', 'list', 'Информация ' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
				                   Field::make( 'text', 'text', 'Текст' )->set_required( true ),
				                   Field::make( 'text', 'text_bottom', 'Текст' )->set_required( true ),
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' )->set_required( true ),
				                   Field::make( 'checkbox', 'included', 'Цена включена' )->set_width( 50 ),
				                   Field::make( 'text', 'price', 'Цена' )
				                        ->set_conditional_logic( [
					                        [
						                        'field'   => 'included',
						                        'value'   => false,
						                        'compare' => '='
					                        ]
				                        ] )->set_width( 50 ),

			                   ) )
		              ) )

	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_equipment_page' );
function crb_attach_in_equipment_page() {
	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels         = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_columns = array(
		'plural_name'   => 'колонки',
		'singular_name' => 'колонку',
	);
	$labels_tabs    = array(
		'plural_name'   => 'вкладки',
		'singular_name' => 'вкладку',
	);
	Container::make( 'post_meta', 'Секции на странице Экипировки' )
	         ->where( 'post_type', '=', 'equipment' )
	         ->add_fields( array(
		         Field::make( 'complex', 'list', 'Краткая информация' )->set_required( true )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text_start', 'Текст' )->set_required( true )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'text_end', 'Текст' )->set_required( true )->set_required( true )->set_width( 50 ),
		              ) ),
		         Field::make( 'complex', 'list_info', 'информация' )->set_required( true )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
		              ) ),
		         Field::make( 'text', 'new_price', 'Цена' )->set_required( true )->set_width( 50 ),
		         Field::make( 'text', 'new_price_old', 'Цена без скидки' )->set_width( 50 ),
		         Field::make( 'complex', 'equipment_screens', 'Секции' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Секция 1 - Информация', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'complex', 'gallery', 'Галерея' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
			                   ) ),
		              ) )
		              ->add_fields( 'screen_2', 'Секция 2 - Экипировка', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'association', 'equipment', 'Экипировка' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'equipment',
				                   )
			                   ) )
		              ) )

	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_bike_page' );
function crb_attach_in_bike_page() {
	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels         = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_columns = array(
		'plural_name'   => 'колонки',
		'singular_name' => 'колонку',
	);
	$labels_tabs    = array(
		'plural_name'   => 'вкладки',
		'singular_name' => 'вкладку',
	);
	Container::make( 'post_meta', 'Секции на странице Мотоцикла' )
	         ->where( 'post_type', '=', 'bike' )
	         ->add_fields( array(
		         Field::make( 'complex', 'list', 'Краткая информация' )->set_required( true )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text_start', 'Текст' )->set_required( true )->set_required( true )->set_width( 50 ),
			              Field::make( 'text', 'text_end', 'Текст' )->set_required( true )->set_required( true )->set_width( 50 ),
		              ) ),
		         Field::make( 'complex', 'list_info', 'информация' )->set_required( true )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'rich_text', 'text', 'Текст' )->set_required( true ),
		              ) ),
		         // Field::make('text', 'new_price', 'Цена')->set_required(true)->set_width(33),
		         // Field::make('text', 'new_price_old', 'Цена без скидки')->set_width(33),
		         // Field::make('text', 'count', 'Количество')->set_width(33),
		         Field::make( 'complex', 'bike_screens', 'Секции' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Секция 1 - Информация', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'complex', 'gallery', 'Галерея' )->set_required( true )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
			                   ) ),
		              ) )
		              ->add_fields( 'screen_2', 'Секция 2 - Мото', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'text', 'title', 'Заголовок' )->set_required( true ),
			              Field::make( 'text', 'text_btn', 'Текст кнопки' )->set_required( true ),
			              Field::make( 'association', 'bike', 'Мото' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'bike',
				                   )
			                   ) )
		              ) )
		              ->add_fields( 'screen_3', 'Секция 3 - Экипировка', array(
			              Field::make( "separator", "crb_style_screen_off", "Отключить секцию?" ),
			              Field::make( 'checkbox', 'screen_off', 'Отключить секцию?' ),
			              Field::make( "separator", "crb_style_inform", "Информация" ),
			              Field::make( "text", "id", "ID секции (уникальное значение)" )
			                   ->set_help_text( 'Слово на латинице без пробелов и цифр' )
			                   ->set_required( true ),
			              Field::make( 'association', 'equipment', 'Экипировка отображається на странице бронирования' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'equipment',
				                   )
			                   ) )
		              ) )

	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_booking_page' );
function crb_attach_in_booking_page() {
	$screens_labels = array(
		'plural_name'   => 'секции',
		'singular_name' => 'секцию',
	);

	$labels         = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	$labels_columns = array(
		'plural_name'   => 'колонки',
		'singular_name' => 'колонку',
	);
	$labels_tabs    = array(
		'plural_name'   => 'вкладки',
		'singular_name' => 'вкладку',
	);
	Container::make( 'post_meta', 'Секции на странице Бронирования' )
	         ->show_on_template( [ 'booking.php', 'booking-motorcycle.php' ] )
	         ->add_fields( array(
		         Field::make( 'text', 'available_title', 'Заголовок' ),
		         Field::make( 'text', 'label_text_name', 'Лейбл для имени' )->set_width( 50 ),
		         Field::make( 'text', 'text_name', 'Текст для имени' )->set_width( 50 ),
		         Field::make( 'text', 'label_text_counnrty', 'Лейбл для Страны' )->set_width( 50 ),
		         Field::make( 'complex', 'text_country', 'Страна' )->set_width( 50 )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text', 'Текст для Страны' ),
		              ) ),
		         Field::make( 'text', 'label_text_phone', 'Лейбл для Телефона' )->set_width( 50 ),
		         Field::make( 'text', 'text_phone', 'Текст для Телефона' )->set_width( 50 ),
		         Field::make( 'text', 'label_text_contacts', 'Лейбл для Месенджер' )->set_width( 50 ),
		         Field::make( 'complex', 'text_contacts', 'Месенджер' )->set_width( 50 )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text', 'Текст для Месенджер' ),
		              ) ),
		         Field::make( 'text', 'label_text_email', 'Лейбл для Email' )->set_width( 50 ),
		         Field::make( 'text', 'text_email', 'Текст для Email' )->set_width( 50 ),
		         Field::make( 'text', 'label_text_pay', 'Лейбл для оплаты' )->set_width( 50 ),
		         Field::make( 'complex', 'text_pay', 'Oплатa' )->set_width( 50 )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'text', 'Текст' ),
		              ) ),
		         Field::make( 'text', 'payments_btn', 'Текст кнопки оплатить' ),
		         Field::make( 'text', 'page_offer_agreement', 'страница оферты' ),
		         Field::make( 'text', 'privacy_policy', 'страница политики' ),
		         Field::make( 'text', 'success_title', 'Успех заголовок' ),
		         Field::make( 'rich_text', 'success_text', 'Успех текст' ),

		         // Field::make('complex', 'bike_screens', 'Секции')
		         //     ->set_layout('tabbed-vertical')
		         //     ->setup_labels($screens_labels)
		         //     ->add_fields('screen_1', 'Секция 1 - Информация', array(
		         //         Field::make("separator", "crb_style_screen_off", "Отключить секцию?"),
		         //         Field::make('checkbox', 'screen_off', 'Отключить секцию?'),
		         //         Field::make("separator", "crb_style_inform", "Информация"),
		         //         Field::make("text", "id", "ID секции (уникальное значение)")
		         //             ->set_help_text('Слово на латинице без пробелов и цифр')
		         //             ->set_required(true),
		         //         Field::make('complex', 'gallery', 'Галерея')->set_required(true)
		         //             ->setup_labels($labels)
		         //             ->add_fields(array(
		         //                 Field::make('image', 'image', 'Изображение')->set_value_type( 'url' ),
		         //             )),
		         //         Field::make('complex', 'list', 'Краткая информация')->set_required(true)
		         //             ->setup_labels($labels)
		         //             ->add_fields(array(
		         //                 Field::make('text', 'text_start', 'Текст')->set_required(true)->set_required(true)->set_width(50),
		         //                 Field::make('text', 'text_end', 'Текст')->set_required(true)->set_required(true)->set_width(50),
		         //             )),
		         //         Field::make('complex', 'list_info', 'информация')->set_required(true)
		         //             ->setup_labels($labels)
		         //             ->add_fields(array(
		         //                 Field::make('text', 'title', 'Заголовок')->set_required(true),
		         //                 Field::make('rich_text', 'text', 'Текст')->set_required(true),
		         //             )),
		         //         Field::make('text', 'price', 'Цена')->set_required(true)->set_width(50),
		         //         Field::make('text', 'price_old', 'Цена без скидки')->set_width(50),
		         //         ))
		         //     ->add_fields('screen_2', 'Секция 2 - Мото', array(
		         //         Field::make("separator", "crb_style_screen_off", "Отключить секцию?"),
		         //         Field::make('checkbox', 'screen_off', 'Отключить секцию?'),
		         //         Field::make("separator", "crb_style_inform", "Информация"),
		         //         Field::make("text", "id", "ID секции (уникальное значение)")
		         //             ->set_help_text('Слово на латинице без пробелов и цифр')
		         //             ->set_required(true),
		         //         Field::make('text', 'title', 'Заголовок')->set_required(true),
		         //         Field::make('text', 'text_btn', 'Текст кнопки')->set_required(true),
		         //         Field::make( 'association', 'bike', 'Мото')
		         //         ->set_types( array(
		         //             array(
		         //                 'type'      => 'post',
		         //                 'post_type' => 'bike',
		         //             )
		         //         ) )
		         //     ))

	         ) );

}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_oder_post' );
function crb_attach_in_oder_post() {
	$labels = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);

	Container::make( 'post_meta', 'Информация по закaзу' )
	         ->where( 'post_type', '=', 'orders' )
	         ->add_tab( 'Основная информация', array(
		         Field::make( "text", "order_type", "Тип заказа" )->set_width( 25 ),
		         Field::make( "text", "order_order_tour", "Название услуги" )->set_width( 50 ),
		         Field::make( "text", "order_order_price", "Цена услуги" )->set_width( 25 ),
		         Field::make( "date", "order_order_start", "Начало бронирования" )->set_width( 50 ),
		         Field::make( "date", "order_order_end", "Окончание бронирования" )->set_width( 50 ),
		         Field::make( "text", "order_persons", "Участники" )->set_width( 50 )->set_conditional_logic( [
			         [
				         'field'   => 'order_type',
				         'value'   => 'bike',
				         'compare' => '!=',
			         ]
		         ] ),
		         Field::make( "text", "order_persons_val", "Сумма" )->set_width( 50 )->set_conditional_logic( [
			         [
				         'field'   => 'order_type',
				         'value'   => 'bike',
				         'compare' => '!=',
			         ]
		         ] ),
		         Field::make( "text", "order_accommodation", "Проживание" )->set_width( 50 )->set_conditional_logic( [
			         [
				         'field'   => 'order_type',
				         'value'   => 'bike',
				         'compare' => '!=',
			         ]
		         ] ),
		         Field::make( "text", "order_accommodation_val", "Сумма" )->set_width( 50 )->set_conditional_logic( [
			         [
				         'field'   => 'order_type',
				         'value'   => 'bike',
				         'compare' => '!=',
			         ]
		         ] ),
		         Field::make( "text", "order_country", "Страна " )->set_width( 50 ),
		         Field::make( 'complex', 'order_info', 'Доп. Оборудование' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'info_name', __( 'Название' ) )->set_width( 70 ),
			              Field::make( 'text', 'price', __( 'Цена' ) )->set_width( 30 ),
		              ) ),
		         Field::make( "text", "order_sum", "Сумма тура" )->set_classes( 'order_sum' ),
		         Field::make( 'separator', 'crb_separator_order_promo_code', __( 'Промокод' ) ),
		         Field::make( "text", "order_promo_code", "Промокод" )->set_width( 25 ),
		         Field::make( "text", "order_promo_code_id", "ID Промокода" )->set_width( 25 ),
		         Field::make( "text", "order_promo_code_contacts", "Контакты Промокода" )->set_width( 25 ),
		         Field::make( "text", "order_promo_code_discount", "Скидка Промокода" )->set_width( 25 ),
		         Field::make( "text", "order_total_sum", "Сумма со скидкой" ),
	         ) )
	         ->add_tab( 'Участники', array(
		         Field::make( 'complex', 'order_products', 'Участники' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'name', __( 'Имя' ) )->set_width( 20 ),
			              Field::make( 'text', 'country', __( 'Страна' ) )->set_width( 20 ),
			              Field::make( 'text', 'phone', __( 'Телефон' ) )->set_width( 20 ),
			              Field::make( 'text', 'messenger', __( 'Метод комуникации' ) )->set_width( 20 ),
			              Field::make( 'text', 'email', __( 'E-mail' ) )->set_width( 20 )->set_classes( 'preparation-email' ),
			              Field::make( 'association', 'message_template', 'Шаблон' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'message_template',
				                   )
			                   ) )->set_width( 50 ),
			              Field::make( 'html', 'send_template_button', '' )
			                   ->set_html( '<button type="button" class="send-template-btn">Отправить шаблон</button>' )->set_width( 50 ),
		              ) ),

	         ) )
	         ->add_tab( 'Подготовка', array(
		         Field::make( 'complex', 'preparation_info', 'Подготовка' )
		              ->setup_labels( $labels )
		              ->set_classes( 'preparation-item' )
		              ->add_fields( array(
			              Field::make( 'text', 'preparation_title', 'Тема' )->set_width( 100 )->set_required( true ),
			              Field::make( 'rich_text', 'preparation_value', 'Описание' )->set_width( 100 )->set_required( true ),
			              Field::make( 'text', 'preparation_value_sum', 'Сумма' )->set_width( 100 )->set_classes( 'preparation-item-sum' )->set_required( true ),
			              Field::make( 'date', 'preparation_date', 'Дата записи' )->set_width( 33 )->set_required( true ),
			              Field::make( 'text', 'preparation_author', 'Автор' )->set_width( 33 )->set_required( true ),
			              Field::make( 'html', 'preparation_button' )->set_width( 33 )->set_html( '<button class="preparation_button">Сохранить</button>' ),

		              ) ),
	         ) )
	         ->add_tab( 'Итоги', array(
		         Field::make( "text", "total_order_sum", "Общая сумма заказа" )
		              ->set_classes( 'total_order_sum' ),

	         ) )->set_classes( 'preparation-user' );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_promo' );
function crb_attach_in_promo() {
	$labels = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);

	Container::make( 'post_meta', 'Информация ' )
	         ->where( 'post_type', '=', 'promocode' )
	         ->add_tab( 'Основная информация', array(
		         Field::make( "text", "promo_code_percent", "Значение скидки, %" )->set_required( true )
		              ->set_attribute( 'min', '1' )
		              ->set_attribute( 'step', '0.1' )
		              ->set_attribute( 'type', 'number' )
		              ->set_attribute( 'max', '80' ),
		         Field::make( "text", "promo_code_user_name", "Имя пользователя" ),
		         Field::make( "text", "promo_code_user_country", "Страна пользователя" ),
		         Field::make( "text", "promo_code_user_email", "Email пользователя" ),
		         Field::make( "text", "promo_code_user_tel", "Телефон пользователя" ),
	         ) )
	         ->add_tab( 'Настройки', array(
		         Field::make( "checkbox", "promo_code_set_contacts_required", "Email и Телефон должны совпадать в заказе" ),
		         Field::make( "date_time", "promo_code_start", "Начало действия" )->set_width( 50 ),
		         Field::make( "date_time", "promo_code_finish", "Окончание действия" )->set_width( 50 ),
		         Field::make( "text", "promo_code_limit", "Лимит использования" )
		              ->set_attribute( 'type', 'number' )
		              ->set_attribute( 'step', '1' )
		              ->set_attribute( 'min', '1' ),
	         ) )
	         ->add_tab( 'Заказ', array(
		         Field::make( "text", "promo_code_order", "ID заказа" ),
	         ) );

	Container::make( 'theme_options', "Настройки промокодов" )
	         ->set_page_parent( 'edit.php?post_type=promocode' )
	         ->add_tab( 'Скидка', array(
		         Field::make( 'text', 'promo_codes_percent', 'Скидка по-умолчанию' )
		              ->set_attribute( 'type', 'number' )
		              ->set_attribute( 'step', '0.1' )
		              ->set_attribute( 'min', '1' )
		              ->set_attribute( 'max', '80' ),
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_message_template' );

function crb_attach_in_message_template() {

	Container::make( 'post_meta', 'Информация' )
	         ->where( 'post_type', '=', 'message_template' )
	         ->add_fields( array(
		         Field::make( 'html', 'crb_information_text', 'Подсказки' )
		              ->set_html( get_mail_hints() )
	         ) );

	Container::make( 'theme_options', "Настройки писем" )
	         ->set_page_parent( 'edit.php?post_type=message_template' )
	         ->add_tab( 'Купон', array(
		         Field::make( 'association', 'promo_code_mail', __( 'Письмо промокода' ) )
		              ->set_max( 1 )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'message_template',
			              )
		              ) )
	         ) );
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	get_template_part( 'vendor/autoload' );
	\Carbon_Fields\Carbon_Fields::boot();
}

add_filter( 'crb_media_buttons_html', function ( $html, $field_name ) {
	if (
		$field_name === 'text' ||
		$field_name === 'subtitle' ||
		$field_name === 'crb_pp_title' ||
		$field_name === 'thanks_title' ||
		$field_name === 'modal1_title' ||
		$field_name === 'description' ||
		$field_name === 'title1' ||
		$field_name === 'table' ||
		$field_name === 'text_after' ||
		$field_name === 'text_before' ||
		$field_name === 'description_in_front_page' ||
		$field_name === 'title'
	) {
		return;
	}

	return $html;
}, 10, 2 );

function get_mail_hints(): string {
	return "
	<strong>$%coupon%</strong>-значение купона<br>	
	<strong>$%coupon_discount%</strong>- значение скидки купона<br>	
	<strong>$%coupon_name%</strong>-имя пользователя купона <br>	
	<strong>$%coupon_country%</strong>-страна пользователя купона <br>	
	<strong>$%coupon_email%</strong>-email пользователя купона <br>	
	<strong></strong>- <br>	
	";
}