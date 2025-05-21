<?php 
add_action('wp_ajax_get_booked_dates', 'get_booked_dates');
add_action('wp_ajax_nopriv_get_booked_dates', 'get_booked_dates');

function get_booked_dates() {
    global $wpdb;

    $tour_id = intval($_GET['tour_id']);
    $bookings = $wpdb->get_results($wpdb->prepare("
        SELECT start_date, end_date FROM {$wpdb->prefix}bookings
        WHERE tour_id = %d
    ", $tour_id));

    $booked_dates = [];
    foreach ($bookings as $booking) {
        $current_date = strtotime($booking->start_date);
        $end_date = strtotime($booking->end_date);
        
        while ($current_date <= $end_date) {
            $booked_dates[] = date('Y-m-d', strtotime("-1 day", $current_date));
            $current_date = strtotime("+1 day", $current_date);
        }
    }

    wp_send_json_success(['booked_dates' => $booked_dates]);
}
add_action('wp_ajax_get_booked_dates_tour', 'get_booked_dates_tour');
add_action('wp_ajax_nopriv_get_booked_dates_tour', 'get_booked_dates_tour');

function get_booked_dates_tour() {
    global $wpdb;

    $tour_id = intval($_GET['tour_bike_id']);
    $bookings = $wpdb->get_results($wpdb->prepare("
        SELECT start_date, end_date FROM {$wpdb->prefix}tour_dates
        WHERE tour_bike_id = %d
    ", $tour_id));

    $booked_dates = [];
    foreach ($bookings as $booking) {
        $current_date = strtotime($booking->start_date);
        $end_date = strtotime($booking->end_date);
        
        while ($current_date <= $end_date) {
            $booked_dates[] = date('Y-m-d', strtotime("-1 day", $current_date));
            $current_date = strtotime("+1 day", $current_date);
        }
    }

    wp_send_json_success(['booked_dates' => $booked_dates]);
}



add_action('wp_ajax_get_available_bikes', 'get_available_bikes');
add_action('wp_ajax_nopriv_get_available_bikes', 'get_available_bikes');

function get_available_bikes() {
    global $wpdb;
    $moto_tour_data = json_decode(stripslashes($_POST['moto_tour_data']), true);
    $available_bikes = [];
    $available_equipment = [];

    foreach ($moto_tour_data as $tour) {
        $bike_id = sanitize_text_field($tour['bikeData']);
        $equipment_id = sanitize_text_field($tour['equipmentData']);
        $start_date = sanitize_text_field($tour['start_date']);
        $end_date = sanitize_text_field($tour['end_date']);

        // Check for booked bikes
        $booked_bikes = $wpdb->get_results($wpdb->prepare(
            "SELECT tour_bike_id FROM {$wpdb->prefix}bookings WHERE tour_bike_id = %s AND (
                (start_date <= %s AND end_date >= %s) OR
                (start_date BETWEEN %s AND %s) OR
                (end_date BETWEEN %s AND %s)
            )",
            $bike_id, $end_date, $start_date, $start_date, $end_date, $start_date, $end_date
        ));

        if (empty($booked_bikes)) {
            $bike_post = get_post($bike_id);
            if ($bike_post) {
                $_img = get_the_post_thumbnail_url($bike_id) ?: '';
                $result_item = '<div class="item" data-id="' . $bike_id . '" data-title="' . esc_html($bike_post->post_title) . '">';
                $result_item .= '<div class="item-media"><a href="#"><img src="' . esc_url($_img) . '" alt="Bike"></a></div>';
                $result_item .= '<div class="item-desc"><div class="item-title"><a href="#">' . esc_html($bike_post->post_title) . '</a></div>';
                $new_price = carbon_get_post_meta($bike_id, 'new_price');
                $new_price_old = carbon_get_post_meta($bike_id, 'new_price_old');
                if ($new_price) {
                    $result_item .= '<div class="item-bottom">';
                    $result_item .= '<span class="item-price">' . esc_html($new_price) . '</span>';
                    if ($new_price_old) {
                        $result_item .= '<span class="item-price__old">' . esc_html($new_price_old) . '</span>';
                    }
                    $result_item .= '<a href="' . esc_url(get_the_permalink($bike_id)) . '" class="item-book btn btn-red">' . pll__('Book') . '</a>';
                    $result_item .= '</div>';
                }
                $result_item .= '</div></div>';
                $available_bikes[] = $result_item;
            }
        }

        // Check for booked equipment
        $booked_equipment = $wpdb->get_results($wpdb->prepare(
            "SELECT equipment_id FROM {$wpdb->prefix}bookings WHERE equipment_id = %s AND (
                (start_date <= %s AND end_date >= %s) OR
                (start_date BETWEEN %s AND %s) OR
                (end_date BETWEEN %s AND %s)
            )",
            $equipment_id, $end_date, $start_date, $start_date, $end_date, $start_date, $end_date
        ));

        if (empty($booked_equipment)) {
            $equipment_post = get_post($equipment_id);
            if ($equipment_post) {
                $_img = get_the_post_thumbnail_url($equipment_id) ?: '';
                $result_item = '<div class="item" data-id="' . $equipment_id . '" data-title="' . esc_html($equipment_post->post_title) . '">';
                $result_item .= '<div class="item-media"><a href="#"><img src="' . esc_url($_img) . '" alt="Equipment"></a></div>';
                $result_item .= '<div class="item-desc"><div class="item-title"><a href="#">' . esc_html($equipment_post->post_title) . '</a></div>';
                $new_price = carbon_get_post_meta($equipment_id, 'new_price');
                $new_price_old = carbon_get_post_meta($equipment_id, 'new_price_old');
                if ($new_price) {
                    $result_item .= '<div class="item-bottom">';
                    $result_item .= '<span class="item-price">' . esc_html($new_price) . '</span>';
                    if ($new_price_old) {
                        $result_item .= '<span class="item-price__old">' . esc_html($new_price_old) . '</span>';
                    }
                    $result_item .= '<a href="' . esc_url(get_the_permalink($equipment_id)) . '" class="item-book btn btn-red">' . pll__('Book') . '</a>';
                    $result_item .= '</div>';
                }
                $result_item .= '</div></div>';
                $available_equipment[] = $result_item;
            }
        }
    }

    $result = '';
    if (empty($available_bikes) && empty($available_equipment)) {
        $result = '<div class="no-bikes"><h4 style="text-align: center;">There are no motorcycles or equipment available for this tour date. Leave a request to find out when the tour is available</h4> <br/>  
        <button type="button" class="btn btn-red btn-popup" style="margin: 0 auto; display: block;" data-popup="info">Leave a request</button></div>';
    } else {
        $result .= '<div class="items items-bikes">' . implode('', $available_bikes) . '</div>';
        $result .= '<div class="items items-equipment">' . implode('', $available_equipment) . '</div>';
    }

    wp_send_json($result);
    wp_die();
}


// add_action('wp_ajax_get_available_bikes', 'get_available_bikes');
// add_action('wp_ajax_nopriv_get_available_bikes', 'get_available_bikes');
// function get_available_bikes() {
//     global $wpdb;
//     $moto_tour_data = json_decode(stripslashes($_POST['moto_tour_data']), true);
//     $available_bikes = [];
//     foreach ($moto_tour_data as $tour) {
//         $bike_id = sanitize_text_field($tour['bikeData']);
//         $equipment_id = sanitize_text_field($tour['equipmentData']);
//         $start_date = sanitize_text_field($tour['start_date']);
//         $end_date = sanitize_text_field($tour['end_date']);
//         $booked_bikes = $wpdb->get_results($wpdb->prepare(
//             "SELECT tour_bike_id FROM {$wpdb->prefix}bookings WHERE tour_bike_id = %s AND (
//                 (start_date <= %s AND end_date >= %s) OR
//                 (start_date BETWEEN %s AND %s) OR
//                 (end_date BETWEEN %s AND %s)
//             )",
//             $bike_id, $end_date, $start_date, $start_date, $end_date, $start_date, $end_date
//         ));
//         if (empty($booked_bikes)) {
//             $bike_post = get_post($bike_id);
//             if ($bike_post) {
//                 $_img = get_the_post_thumbnail_url($bike_id) ?: '';
//                 $result_item = '<div class="item" data-id="' . $bike_id . '" data-title="' . esc_html($bike_post->post_title) . '">';
//                 $result_item .= '<div class="item-media"><a href="#"><img src="' . esc_url($_img) . '" alt="Bike"></a></div>';
//                 $result_item .= '<div class="item-desc"><div class="item-title"><a href="#">' . esc_html($bike_post->post_title) . '</a></div>';
//                 $new_price = carbon_get_post_meta($bike_id, 'new_price');
//                 $new_price_old = carbon_get_post_meta($bike_id, 'new_price_old');
//                 if ($new_price) {
//                     $result_item .= '<div class="item-bottom">';
//                     $result_item .= '<span class="item-price">' . esc_html($new_price) . '</span>';
//                     if ($new_price_old) {
//                         $result_item .= '<span class="item-price__old">' . esc_html($new_price_old) . '</span>';
//                     }
//                     $result_item .= '<a href="' . esc_url(get_the_permalink($bike_id)) . '" class="item-book btn btn-red">' . pll__('Book') . '</a>';
//                     $result_item .= '</div>';
//                 }
//                 $result_item .= '</div></div>';
//                 $available_bikes[] = $result_item;
//             }
//         }
//     }

//     if (empty($available_bikes)) {
//         $result = '<div class="no-bikes"><h4 style="text-align: center;">There are no motorcycles available for this tour date. Leave a request to find out when the tour is available</h4> <br/>  
//         <button type="button" class="btn btn-red btn-popup" style="margin: 0 auto; display: block;" data-popup="info">Leave a request</button></div>';
//     } else {
//         $result = '<div class="items">' . implode('', $available_bikes) . '</div>';
//     }

//     wp_send_json($result);
//     wp_die();
// }

add_action('wp_ajax_get_available_equipment', 'get_available_equipment');
add_action('wp_ajax_nopriv_get_available_equipment', 'get_available_equipment');
function get_available_equipment() {
    global $wpdb;
    $moto_tour_data = json_decode(stripslashes($_POST['moto_tour_data']), true);
    $available_bikes = [];
    foreach ($moto_tour_data as $tour) {
        $bike_id = sanitize_text_field($tour['bikeData']);
        $start_date = sanitize_text_field($tour['start_date']);
        $end_date = sanitize_text_field($tour['end_date']);
        $booked_bikes = $wpdb->get_results($wpdb->prepare(
            "SELECT equipment_id FROM {$wpdb->prefix}bookings WHERE equipment_id = %s AND (
                (start_date <= %s AND end_date >= %s) OR
                (start_date BETWEEN %s AND %s) OR
                (end_date BETWEEN %s AND %s)
            )",
            $bike_id, $end_date, $start_date, $start_date, $end_date, $start_date, $end_date
        ));
        if (empty($booked_bikes)) {
            $bike_post = get_post($bike_id);
            if ($bike_post) {
                $_img = get_the_post_thumbnail_url($bike_id) ?: '';
                $result_item = '<div class="item" data-id="' . $bike_id . '" data-title="' . esc_html($bike_post->post_title) . '">';
                $result_item .= '<div class="item-media"><a href="#"><img src="' . esc_url($_img) . '" alt="Bike"></a></div>';
                $result_item .= '<div class="item-desc"><div class="item-title"><a href="#">' . esc_html($bike_post->post_title) . '</a></div>';
                $new_price = carbon_get_post_meta($bike_id, 'new_price');
                $new_price_old = carbon_get_post_meta($bike_id, 'new_price_old');
                if ($new_price) {
                    $result_item .= '<div class="item-bottom">';
                    $result_item .= '<span class="item-price">' . esc_html($new_price) . '</span>';
                    if ($new_price_old) {
                        $result_item .= '<span class="item-price__old">' . esc_html($new_price_old) . '</span>';
                    }
                    $result_item .= '<a href="' . esc_url(get_the_permalink($bike_id)) . '" class="item-book btn btn-red">' . pll__('Book') . '</a>';
                    $result_item .= '</div>';
                }
                $result_item .= '</div></div>';
                $available_bikes[] = $result_item;
            }
        }
    }

    if (empty($available_bikes)) {
        $result = '<div class="no-bikes"><h4 style="text-align: center;">There are no equipment available for this tour date.</h4></div>';
    } else {
        $result = '<div class="items items-bikes">' . implode('', $available_bikes) . '</div>';
    }

    wp_send_json($result);
    wp_die();
}



add_action('wp_ajax_get_tours_for_date', 'get_tours_for_date');
add_action('wp_ajax_nopriv_get_tours_for_date', 'get_tours_for_date');

function get_tours_for_date() {
    global $wpdb;
    $selected_date = sanitize_text_field($_GET['selected_date']);
    if (!$selected_date) {
        wp_send_json_error(['message' => 'Invalid date provided']);
        return;
    }
    $tours = $wpdb->get_results($wpdb->prepare("
        SELECT DISTINCT post_id 
        FROM {$wpdb->prefix}tour_dates
        WHERE %s BETWEEN start_date AND end_date
    ", $selected_date));
    if (empty($tours)) {
        wp_send_json_success(['html' => '<h4 class="not-tours-available">'. pll_e('No tours available') .'</h4>']);
        return;
    }
    $html = '';
    $cur_lang = function_exists('pll_current_language') ? pll_current_language() : '';
    foreach ($tours as $tour) {
        $post = get_post($tour->post_id);
        if (!$post) continue;
        if ($cur_lang && function_exists('pll_get_post_language') && pll_get_post_language($post->ID) !== $cur_lang) {
            continue;
        }
        
        $image_url = get_the_post_thumbnail_url($post->ID, 'full');
        $price = carbon_get_post_meta($post->ID, 'price');
        $price_old = carbon_get_post_meta($post->ID, 'price_old');
        ob_start(); ?>
        <div class="item" data-aos="fade-up" data-aos-delay="100">
            <div class="item-media">
                <a href="<?php echo get_permalink($post->ID); ?>">
                <?php
                                $terms = get_the_terms($post->ID, 'category-tour');
                                
                                if ($terms && !is_wp_error($terms)) : 
                                    foreach ($terms as $term) :
                                        $class_color = carbon_get_term_meta($term->term_id, 'crb_class_color'); ?>
                                        <span class="label <?php echo esc_attr($class_color); ?>"><?php echo esc_html($term->name); ?></span>
                                    <?php endforeach; 
                                endif; 
                                $termsType = get_the_terms($post->ID, 'tour-type');
                                if ($termsType && !is_wp_error($termsType)) : 
                                    foreach ($termsType as $term) :?>
                                        <span class="label label-right label-red"><?php echo esc_html($term->name); ?></span>
                                    <?php endforeach; 
                                endif; 
                                ?>
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                    <?php endif; ?>
                </a>
            </div>
            <div class="item-desc">
                <div class="item-title"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo esc_html($post->post_title); ?></a></div>
                <?php if ($infoList = carbon_get_post_meta($post->ID, 'list')) : ?>
                                <ul class="item-list">
                                <?php  
                            $infoDates = carbon_get_post_meta($post->ID, 'list_date'); 
                            $ids = 1;
                            $currentDate = new DateTime('today');

                            foreach ($infoDates as $item) :
                                $repeat_monthly = $item['repeat_monthly'];
                                $repeat_count = $item['repeat_count'];
                                $start_date = new DateTime($item['date_start']);
                                $end_date = new DateTime($item['date_end']);
                                
                                if ($start_date < $currentDate) {
                                    continue;
                                }

                                if ($selected_date >= $start_date->format('Y-m-d') && $selected_date <= $end_date->format('Y-m-d')) {
                                    $hiddenClass = ($ids > 1) ? ' hidden' : '';
                                    echo '<li class="date-item"><span>Tour date:</span> <span>' . $start_date->format('Y-m-d') . ' - ' . $end_date->format('Y-m-d') . '</span></li>';
                                }

                                // Якщо є повторення на кожен місяць, додаємо нові дати
                                if ($repeat_monthly && is_numeric($repeat_count) && $repeat_count > 1) {
                                    for ($i = 1; $i < $repeat_count; $i++) {
                                        $start_date->modify('+1 month');
                                        $end_date->modify('+1 month');

                                        // Пропускаємо якщо дата в минулому
                                        if ($start_date < $currentDate) {
                                            continue;
                                        }

                                        if ($selected_date >= $start_date->format('Y-m-d') && $selected_date <= $end_date->format('Y-m-d')) {
                                            $hiddenClass = ' hidden';
                                            echo '<li class="date-item' . $hiddenClass . '"><span>Tour date:</span> <span>' . $start_date->format('Y-m-d') . ' - ' . $end_date->format('Y-m-d') . '</span></li>';
                                        }
                                    }
                                }
                                $ids++;
                            endforeach;
                        ?>

                                <?php foreach ($infoList as $item) : ?>
                                    <li><span><?php echo $item['text_start'] ?></span><span><?php echo $item['text_end'] ?></span></li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                <div class="item-bottom">
                    <span class="item-price__info"><?php echo pll_e('From');?></span>
                    <span class="item-price"><?php echo esc_html($price); ?></span>
                    <?php if ($price_old): ?>
                        <span class="item-price__old"><?php echo esc_html($price_old); ?></span>
                    <?php endif; ?>
                    <a href="<?php echo get_permalink($post->ID); ?>" class="item-book btn btn-red"><?php echo pll_e('View'); ?></a>
                </div>
            </div>
        </div>
        <?php
        $html .= ob_get_clean();
    }

    wp_send_json_success(['html' => $html]);
}


function load_all_posts() {
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date'; 
    $args = array(
        'post_type' => 'tour', 
        'posts_per_page' => 9,
        'post_status' => 'publish',
        'orderby' => ($sort_by === 'title') ? 'title' : 'date', 
        'order' => ($sort_by === 'title') ? 'ASC' : 'DESC', 
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) : $i = 1;
        while ($query->have_posts()): $query->the_post(); 
        $id = get_the_ID();
        ?>
        <div class="item" data-aos="fade-up" data-aos-delay="<?php echo ($i - 1) % 3 + 1;?>00">
                        <div class="item-media">
                            <a href="<?php the_permalink(); ?>">
                            <?php
                                $terms = get_the_terms(get_the_ID(), 'category-tour');
                                
                                if ($terms && !is_wp_error($terms)) : 
                                    foreach ($terms as $term) :
                                        $class_color = carbon_get_term_meta($term->term_id, 'crb_class_color'); ?>
                                        <span class="label <?php echo esc_attr($class_color); ?>"><?php echo esc_html($term->name); ?></span>
                                    <?php endforeach; 
                                endif; 
                                $termsType = get_the_terms(get_the_ID(), 'tour-type');
                                if ($termsType && !is_wp_error($termsType)) : 
                                    foreach ($termsType as $term) :?>
                                        <span class="label label-right label-red"><?php echo esc_html($term->name); ?></span>
                                    <?php endforeach; 
                                endif; 
                                ?>
                                <?php  $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                                }?>
                            </a>
                        </div>
                        <div class="item-desc">
                            <div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                            <?php if ($infoList = carbon_get_post_meta($id, 'list')) : ?>
                                <ul class="item-list">
                                <?php  
                                    $infoDates = carbon_get_post_meta($id, 'list_date'); 
                                    $ids = 1;
                                    $currentDate = new DateTime(); // Поточна дата
                                    foreach ($infoDates as $item) :
                                        $repeat_monthly = $item['repeat_monthly'];
                                        $repeat_count = $item['repeat_count'];
                                        $start_date = new DateTime($item['date_start']);
                                        $end_date = new DateTime($item['date_end']);
                                        if ($start_date < $currentDate) {
                                            continue; 
                                        }
                                        $hiddenClass = ($ids > 1) ? ' hidden' : '';
                                        echo '<li class="date-item' . $hiddenClass . '"><span>';
                                            pll_e('Tour dates');
                                            echo '</span> <span>' 
                                                . $start_date->format('Y-m-d') 
                                                . ' - ' 
                                                . $end_date->format('Y-m-d') 
                                                . '</span></li>';
                                        if ($repeat_monthly && is_numeric($repeat_count) && $repeat_count > 1) {
                                            for ($i = 1; $i < $repeat_count; $i++) {
                                                $start_date->modify('+1 month');
                                                $end_date->modify('+1 month');
                                                if ($start_date < $currentDate) {
                                                    continue;
                                                }
                                                $hiddenClass = ' hidden';
                                                echo '<li class="date-item' . $hiddenClass . '"><span>';
                                                pll_e('Tour dates');
                                                echo '</span> <span>' 
                                                . $start_date->format('Y-m-d') 
                                                . ' - ' 
                                                . $end_date->format('Y-m-d') 
                                                . '</span></li>';
                                            }
                                        }
                                        $ids++;
                                    endforeach;
                                    ?>

                                <?php foreach ($infoList as $item) : ?>
                                    <li><span><?php echo $item['text_start'] ?></span><span><?php echo $item['text_end'] ?></span></li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="item-bottom">
                            <span class="item-price__info">From</span>
                            <span class="item-price"><?php echo carbon_get_post_meta($id, 'price'); ?></span>
                            <?php $new_price_old = carbon_get_post_meta($id, 'price_old'); if ($new_price_old) { ?>
                                <span class="item-price__old"><?php echo $new_price_old; ?></span>
                            <?php } ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="item-book btn btn-red"><?php echo pll_e('View');?></a>
                            </div>
                        </div>
                    </div>

            <?php 
         $i++; endwhile;
    else :
        echo 'Пости не знайдено.';
    endif;

    wp_reset_postdata();
    die(); 
}
add_action('wp_ajax_load_all_posts', 'load_all_posts');
add_action('wp_ajax_nopriv_load_all_posts', 'load_all_posts');

add_action('wp_ajax_get_booked_dates_total', 'get_all_booked_dates_total');
add_action('wp_ajax_nopriv_get_booked_dates_total', 'get_all_booked_dates_total');

function get_all_booked_dates_total() {
    $args = [
        'post_type'      => 'tour',
        'post_status'    => 'publish',
        'posts_per_page' => -1
    ];

    $query = new WP_Query($args);
    $booked_dates = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $dates = carbon_get_post_meta($post->ID, 'list_date');

            if (!empty($dates) && is_array($dates)) {
                foreach ($dates as $date_entry) {
                    $start = isset($date_entry['date_start']) ? strtotime($date_entry['date_start']) : false;
                    $end   = isset($date_entry['date_end']) ? strtotime($date_entry['date_end']) : false;

                    if ($start && $end) {
                        $current = $start;

                        while ($current <= $end) {
                            $booked_dates[] = date('Y-m-d', $current);
                            $current = strtotime('+1 day', $current);
                        }
                    }
                }
            }
        }
    }

    wp_send_json_success([
        'booked_dates' => array_unique($booked_dates)
    ]);
}




?>