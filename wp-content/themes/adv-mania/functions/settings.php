<?php
add_theme_support('post-thumbnails');

add_action('after_setup_theme',
    function () {
        register_nav_menus(
            array('header_menu' => 'Header menu')
        );
    }
);

add_filter('get_the_archive_title', function ($title) {
    return preg_replace('~^[^:]+: ~', '', $title);
});

function onwp_disable_content_editor()
{

    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];

    if (!isset($post_id)) return;

    $template_file = get_post_meta($post_id, '_wp_page_template', true);

    if (
        $template_file == 'index.php'
    )
    {
        remove_post_type_support('page', 'editor');
    }

}

add_action('admin_init', 'onwp_disable_content_editor');

add_filter('wpcf7_autop_or_not', '__return_false');

//add_filter('wpcf7_form_elements', function($content) {
//    $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
//
//    $content = str_replace('<br />', '', $content);
//
//    return $content;
//});

if(0){
    add_action('init', 'add_post_thumbs_in_post_list_table', 20 );
    function add_post_thumbs_in_post_list_table(){
        // проверим какие записи поддерживают миниатюры
        $supports = get_theme_support('post-thumbnails');

        $ptype_names = array('portfolio', 'team'); // указывает типы для которых нужна колонка отдельно

        // Определяем типы записей автоматически
        if( ! isset($ptype_names) ){
            if( $supports === true ){
                $ptype_names = get_post_types(array( 'public'=>true ), 'names');
                $ptype_names = array_diff( $ptype_names, array('attachment') );
            }
            // для отдельных типов записей
            elseif( is_array($supports) ){
                $ptype_names = $supports[0];
            }
        }

        // добавляем фильтры для всех найденных типов записей
        foreach( $ptype_names as $ptype ){
            add_filter( "manage_{$ptype}_posts_columns", 'add_thumb_column' );
            add_action( "manage_{$ptype}_posts_custom_column", 'add_thumb_value', 10, 2 );
        }
    }

    // добавим колонку
    function add_thumb_column( $columns ){
        // подправим ширину колонки через css
        add_action('admin_notices', function(){
            echo '
			<style>
				.column-thumbnail{ width:90px; text-align:center; }
			</style>';
        });

        $num = 1; // после какой по счету колонки вставлять новые

        $new_columns = array( 'thumbnail' => __('Thumbnail') );

        return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
    }

    // заполним колонку
    function add_thumb_value( $colname, $post_id ){
        if( 'thumbnail' == $colname ){
            $width  = $height = 55;

            // миниатюра
            if( $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true ) ){
                $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
            }
            // из галереи...
            elseif( $attachments = get_children( array(
                'post_parent'    => $post_id,
                'post_mime_type' => 'image',
                'post_type'      => 'attachment',
                'numberposts'    => 1,
                'order'          => 'DESC',
            ) ) ){
                $attach = array_shift( $attachments );
                $thumb = wp_get_attachment_image( $attach->ID, array($width, $height), true );
            }elseif (function_exists('carbon_get_post_meta') && $img = carbon_get_post_meta($post_id, 'icon')) {
                $thumb = wp_get_attachment_image($img, array($width, $height), true );
            }

            echo empty($thumb) ? ' ' : $thumb;
        }
    }
}

//add_action('admin_footer-edit.php', 'add_status_to_pages');

