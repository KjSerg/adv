<?php
// Додаємо роль "Менеджер замовлень" із потрібними правами
function add_custom_order_manager_capabilities() {
    $role = add_role('order_manager', 'Order Manager', ['read' => true]);

    if ($role || ($role = get_role('order_manager'))) {
        $capabilities = [
            'edit_order',
            'read_order',
            'edit_orders',
            'publish_orders',
            'read_private_orders',
            'edit_private_orders',
            'edit_published_orders',
        ];

        foreach ($capabilities as $cap) {
            $role->add_cap($cap);
        }
    }
}
add_action('admin_init', 'add_custom_order_manager_capabilities');

function restrict_admin_menu_for_order_manager() {
    if (current_user_can('order_manager')) {
        global $menu;
        
        // Видаляємо всі пункти меню, крім "Orders"
        foreach ($menu as $key => $value) {
            if ($value[2] !== 'edit.php?post_type=orders') {
                unset($menu[$key]);
            }
        }
    }
}
add_action('admin_menu', 'restrict_admin_menu_for_order_manager', 999);

function restrict_post_types_for_order_manager($query) {
    // Перевіряємо, чи це адмінка та чи користувач має роль 'order_manager'
    if (is_admin() && current_user_can('order_manager') && $query->is_main_query()) {
        // Обмежуємо тип постів тільки для 'orders'
        if (empty($query->query_vars['post_type']) || $query->query_vars['post_type'] !== 'orders') {
            $query->set('post_type', 'orders');
        }
    }
}
add_action('pre_get_posts', 'restrict_post_types_for_order_manager');
