function setCookie(name, value, minutes) {
    var expires = "";
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime() + (minutes * 60 * 1000)); // Конвертуємо хвилини в мілісекунди
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/"; // Встановлюємо кукі
}


$('.btn-book').on('click', function(e){
    localStorage.removeItem('tour_title');
    localStorage.removeItem('tour_id');
    localStorage.setItem('tour_id', $(this).data('id'));

    localStorage.setItem('tour_title', $(this).data('title'));

    localStorage.setItem('original_tour_id', $(this).data('translated'));
    

    setCookie('input_tour_id', localStorage.getItem('tour_id'), 30); // Термін дії 30 хвилин
    setCookie('original_tour_id', localStorage.getItem('original_tour_id'), 30); // Термін дії 30 хвилин
})



jQuery(document).ready(function($) {
    var bikeId = $('#bike-booking-form input[name="bike_id"]').val(); // Отримуємо ID байка з мета-даних

    // Ініціалізація календаря
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: '/wp-admin/admin-ajax.php', // Шлях до обробника AJAX у WordPress
                method: 'POST',
                data: {
                    action: 'get_bike_booking_events', // Назва дії для AJAX
                    bike_id: bikeId
                },
                success: function(response) {
                    var events = JSON.parse(response); // Перетворюємо JSON в масив подій

                    // Перевірка формату кожної події
                    events = events.map(function(event) {
                        // Якщо start не є рядком, перетворюємо його в правильний формат
                        if (typeof event.start === 'string' && !event.start.includes('T')) {
                            event.start += 'T00:00:00'; // Додаємо час
                        } else if (event.start instanceof Date) {
                            // Якщо event.start - це об'єкт Date, конвертуємо його в формат рядка
                            event.start = moment(event.start).format('YYYY-MM-DDT00:00:00');
                        }
                        return event;
                    });

                    callback(events); // Передаємо події в календар
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error); // Лог помилки
                }
            });
        }
    });

    // Обробка форми для бронювання
    $('#bike-booking-form').submit(function(e) {
        e.preventDefault(); // Запобігаємо стандартному відправленню форми

        var quantity = $('#quantity').val();  // Отримуємо кількість
        var startDate = $('#start_date').val(); // Дата початку
        var endDate = $('#end_date').val(); // Дата закінчення

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'book_bike', // Дія для бронювання
                bike_id: bikeId,
                quantity: quantity,
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                var data = JSON.parse(response); // Парсимо відповідь від сервера
                alert(data.message); // Виводимо повідомлення
                if (data.success) {
                    $('#calendar').fullCalendar('refetchEvents'); // Оновлюємо календар
                }
            },
            error: function(xhr, status, error) {
                console.log("Error:", error); // Лог помилки
            }
        });
    });
});