function add_status_to_pages()
{

    $terms_and_conditions_page = carbon_get_theme_option('terms_and_conditions_page')[0]['id'] ?: 0;

    echo "<script>
	jQuery(document).ready( function($) {
		$( '#post-' + $terms_and_conditions_page ).find('strong').append( ' — Страница условий и положений' );
	});
	</script>";
}
add_action('init', 'order_init');
function order_init(){
    register_post_type('orders', array(
        'labels'             => array(
            'name'               => 'Заказы', 
            'singular_name'      => 'Заказы', 
            'add_new'            => 'Добавить Заказ',
            'add_new_item'       => 'Добавить новый Заказ',
            'edit_item'          => 'Редактировать Заказ',
            'new_item'           => 'Новый Заказ',
            'view_item'          => 'Посмотреть Заказ',
            'search_items'       => 'Найти Заказ',
            'not_found'          => 'Заказов не найдено',
            'not_found_in_trash' => 'В корзине заказов не найдено',
            'parent_item_colon'  => '',
            'menu_name'          => 'Заказы'

        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'menu_icon'          => 'dashicons-cart',
        'rewrite'            => true,
        'capability_type'    => 'post',
        'capabilities'       => array(
            'edit_post'          => 'edit_order',
            'read_post'          => 'read_order',
            'delete_post'        => 'delete_order',
            'edit_posts'         => 'edit_orders',
            'edit_others_posts'  => 'edit_others_orders',
            'publish_posts'      => 'publish_orders',
            'read_private_posts' => 'read_private_orders',
        ),
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title','thumbnail',  'author', 'comments')
    ) );
}
add_action('admin_menu', 'add_user_menu_bubble');

function add_user_menu_bubble() {
    global $menu;

    // Перевірка, чи є користувач адміністратором
    if (current_user_can('administrator')) {
        $countOrder = wp_count_posts('orders')->pending; // на підтвердженні
        if ($countOrder) {
            foreach ($menu as $key => $value) {
                // Перевірка, чи є елемент меню для типу поста 'orders'
                if ($menu[$key][2] == 'edit.php?post_type=orders') {
                    // Додавання індикатора з кількістю замовлень на підтвердженні
                    $menu[$key][0] .= '<span class="awaiting-mod"><span class="pending-count">' . $countOrder . '</span></span>';
                    break;
                }
            }
        }
    }
}


add_action('wp_ajax_send_email', 'send_email');
add_action('wp_ajax_nopriv_send_email', 'send_email');
function send_email() {
    if (isset($_POST['form_data'])) {
        parse_str($_POST['form_data'], $data);
        $name = sanitize_text_field($data['name']);
        $phone = sanitize_text_field($data['phone']);
        $email = sanitize_email($data['email']);
        $to = 'devsequencce@gmail.com'; 
        $subject = 'New Form Submission';
        $message = '
        <html>
        <head>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
            <h2>Form Submission</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <td>' . esc_html($name) . '</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>' . esc_html($phone) . '</td>
                </tr>
                <tr>
                    <th>Email</th>  
                    <td>' . esc_html($email) . '</td>
                </tr>
            </table>
        </body>
        </html>';

        $headers = 'From: ' . $email . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

        if (wp_mail($to, $subject, $message, $headers)) {
            wp_send_json_success('Email sent successfully.');
        } else {
            wp_send_json_error('Email sending failed.');
        }
    }
    wp_die(); 
}

// Додаємо синхронізацію  bike

function add_bike_stock_meta_box() {
    add_meta_box(
        'bike_stock_meta_box',
        'Информация о мотоцикле',
        'bike_stock_meta_box_callback',
        'bike', 
        'side',
        'high' 
    );
}
add_action('add_meta_boxes', 'add_bike_stock_meta_box');
function bike_stock_meta_box_callback($post) {
    wp_nonce_field('save_bike_stock', 'bike_stock_nonce');
    $bike_stock = get_post_meta($post->ID, '_bike_stock', true);
    $bike_price = get_post_meta($post->ID, '_bike_price', true);
    $bike_price_old = get_post_meta($post->ID, '_bike_price_old', true);
    $bike_price_percent = get_post_meta($post->ID, '_bike_price_percent', true);
    $info_price_percent = get_post_meta($post->ID, '_info_price_percent', true);
    echo '<label for="bike_stock">Количество Мотоциклов:</label>';
    echo '<input type="number" id="bike_stock" name="bike_stock" value="' . esc_attr($bike_stock) . '" />';
    echo '<hr>';
    echo '<label for="bike_price">Цена Мотоцикла:</label>';
    echo '<input type="text" id="bike_price" name="bike_price" value="' . esc_attr($bike_price) . '" />';
    echo '<hr>';
    echo '<label for="bike_price_old">Стара Цена Мотоцикла:</label>';
    echo '<input type="text" id="bike_price_old" name="bike_price_old" value="' . esc_attr($bike_price_old) . '" />';
    echo '<hr>';
    echo '<label for="bike_price_percent">Цена процент:</label>';
    echo '<input type="text" id="bike_price_percent" name="bike_price_percent" value="' . esc_attr($bike_price_percent) . '" />';
    echo '<hr>';
    echo '<label for="info_price_percent">Информация по процентам</label>';
    echo '<input type="text" id="info_price_percent" name="info_price_percent" value="' . esc_attr($info_price_percent) . '" />';
}

function save_bike_stock_meta($post_id) {
    if (!isset($_POST['bike_stock_nonce']) || !wp_verify_nonce($_POST['bike_stock_nonce'], 'save_bike_stock')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (get_post_type($post_id) != 'bike') return $post_id;
    if (isset($_POST['bike_stock'])) {
        update_post_meta($post_id, '_bike_stock', absint($_POST['bike_stock']));
    }
    if (isset($_POST['bike_price'])) {
        update_post_meta($post_id, '_bike_price', sanitize_text_field($_POST['bike_price']));
    }
    if (isset($_POST['bike_price_old'])) {
        update_post_meta($post_id, '_bike_price_old', sanitize_text_field($_POST['bike_price_old']));
    }
    if (isset($_POST['bike_price_percent'])) {
        update_post_meta($post_id, '_bike_price_percent', sanitize_text_field($_POST['bike_price_percent']));
    }
    if (isset($_POST['info_price_percent'])) {
        update_post_meta($post_id, '_info_price_percent', sanitize_text_field($_POST['info_price_percent']));
    }
}
add_action('save_post', 'save_bike_stock_meta');

function sync_bike_to_database($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (get_post_type($post_id) != 'bike') return $post_id;
    error_log("sync_bike_to_database called for post ID: $post_id");
    $name = get_the_title($post_id); // Назва мотоцикла
    $available = get_post_meta($post_id, '_bike_stock', true); // Кількість доступних мотоциклів
    $price = get_post_meta($post_id, '_bike_price', true); // Ціна
    $description = get_post_meta($post_id, '_bike_description', true); // Опис
    $price_old = get_post_meta($post_id, '_bike_price_old', true); // Стара ціна (якщо є)
    error_log("Bike Name: $name, Available: $available, Price: $price, Old Price: $price_old");
    if (empty($available) || empty($price)) {
        error_log("Bike stock or price is missing for post ID: $post_id");
        return $post_id; // Якщо немає даних для кількості або ціни, не оновлюємо базу
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'bike';
    $existing_bike = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE post_id = %d", $post_id
    ));
    if ($existing_bike) {
        error_log("Updating existing bike data for post ID: $post_id");
        $wpdb->update(
            $table_name,
            array(
                'name' => $name,
                'available' => (int) $available,
                'price' => (float) $price,
                'price_old' => (float) $price_old,
                'description' => 'test'
            ),
            array('post_id' => $post_id),
            array('%s', '%d', '%f', '%f', '%s'),
            array('%d')
        );
    } else {
        error_log("Inserting new bike data for post ID: $post_id");
        $wpdb->insert(
            $table_name,
            array(
                'post_id' => $post_id,
                'name' => $name,
                'available' => (int) $available,
                'price' => (float) $price,
                'price_old' => (float) $price_old,
                'description' => 'test'
            ),
            array('%d', '%s', '%d', '%f', '%f', '%s')
        );
    }

    return $post_id;
}
add_action('save_post', 'sync_bike_to_database');

add_action('wp_ajax_get_booked_dates_moto', 'get_booked_dates_moto');
add_action('wp_ajax_nopriv_get_booked_dates_moto', 'get_booked_dates_moto');

function get_booked_dates_moto() {
    global $wpdb;

    // Перевірка на наявність необхідного параметра в GET-запиті
    if (empty($_GET['tour_id'])) {
        wp_send_json_error(['message' => 'tour_id не передано']);
        return;
    }

    $tour_bike_id = intval($_GET['tour_id']);

    // Перевірка, чи є такий ідентифікатор
    if (!$tour_bike_id) {
        wp_send_json_error(['message' => 'Невірний ID турного мотоцикла']);
        return;
    }

    // Отримуємо загальну кількість доступних мотоциклів для цього турного байка
    $table_name = $wpdb->prefix . 'bookings';
    $bike = $wpdb->get_row(
        $wpdb->prepare("SELECT count_total FROM $table_name WHERE tour_id = %d LIMIT 1", $tour_bike_id)
    );

    if (!$bike) {
        wp_send_json_error(['message' => 'Мотоцикл не знайдений']);
        return;
    }

    // Отримуємо всі заброньовані дати для цього турного байка
    $booked_dates = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT start_date, end_date, count FROM {$wpdb->prefix}bookings WHERE tour_id = %d ORDER BY start_date ASC",
            $tour_bike_id
        )
    );

    if (empty($booked_dates)) {
        wp_send_json_success([ // Якщо немає заброньованих дат
            'data' => [
                'booked_dates' => [],
                'total_bikes' => $bike->count_total
            ]
        ]);
        return;
    }

    $booked_dates_array = [];
    $booked_counts = [];
    $booked_counts_per_date = [];  // Додамо масив для зберігання кількості заброньованих мотоциклів для кожної дати

    // Проходимо через всі бронювання і обчислюємо сумарну кількість заброньованих мотоциклів для кожної дати
    foreach ($booked_dates as $booking) {
        $current_date = new DateTime($booking->start_date);
        $end_date = new DateTime($booking->end_date);
        $count = intval($booking->count); // Конвертуємо в integer

        // Генеруємо діапазон дат і додаємо їх до масиву
        while ($current_date <= $end_date) {
            $date_str = $current_date->format('Y-m-d');
            
            // Якщо дата вже є, додаємо кількість до існуючої
            if (isset($booked_counts[$date_str])) {
                $booked_counts[$date_str] += $count;
            } else {
                $booked_counts[$date_str] = $count;
            }

            // Зберігаємо кількість заброньованих мотоциклів на кожну дату
            $booked_counts_per_date[$date_str] = $booked_counts[$date_str];

            $current_date->modify('+1 day');
        }
    }

    // Тепер фільтруємо лише ті дати, де сумарна кількість заброньованих мотоциклів дорівнює або перевищує count_total
    foreach ($booked_counts as $date => $total_booked) {
        if ($total_booked >= $bike->count_total) {
            $booked_dates_array[] = $date;
        }
    }

    wp_send_json_success([  // Відправляємо результат
        'data' => [
            'booked_dates' => $booked_dates_array,
            'total_bikes' => $bike->count_total,
            'total_bikes_booked' => $booked_counts_per_date, // Повертаємо кількість заброньованих мотоциклів для кожної дати
        ]
    ]);
}


