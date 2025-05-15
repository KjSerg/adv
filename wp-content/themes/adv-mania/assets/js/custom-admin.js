jQuery(document).ready(function ($) {
    
    function calculateTotalSum() {
        let totalSum = 0;

        // Підсумовуємо значення всіх елементів preparation-item-sum
        $('#pending_preparation_changes .item.approved').each(function () {
            let value = parseFloat($(this).find('.sum-item').text());
            if (!isNaN(value)) {
                totalSum += value;
            }
        });
        
        // Отримуємо значення з order_sum і додаємо його до загальної суми
        const orderSum = parseFloat($('.order_sum .cf-text__input').val()) || 0;
        totalSum += orderSum;

        $('.total_order_sum .cf-text__input').val(totalSum.toFixed(2));
    }

    setTimeout(function(){
        calculateTotalSum();    
    }, 800);
    

    $(document).on('input', '#pending_preparation_changes .item.approved', function () {
        calculateTotalSum();
    });

    // Додаємо обробник події input для елементів order_sum
    // $(document).on('input', '.order_sum .cf-text__input', function () {
    //     calculateTotalSum();
    // });
});


jQuery(document).ready(function ($) {
    $(document).on('click', '.send-template-btn', function () {
        var $container = $(this).closest('.cf-complex__group-body');
        var email = $container.find('.preparation-email .cf-text__input').val();
        var templateValue = $container.find('input[name^="carbon_fields_compact_input[_order_products]"][name$="[_message_template][0]"]').val();

        console.log('Email:', email);
        console.log('Template Value:', templateValue);

        if (email && templateValue) {
            // Отримуємо шаблон через Ajax
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'get_message_template', // Дія для отримання шаблону
                    template_value: templateValue
                },
                success: function (response) {
                    if (response.success) {
                        // Надсилаємо email із шаблоном
                        $.ajax({
                            url: ajaxurl,
                            method: 'POST',
                            data: {
                                action: 'send_email_with_template', // Дія для надсилання email
                                to: email,
                                subject: response.data.subject,
                                content: response.data.content
                            },
                            success: function (response) {
                                if (response.success) {
                                    alert('Шаблон надіслано успішно!');
                                } else {
                                    alert('Помилка при відправці шаблону.');
                                }
                            }
                        });
                    } else {
                        alert('Не вдалося отримати шаблон.');
                    }
                },
                error: function () {
                    alert('Помилка запиту до сервера.');
                }
            });
        } else {
            alert('Введіть email та виберіть шаблон.');
        }
    });
});
