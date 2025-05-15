<?php
// if (!defined('ABSPATH')) {
//     exit;
// }
// add_action('wp_ajax_approve_single_change', function() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';
//     $post_id = intval($_POST['post_id']);
//     $change_index = intval($_POST['change_index']);
//     if (!current_user_can('administrator')) {
//         wp_send_json_error('Unauthorized');
//     }
//     $pending_changes = get_post_meta($post_id, '_pending_preparation_info', true);
//     if ($pending_changes && isset($pending_changes[$change_index])) {
//         $change_data = $pending_changes[$change_index];
//         $pending_changes[$change_index]['approved'] = true;
//         $approved_info = get_post_meta($post_id, '_approved_preparation_info', true) ?: [];
//         $approved_info[] = $change_data;
//         update_post_meta($post_id, '_approved_preparation_info', $approved_info);
//         carbon_set_post_meta($post_id, 'preparation_info', $approved_info);
//         unset($pending_changes[$change_index]);
//         update_post_meta($post_id, '_pending_preparation_info', array_values($pending_changes));
//         $wpdb->insert($table_name, [ 
//             'post_id' => $post_id,
//             'action' => 'approved',
//             'change_data' => wp_json_encode($change_data),
//             'user_id' => get_current_user_id(),
//             'created_at' => current_time('mysql')
//         ]);
//         wp_send_json_success(['message' => 'Change approved.']);
//     } else {
//         wp_send_json_error('Change not found.');
//     }
// });
// add_action('wp_ajax_delete_proposed_change', function() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';
//     $post_id = intval($_POST['post_id']);
//     $change_index = intval($_POST['change_index']);
//     if (!current_user_can('edit_post', $post_id)) {
//         wp_send_json_error('Unauthorized');
//     }
//     $pending_changes = get_post_meta($post_id, '_pending_preparation_info', true);
//     if ($pending_changes && isset($pending_changes[$change_index])) {
//         $change_data = $pending_changes[$change_index];
//         unset($pending_changes[$change_index]);
//         update_post_meta($post_id, '_pending_preparation_info', array_values($pending_changes));
//         $wpdb->insert($table_name, [
//             'post_id' => $post_id,
//             'action' => 'deleted',
//             'change_data' => wp_json_encode($change_data),
//             'user_id' => get_current_user_id(),
//             'created_at' => current_time('mysql')
//         ]);
//         wp_send_json_success('Change deleted successfully.');
//     } else {
//         wp_send_json_error('Change not found.');
//     }
// });
// add_action('carbon_fields_post_meta_container_saved', function($post_id) {
//     if (!current_user_can('administrator')) {
//         // Отримуємо дані, які менеджер зберіг
//         $preparation_info = carbon_get_post_meta($post_id, 'preparation_info');

//         // Зберігаємо ці дані в `_pending_preparation_info` для адміністратора
//         update_post_meta($post_id, '_pending_preparation_info', $preparation_info);

//         // Додаємо запис у таблицю `wp_changes_log`
//         global $wpdb;
//         $table_name = $wpdb->prefix . 'changes_log';
//         foreach ($preparation_info as $change_data) {
//             $wpdb->insert($table_name, [
//                 'post_id' => $post_id,
//                 'action' => 'pending',
//                 'change_data' => wp_json_encode($change_data),
//                 'user_id' => get_current_user_id(),
//                 'created_at' => current_time('mysql')
//             ]);
//         }

//         // Відновлюємо початкові (затверджені) дані
//         $approved_info = get_post_meta($post_id, '_approved_preparation_info', true) ?: [];
//         carbon_set_post_meta($post_id, 'preparation_info', $approved_info);
//     }
// });


// // Render the meta box with pending changes
// function render_pending_preparation_meta_box($post) {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';

//     $pending_changes = $wpdb->get_results(
//         $wpdb->prepare(
//             "SELECT * FROM $table_name WHERE post_id = %d AND action = 'pending'",
//             $post->ID
//         ),
//         ARRAY_A
//     );