// Обробка створення бронювання мотоциклів
add_action('wp_ajax_nopriv_create_motorcycle_booking', 'create_motorcycle_booking');
add_action('wp_ajax_create_motorcycle_booking', 'create_motorcycle_booking');
function create_motorcycle_booking() {
    global $wpdb;
    $moto_id = isset($_POST['booking_data']['moto_id']) ? intval($_POST['booking_data']['moto_id']) : 0;
    $moto_count = isset($_POST['booking_data']['moto_count']) ? intval($_POST['booking_data']['moto_count']) : 0;
    $moto_count_total = isset($_POST['booking_data']['moto_count_total']) ? intval($_POST['booking_data']['moto_count_total']) : 0;
    $order_start = sanitize_text_field($_POST['booking_data']['order_start']);
    $order_end = sanitize_text_field($_POST['booking_data']['order_end']);
    $order_sum = floatval($_POST['booking_data']['order_sum']);
    $payment_status = sanitize_text_field($_POST['booking_data']['payment_status']);

    // Перевірка наявності мотоцикла по post_id
    $table_name = $wpdb->prefix . 'bike';
    $motorcycle = $wpdb->get_row($wpdb->prepare(
        "SELECT id, available FROM $table_name WHERE post_id = %d",  // Використовуємо post_id, а не id
        $moto_id
    ));

    if (!$motorcycle) {
        wp_send_json_error(['message' => 'Мотоцикл не знайдений']);
    }

    // Перевірка залишку мотоциклів
    $available = (int) $motorcycle->available; // Приводимо до числа
    if ($available < $moto_count) {
        wp_send_json_error([
            'message' => 'Недостатньо мотоциклів на складі для бронювання'
        ]);
    }

    // Оновлюємо залишок мотоциклів
    // $new_available = $available - $moto_count;
    // $wpdb->update(
    //     $table_name,
    //     ['available' => $new_available],
    //     ['post_id' => $moto_id]  // Використовуємо post_id для оновлення
    // );

    // Створюємо нове бронювання в таблиці bookings
    $data = array(
        'tour_id' => $moto_id,
        'start_date' => $order_start,
        'end_date' => $order_end,
        'count' => $moto_count,
        'count_total' => $moto_count_total,
        'tour_amount' => $order_sum / 100,
        'payment_status' => $payment_status
    );

    // Створюємо нове бронювання в таблиці bookings
        $table_name = $wpdb->prefix . 'bookings';
        $inserted = $wpdb->insert($table_name, $data);

        // Перевіряємо на помилки
        if ($inserted === false) {
            // Вивести помилку SQL запиту
            error_log('Помилка вставки в bookings: ' . $wpdb->last_error);
            wp_send_json_error([
                'message' => 'Не вдалося створити бронювання',
                'error' => $wpdb->last_error  // Додатково виводимо текст помилки для налагодження
            ]);
        } else {
            wp_send_json_success([
                'message' => 'Бронювання успішно створено'
            ]);
        }

    die();
}


