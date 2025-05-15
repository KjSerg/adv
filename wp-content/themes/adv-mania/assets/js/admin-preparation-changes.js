jQuery(document).ready(function($) {
    $('.edit-change').click(function(int) {
        int.preventDefault();
        const change_id = $(this).data('id');
        const currentData = $(this).closest('table').find('td').map(function() {
            return $(this).text();
        }).get();
        const currentDataObj = {
            preparation_title: currentData[1] || '',
            preparation_value: currentData[3] || '',
            preparation_value_sum: currentData[5] || '',
            preparation_date: currentData[7] || '',
            preparation_author: currentData[9] || ''
        };

        // Створення форми для редагування
        const formHtml = `
            <div id="edit-modal" style="position: fixed;left: 0;top: 0;right: 0;background-color: rgba(0,0,0,.6);height: 100%;z-index: 150;display: flex;justify-content: center;align-items: center;">
                <form id="edit-change-form" style="background: #fff; padding: 15px; display: flex; flex-direction: column; width: 100%; max-width: 580px;">
                    <label>Title:</label>
                    <input type="text" name="preparation_title" value="${currentDataObj.preparation_title}"><br>
                    <label>Description:</label>
                    <input type="text" name="preparation_value" value="${currentDataObj.preparation_value}"><br>
                    <label>Sum:</label>
                    <input type="text" name="preparation_value_sum" value="${currentDataObj.preparation_value_sum}"><br>
                    <label>Date:</label>
                    <input type="text" name="preparation_date" value="${currentDataObj.preparation_date}"><br>
                    <label>Author:</label>
                    <input type="text" name="preparation_author" value="${currentDataObj.preparation_author}"><br>
                    <button type="submit" class='button button-primary button-large'>Save</button>
                    <br>
                    <button type="button" class="button close-popup">Close</button>
                </form>
            </div>
        `;
        
        
        $('body').append(formHtml);
        $('#edit-change-form').submit(function(e) {
            e.preventDefault();
            const new_data = {
                preparation_title: $('input[name="preparation_title"]').val(),
                preparation_value: $('input[name="preparation_value"]').val(),
                preparation_value_sum: $('input[name="preparation_value_sum"]').val(),
                preparation_date: $('input[name="preparation_date"]').val(),
                preparation_author: $('input[name="preparation_author"]').val()
            };

            $.post(ajaxurl, {
                action: 'edit_change',
                change_id: change_id,
                new_data: new_data
            }, function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }

                $('#edit-modal').remove();
            });
        });
    });
    $(document).on('click', '.preparation_button', function(e) {
        e.preventDefault(); // Prevent default form submission
    
        const preparationData = [];
    
        // Loop through all fields inside the preparation_info complex field and gather data
        $('.preparation-item').each(function() {
            const data = {};
    
            // Gather data for text inputs
            $(this).find('input[type="text"], textarea').each(function() {
                const name = $(this).attr('name');
                const value = $(this).val();
                if (value) {
                    const fieldName = name.replace('carbon_fields_compact_input[_preparation_info][0][', '').replace(']', '');
                    data[fieldName] = value;
                }
            });
    
            // If we have gathered any data for this group, push to the array
            if (Object.keys(data).length) {
                preparationData.push(data);
            }
        });
    
        // Send the collected data via AJAX to save it
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'save_preparation_info',
                preparation_data: preparationData,
                post_id: $('#post_ID').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('Дані збережено!');
                    // $('input[name^="carbon_fields_compact_input[_preparation_info]"], textarea[name^="carbon_fields_compact_input[_preparation_info]"]').val('');
                    location.reload();
                } else {
                    alert('Помилка: ' + response.data);
                }
            },
            error: function() {
                alert('Помилка збереження даних.');
            }
        });
    });
    
    $('.approve-change').click(function() {
        const change_id = $(this).data('id');

        $.post(ajaxurl, {
            action: 'approve_change',
            change_id: change_id,
        }, function(response) {
            if (response.success) {
                alert(response.data.message);
                location.reload();
            } else {
                alert('Error: ' + response.data);
            }
        });
    });

    $('.delete-change').click(function(e) {
        e.preventDefault();
        const change_id = $(this).data('id');

        $.post(ajaxurl, {
            action: 'delete_change',
            change_id: change_id,
            // nonce: delete_change_nonce // Додайте nonce для безпеки
        }, function(response) {
            if (response.success) {
                alert(response.data.message);
                location.reload();
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
    $(document).on('click', `.close-popup`, function (e) {
        e.preventDefault();
        $('#edit-modal').remove();
    });
});