//     if ($pending_changes) {
//         echo '<h4>Pending Preparations:</h4>';
//         foreach ($pending_changes as $index => $change) {
//             $change_data = json_decode($change['change_data'], true);

//             echo '<table class="item widefat fixed striped" data-index="' . $index . '">';
//             echo '<tr><td><strong>Title</strong></td><td>' . esc_html($change_data['preparation_title']) . '</td></tr>';
//             echo '<tr><td><strong>Description</strong></td><td>' . esc_html($change_data['preparation_value']) . '</td></tr>';
//             echo '<tr><td><strong>Sum</strong></td><td>' . esc_html($change_data['preparation_value_sum']) . '</td></tr>';
//             echo '<tr><td><strong>Date</strong></td><td>' . esc_html($change_data['preparation_date']) . '</td></tr>';
//             echo '<tr><td><strong>Author</strong></td><td>' . esc_html($change_data['preparation_author']) . '</td></tr>';
//             echo '<tr><td>';
//             echo '<button class="approve-change" data-id="' . $change['id'] . '">Approve</button> ';
//             echo '<button class="edit-change" data-id="' . $change['id'] . '">Edit</button> ';
//             echo '<button class="delete-change" data-id="' . $change['id'] . '">Delete</button>';
//             echo '</td></tr>';
//             echo '</table><br>';
//         }
//     } else {
//         echo '<p>No pending changes.</p>';
//     }
// }

// add_action('add_meta_boxes', function() {
//     if (current_user_can('administrator')) {
//         add_meta_box(
//             'pending_preparation_changes',
//             'Pending Preparation Changes',
//             'render_pending_preparation_meta_box',
//             'orders',
//             'normal',
//             'default'
//         );
//     }
// });

// // Add a column for pending changes in admin list view
// add_filter('manage_orders_posts_columns', function($columns) {
//     $columns['pending_changes'] = 'Pending Changes';
//     return $columns;
// });

// add_action('manage_orders_posts_custom_column', function($column, $post_id) {
//     if ($column === 'pending_changes') {
//         $pending_changes = get_post_meta($post_id, '_pending_preparation_info', true);

//         if ($pending_changes) {
//             $pending = count(array_filter($pending_changes, function($change) {return !isset($change['approved']) || !$change['approved']; }));
//             $approved = count($pending_changes) - $pending;

//             echo '<strong>' . $pending . ' pending</strong>';
//             // echo '<strong>' . $pending . ' pending</strong> / <em>' . $approved . ' approved</em>';
//         } else {
//             echo '<em>No changes</em>';
//         }
//     }
// }, 10, 2);


// add_action('wp_ajax_approve_change', function() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';
//     $change_id = intval($_POST['change_id']);
//     $change = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $change_id), ARRAY_A);

//     if (!$change) {
//         wp_send_json_error('Change not found.');
//     }

//     $change_data = json_decode($change['change_data'], true);
//     $post_id = $change['post_id'];

//     $approved_info = get_post_meta($post_id, '_approved_preparation_info', true) ?: [];
//     $approved_info[] = $change_data;
//     update_post_meta($post_id, '_approved_preparation_info', $approved_info);
//     carbon_set_post_meta($post_id, 'preparation_info', $approved_info);

//     $wpdb->update($table_name, ['action' => 'approved'], ['id' => $change_id]);
//     wp_send_json_success(['message' => 'Change approved.']);
// });

// add_action('wp_ajax_edit_change', function() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';
//     $change_id = intval($_POST['change_id']);
//     $new_data = $_POST['new_data']; // Передбачається, що дані будуть валідовані на стороні клієнта

//     $wpdb->update($table_name, ['change_data' => wp_json_encode($new_data)], ['id' => $change_id]);
//     wp_send_json_success(['message' => 'Change edited successfully.']);
// });

// add_action('wp_ajax_delete_change', function() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';
//     $change_id = intval($_POST['change_id']);

//     $change = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $change_id), ARRAY_A);
//     if (!$change) {
//         wp_send_json_error('Change not found.');
//     }

