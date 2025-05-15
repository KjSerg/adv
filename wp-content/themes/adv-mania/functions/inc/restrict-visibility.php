<?php
// Обмеження видимості замовлень для менеджера

function add_order_manager_meta_box() {
    if (current_user_can('administrator')) {
        add_meta_box(
            'order_manager_meta_box',
            'Assign Order Manager',
            'render_order_manager_meta_box',
            'orders',
            'side',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'add_order_manager_meta_box');

function render_order_manager_meta_box($post) {
    // Перевіряємо, чи користувач - адміністратор
    if (!current_user_can('administrator')) {
        return; // Якщо це не адміністратор, не відображаємо мета-бокс
    }

    // Отримуємо поточного менеджера (якщо призначений)
    $assigned_users = get_post_meta($post->ID, '_assigned_users', true);
    if (!is_array($assigned_users)) {
        $assigned_users = []; // Якщо це не масив, ініціалізуємо порожній масив
    }

    // Отримуємо всіх користувачів з роллю 'order_manager'
    $managers = get_users([
        'role' => 'order_manager',
        'fields' => ['ID', 'display_name']
    ]);

    // Виведення чекбоксів для кожного менеджера
    echo '<div>';
    foreach ($managers as $manager) {
        $checked = in_array($manager->ID, $assigned_users) ? 'checked' : '';
        echo '<label>';
        echo '<input type="checkbox" name="assigned_users[]" value="' . $manager->ID . '" ' . $checked . '> ';
        echo esc_html($manager->display_name);
        echo '</label><br>';
    }
    echo '</div>';
}


function save_order_manager_meta_box($post_id) {
    if (!current_user_can('administrator')) {
        return; // Якщо не адміністратор, виходимо з функції
    }

    if (isset($_POST['assigned_users'])) {
        $assigned_users = array_map('intval', $_POST['assigned_users']);
        update_post_meta($post_id, '_assigned_users', $assigned_users);
    } else {
        delete_post_meta($post_id, '_assigned_users');
    }
}
add_action('save_post', 'save_order_manager_meta_box');


function restrict_custom_orders_visibility($query) {
    if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'orders') {
        if (current_user_can('administrator')) {
            return;
        }
        if (current_user_can('order_manager')) {
            $current_user_id = get_current_user_id();
            $query->set('meta_query', [
                [
                    'key' => '_assigned_users',
                    'value' => $current_user_id,
                    'compare' => 'LIKE'
                ]
            ]);
        }
    }
}
add_action('pre_get_posts', 'restrict_custom_orders_visibility');