function sync_tour_to_database($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (get_post_type($post_id) != 'tour') return $post_id;

    error_log("sync_tour_to_database triggered for post ID: $post_id");

    $infoDates = carbon_get_post_meta($post_id, 'list_date');

    error_log("list_date data: " . print_r($infoDates, true));

    if (empty($infoDates)) {
        error_log("No dates found for post ID: $post_id");
        return $post_id;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'tour_dates';

    // Видаляємо старі записи для посту
    $deleted_rows = $wpdb->delete($table_name, array('post_id' => $post_id), array('%d'));
    error_log("Deleted $deleted_rows old rows for post ID: $post_id");

    foreach ($infoDates as $item) {
        if (isset($item['date_start'], $item['date_end'])) {
            $start_date = new DateTime($item['date_start']);
            $end_date = new DateTime($item['date_end']);
            $repeat_monthly = isset($item['repeat_monthly']) ? (int) $item['repeat_monthly'] : 0;
            $repeat_count = isset($item['repeat_count']) ? (int) $item['repeat_count'] : 1;

            // Завжди додаємо перший запис
            for ($i = 0; $i < ($repeat_monthly ? $repeat_count : 1); $i++) {
                if ($i > 0) {
                    // Якщо це повторення, додаємо місяць до дати
                    $start_date->modify('+1 month');
                    $end_date->modify('+1 month');
                }

                $wpdb->insert(
                    $table_name,
                    array(
                        'post_id' => $post_id,
                        'start_date' => $start_date->format('Y-m-d'),
                        'end_date' => $end_date->format('Y-m-d'),
                        'repeat_monthly' => $repeat_monthly,
                        'repeat_count' => $repeat_count
                    ),
                    array('%d', '%s', '%s', '%d', '%d')
                );

                if ($wpdb->last_error) {
                    error_log("Database insert error: " . $wpdb->last_error);
                } else {
                    error_log("Inserted tour date: " . $start_date->format('Y-m-d') . " - " . $end_date->format('Y-m-d'));
                }
            }
        }
    }

    return $post_id;
}

add_action('save_post_tour', 'sync_tour_to_database');


function add_full_capabilities_to_admin() {
    $role = get_role('administrator');
    if ($role) {
        $capabilities = [
            'edit_order',
            'read_order',
            'delete_order',
            'edit_orders',
            'edit_others_orders',
            'publish_orders',
            'read_private_orders',
            'delete_orders',
            'delete_private_orders',
            'delete_published_orders',
            'delete_others_orders',
            'edit_private_orders',
            'edit_published_orders',
        ];

        foreach ($capabilities as $cap) {
            $role->add_cap($cap);
        }
    }
}
add_action('admin_init', 'add_full_capabilities_to_admin');

function add_custom_admin_styles() {
    $screen = get_current_screen();
    if ($screen->post_type === 'orders') {
        wp_enqueue_style('custom-admin-styles', get_template_directory_uri() . '/assets/css/admin-orders.css');
    }
}
add_action('admin_enqueue_scripts', 'add_custom_admin_styles');
function enqueue_admin_scripts() {
    wp_enqueue_script( 'custom-admin-script', get_template_directory_uri() . '/assets/js/custom-admin.js', array( 'jquery' ), '1.0.0', true);

    wp_enqueue_script( 'admin-preparation-changes', get_template_directory_uri() . '/assets/js/admin-preparation-changes.js', ['jquery'], null, true );

    wp_localize_script('admin-preparation-changes', 'ajaxPreparationChanges', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );


function sync_order_to_database($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (get_post_type($post_id) != 'orders') return $post_id;
    $order_products = carbon_get_post_meta($post_id, 'order_products');
    $order_moto = carbon_get_post_meta($post_id, 'order_moto');
    $order_equipment = carbon_get_post_meta($post_id, 'order_equipment');
    $order_tour_name = carbon_get_post_meta($post_id, 'order_order_tour');
    $order_start = carbon_get_post_meta($post_id, 'order_order_start');
    $order_end = carbon_get_post_meta($post_id, 'order_order_end');
    $order_sum = carbon_get_post_meta($post_id, 'order_sum');
    $order_country = carbon_get_post_meta($post_id, 'order_country');
    $order_type = carbon_get_post_meta($post_id, 'order_type');
    global $wpdb;
    $table_name = $wpdb->prefix . 'bookings';
    $existing_order = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE post_id = %d", $post_id
    ));
    $data = [
        'post_id' => $post_id,
        'tour_name' => $order_tour_name,
        'start_date' => $order_start,
        'end_date' => $order_end,
        'sum' => (float) $order_sum,
        'country' => $order_country,
        'order_type' => $order_type,
        'products' => json_encode($order_products),
        'moto' => json_encode($order_moto),
        'equipment' => json_encode($order_equipment),
    ];

    $formats = ['%d', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s'];
    if ($existing_order) {
        $wpdb->update($table_name, $data, ['post_id' => $post_id], $formats, ['%d']);
    } else {
        $wpdb->insert($table_name, $data, $formats);
    }
    return $post_id;
}
add_action('save_post', 'sync_order_to_database');

//manager 
require_once get_template_directory() . '/functions/inc/roles.php';
require_once get_template_directory() . '/functions/inc/restrict-visibility.php';
require_once get_template_directory() . '/functions/inc/admin-changes.php';

add_action('carbon_fields_register_fields', function() {
    if (!current_user_can('administrator')) {
        add_filter('carbon_fields_field_container_preparation_info', function() {
            return null; // Видаляємо вкладку для ролі менеджера
        });
    }
});



add_action('wp_ajax_get_message_template', 'get_message_template');
function get_message_template() {
    if (!isset($_POST['template_value']) || empty($_POST['template_value'])) {
        wp_send_json_error(['message' => 'Невірне значення шаблону.']);
    }

    $template_value = sanitize_text_field($_POST['template_value']);
    preg_match('/post:(.*?):(\d+)/', $template_value, $matches);

    if (!$matches || count($matches) < 3) {
        wp_send_json_error(['message' => 'Шаблон не знайдено.']);
    }

    $post_type = $matches[1];
    $post_id = intval($matches[2]);

    if ($post_type !== 'message_template') {
        wp_send_json_error(['message' => 'Непідтримуваний тип шаблону.']);
    }

    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'message_template') {
        wp_send_json_error(['message' => 'Шаблон не знайдено.']);
    }

    // Очищаємо контент від зайвих класів і стилів
    $post_content = wp_kses_post($post->post_content); // Очищає контент від непотрібного HTML

    // Генерація HTML-шаблону
    $html_template = '
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . esc_html($post->post_title) . '</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { background-color: #f9f9f9; font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
            .email-container { max-width: 600px; margin: 20px auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .header img { max-width: 100px; margin-bottom: 20px; }
            .content { margin: 20px 0; }
            .footer { margin-top: 20px; font-size: 0.9em; color: #777; text-align: center; }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <img src="https://adv-mania-wp.web-mosaica.top/wp-content/uploads/2024/10/cropped-fav-180x180.png" alt="Логотип">
            </div>
            <div class="content">
                ' . $post_content . '
            </div>
            <div class="footer">
                <p>© 2024 Your Company. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';

    // Застосовуємо фільтр для встановлення контенту в HTML
    add_filter('wp_mail_content_type', function() {
        return 'text/html'; // Встановлюємо формат повідомлення як HTML
    });

    // Відправка електронної пошти
    $to = 'fear3494@gmail.com'; // Замість цього використовуйте фактичну електронну адресу
    $subject = $post->post_title;
    wp_mail($to, $subject, $html_template);

    
    wp_send_json_success([
        'subject' => $post->post_title,
        'content' => $html_template,
    ]);

    // Після відправки скидаємо фільтр
    
}




add_action('wp_ajax_send_email_with_template', 'send_email_with_template');

function send_email_with_template() {
    if (isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['content'])) {
        $to = sanitize_email($_POST['to']);
        $subject = sanitize_text_field($_POST['subject']);
        $email_content = wp_kses_post($_POST['content']);

        error_log("Email to: $to");
        error_log("Subject: $subject");
        error_log("Content: $email_content");

        if (wp_mail($to, $subject, $email_content)) {
            wp_send_json_success();
        } else {
            error_log('wp_mail не вдалося відправити лист.');
            wp_send_json_error(['message' => 'Не вдалося відправити лист.']);
        }
    } else {
        error_log('Недостатньо даних для відправки листа.');
        wp_send_json_error(['message' => 'Недостатньо даних для відправки.']);
    }
}