//     $wpdb->delete($table_name, ['id' => $change_id]);
//     wp_send_json_success(['message' => 'Change deleted successfully.']);
// });



if (!defined('ABSPATH')) {
    exit;
}


// add_action('save_post_orders', function($post_id) {
//     // Перевірка, чи є права на зміну
//     if (!current_user_can('order_manager')) {
//         return;
//     }

//     // Перевірка, чи це автозбереження
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     // Отримуємо поточний контент з поля preparation_info
//     $preparation_info = carbon_get_post_meta($post_id, 'preparation_info');

//     // Перевіряємо, чи є запис у preparation_info
//     if ($preparation_info && is_array($preparation_info)) {
//         global $wpdb;
//         $table_name = $wpdb->prefix . 'changes_log';

//         // Ітерація по кожному елементу в preparation_info
//         foreach ($preparation_info as $info) {
//             // Перевірка на заповненість полів (наприклад, перевірка на 'preparation_title', 'preparation_value' і ін.)
//             if (!empty($info['preparation_title']) && !empty($info['preparation_value']) && !empty($info['preparation_value_sum'])) {
//                 // Якщо поля заповнені, додаємо їх до бази даних з статусом "pending"
//                 $wpdb->insert(
//                     $table_name,
//                     [
//                         'post_id' => $post_id,
//                         'change_data' => wp_json_encode($info), // Перетворюємо інформацію в JSON
//                         'action' => 'pending', // Статус "pending"
//                         'created_at' => current_time('mysql'),
//                     ]
//                 );
//             }
//         }

//         // Очищуємо поле preparation_info після обробки
//         carbon_set_post_meta($post_id, 'preparation_info', []);
//     }
// });


add_action('wp_ajax_save_preparation_info', function() {
    // Перевірка, чи є користувач менеджером
    // if (!current_user_can('order_manager')) {
    //     wp_send_json_error('Недостатньо прав для виконання цієї операції.');
    // }

    $post_id = intval($_POST['post_id']);
    $preparation_data = isset($_POST['preparation_data']) ? $_POST['preparation_data'] : [];

    if (empty($preparation_data)) {
        wp_send_json_error('Немає даних для збереження.');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'changes_log';

    // Додаємо дані в базу даних
    foreach ($preparation_data as $info) {
        // Add each preparation entry to the database
        $wpdb->insert(
            $table_name,
            [
                'post_id' => $post_id,
                'change_data' => wp_json_encode($info),  // Store as JSON
                'action' => 'pending', // Set action as "pending"
                'created_at' => current_time('mysql'),
            ]
        );
    }

    // Очищаємо поле preparation_info після збереження
    carbon_set_post_meta($post_id, 'preparation_info', []);

    wp_send_json_success('Дані успішно збережено.');
});



add_action('wp_ajax_approve_change', function() {
    global $wpdb;   
    $table_name = $wpdb->prefix . 'changes_log';
    $change_id = intval($_POST['change_id']);
    $change = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $change_id), ARRAY_A);

    if (!$change) {
        wp_send_json_error('Change not found.');
    }

    $change_data = json_decode($change['change_data'], true);
    $post_id = $change['post_id'];

    $approved_info = get_post_meta($post_id, '_approved_preparation_info', true) ?: [];
    $approved_info[] = $change_data;
    update_post_meta($post_id, '_approved_preparation_info', $approved_info);

    //carbon_set_post_meta($post_id, 'preparation_info', $approved_info);

    $wpdb->update($table_name, ['action' => 'approved'], ['id' => $change_id]);
    wp_send_json_success(['message' => 'Change approved.']);
});

add_action('wp_ajax_edit_change', function() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'changes_log';
    $change_id = intval($_POST['change_id']);
    
    $new_data = $_POST['new_data'];
    $change = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $change_id), ARRAY_A);
    if (!$change) {
        wp_send_json_error('Change not found.');
    }
    // if (current_user_can('order_manager')) {
        // $post_id = $change['post_id'];
        // delete_post_meta($post_id, '_approved_preparation_info');
        // $wpdb->update($table_name, ['change_data' => wp_json_encode($new_data)], ['id' => $change_id]);
        // wp_send_json_success(['message' => 'Preparation info deleted and change saved.']);
    // }
    $wpdb->update($table_name, ['change_data' => wp_json_encode($new_data)], ['id' => $change_id]);

    wp_send_json_success(['message' => 'Change edited successfully.']);
});
// add_action('wp_ajax_edit_change', function () {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'changes_log';
//     $change_id = intval($_POST['change_id']);
//     $new_data = $_POST['new_data'];

//     $change = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $change_id), ARRAY_A);
//     if (!$change) {
//         wp_send_json_error('Change not found.');
//     }

//     if (current_user_can('order_manager')) {
//         $wpdb->update($table_name, ['change_data' => wp_json_encode($new_data)], ['id' => $change_id]);
//         wp_send_json_success(['message' => 'Change updated successfully.']);
//     } else {
//         wp_send_json_error('You do not have permission to edit this change.');
//     }
// });


add_action('wp_ajax_delete_change', function() {
    // Перевірте nonce для безпеки
    // if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_change_nonce')) {
    //     wp_send_json_error('Nonce verification failed.');
    // }

    global $wpdb;
    $table_name = $wpdb->prefix . 'changes_log';
    $change_id = intval($_POST['change_id']);

    // Отримати запис змін
    $change = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $change_id), ARRAY_A);

    if (!$change) {
        wp_send_json_error('Change not found.');
    }

    // Видалити запис з бази даних
    $wpdb->delete($table_name, ['id' => $change_id]);

    // Повідомлення про успіх
    wp_send_json_success(['message' => 'Change deleted successfully.']);
});


add_action('admin_footer', function() {
    wp_nonce_field('delete_change_nonce', 'nonce');
});

function render_pending_preparation_meta_box($post) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'changes_log';

    $changes = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE post_id = %d",
            $post->ID
        ),
        ARRAY_A
    );

    if ($changes) {
        echo '<h4>Pending Preparations:</h4>';
        foreach ($changes as $change) {
            $change_data = json_decode($change['change_data'], true);
            $status_class = $change['action'] === 'approved' ? 'approved' : 'pending';
    
            // Уніфікація даних
            $preparation_title = $change_data['preparation_title'] ?? $change_data['_preparation_title'] ?? 'N/A';
            $preparation_value = $change_data['preparation_value'] ?? $change_data['_preparation_value'] ?? 'N/A';
            $preparation_value_sum = $change_data['preparation_value_sum'] ?? $change_data['_preparation_value_sum'] ?? 'N/A';
            $preparation_date = $change_data['preparation_date'] ?? $change_data['_preparation_date'] ?? 'N/A';
            $preparation_author = $change_data['preparation_author'] ?? $change_data['_preparation_author'] ?? 'N/A';
    
            echo '<table class="item widefat fixed striped ' . esc_attr($status_class) . '">';
            echo '<tr><td><strong>Title</strong></td><td>' . htmlspecialchars($preparation_title, ENT_QUOTES, 'UTF-8') . '</td></tr>';
            echo '<tr><td><strong>Description</strong></td><td>' . htmlspecialchars($preparation_value, ENT_QUOTES, 'UTF-8') . '</td></tr>';
            echo '<tr><td><strong>Sum</strong></td><td class="sum-item">' . htmlspecialchars($preparation_value_sum, ENT_QUOTES, 'UTF-8') . '</td></tr>';
            echo '<tr><td><strong>Date</strong></td><td>' . htmlspecialchars($preparation_date, ENT_QUOTES, 'UTF-8') . '</td></tr>';
            echo '<tr><td><strong>Author</strong></td><td>' . htmlspecialchars($preparation_author, ENT_QUOTES, 'UTF-8') . '</td></tr>';
            echo '<tr><td>';
            echo '<button class="approve-change" data-id="' . esc_attr($change['id']) . '">Approve</button> ';
            echo '<button class="edit-change" data-id="' . esc_attr($change['id']) . '">Edit</button> ';
            echo '<button class="delete-change" data-id="' . esc_attr($change['id']) . '">Delete</button>';
            echo '</td></tr>';
            echo '</table><br>';
        }
    } else {
        echo '<p>No pending changes.</p>';
    }

    // if ($changes) {
    //     echo '<h4>Pending Preparations:</h4>';
    //     foreach ($changes as $change) {
    //         $change_data = json_decode($change['change_data'], true);
    //         $status_class = $change['action'] === 'approved' ? 'approved' : 'pending';

    //         echo '<table class="item widefat fixed striped ' . esc_attr($status_class) . '">';
    //         echo '<tr><td><strong>Title</strong></td><td>' . esc_html($change_data['preparation_title'] ?? 'N/A') . '</td></tr>';
    //         echo '<tr><td><strong>Description</strong></td><td>' . esc_html($change_data['preparation_value'] ?? 'N/A') . '</td></tr>';
    //         echo '<tr><td><strong>Sum</strong></td><td>' . esc_html($change_data['preparation_value_sum'] ?? 'N/A') . '</td></tr>';
    //         echo '<tr><td><strong>Date</strong></td><td>' . esc_html($change_data['preparation_date'] ?? 'N/A') . '</td></tr>';
    //         echo '<tr><td><strong>Author</strong></td><td>' . esc_html($change_data['preparation_author'] ?? 'N/A') . '</td></tr>';
    //         echo '<tr><td>';
    //         echo '<button class="approve-change" data-id="' . esc_attr($change['id']) . '">Approve</button> ';
    //         echo '<button class="edit-change" data-id="' . esc_attr($change['id']) . '">Edit</button> ';
    //         echo '<button class="delete-change" data-id="' . esc_attr($change['id']) . '">Delete</button>';
    //         echo '</td></tr>';
    //         echo '</table><br>';
    //     }
    // } else {
    //     echo '<p>No pending changes.</p>';
    // }
}

add_action('add_meta_boxes', function() {
    if (current_user_can('administrator')) {
        add_meta_box(
            'pending_preparation_changes',
            'Pending Preparation Changes',
            'render_pending_preparation_meta_box',
            'orders',
            'normal',
            'default'
        );
    }
});

add_action('admin_head', function() {
    echo '<style>
        .widefat.approved {
            background-color: #d4edda;
        }
        .widefat.pending {
            background-color: #fff3cd;
        }
    </style>';
});


add_filter('manage_orders_posts_columns', function($columns) {
    $columns['pending_changes'] = 'Pending Changes';
    return $columns;
});
add_action('manage_orders_posts_custom_column', function($column, $post_id) {
    if ($column === 'pending_changes') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'changes_log';

        $pending_changes = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE post_id = %d AND action = %s", $post_id, 'pending')
        );

        $pending = count($pending_changes);

        if ($pending) {
            echo '<strong>' . $pending . ' pending</strong>';
        } else {
            echo '<em>No changes</em>';
        }
    }
}, 10, 2);




// add_action('carbon_fields_meta_boxes_loaded', function () {
//     add_action('current_screen', function () {
//         $screen = get_current_screen();

//         if ($screen && $screen->post_type === 'orders' && $screen->base === 'post') {
//             $post_id = get_the_ID();

//             if ($post_id) {
//                 update_preparation_approved_from_db($post_id);
//             }
//         }
//     });
// });

function update_preparation_approved_from_db($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'changes_log';

    // Отримати всі схвалені записи
    $approved_changes = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT change_data FROM $table_name WHERE post_id = %d AND action = 'approved'",
            $post_id
        ),
        ARRAY_A
    );

    // Оновити поле preparation_approved
    if ($approved_changes) {
        $approved_info = [];

        foreach ($approved_changes as $change) {
            $approved_info[] = json_decode($change['change_data'], true);
        }

        carbon_set_post_meta($post_id, 'preparation_approved', $approved_info);
    }
}


add_action('admin_footer', function() {
    if ('orders' !== get_post_type()) return;
    if (!current_user_can('administrator')) {
?>
<script>

</script>
<?php
    }
});
