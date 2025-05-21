$(document).ready(function() {
    localStorage.setItem('tour_id', $('.tour-desc .btn-book').attr('data-id'));
    const tourId = localStorage.getItem('tour_id');
    const tour_id = localStorage.getItem('tour_id');
    const tour_name = localStorage.getItem('tour_title');
    $('.tour_id').val(tour_id);
    $('.tour_name').val(tour_name);
    let bookedDates = [];
    let tourDates = [];
    let startDate = null;
    let endDate = null;
function updateCalendars() {
    $('#current-month').html(renderCalendar(0));
    $('#next-month').html(renderCalendar(1));
    attachDayClickEvent();
}

	$('.btn-book').on('click', function(e) {
    const $thisDataBooking = $(this).attr('data-dates-booking');
    if ($thisDataBooking.length > 0) {
        
    } else {
        $('html, body').animate({
            scrollTop: $('.tour-calendar').offset().top
        }, 1000);
    }
})
let currentDate = new Date();
const cookiepLanguage = Cookies.get('pll_language');
let months;
let weekdays;
if (cookiepLanguage === 'tr') {
    months = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
    weekdays = ["Pzt", "Sal", "Çar", "Per", "Cum", "Cmt", "Paz"];
} else if (cookiepLanguage === 'ru') {
    months = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
    weekdays = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];
} else {
    months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    weekdays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
}
function isPastDate(date) {
    return date < new Date();
}
function fetchAllBookedDates() {
    $.ajax({
        url: '/wp-admin/admin-ajax.php?action=get_booked_dates_total',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                // Очистити та оновити глобальний масив
                bookedDates = data.data.booked_dates.map(date =>
                    new Date(date + 'T00:00:00Z').toISOString().split('T')[0]
                );
                console.log(bookedDates);
                updateCalendars(); // оновити календар одразу
            } else {
                console.error('Помилка у відповіді:', data);
            }
        },
        error: function (error) {
            console.error('AJAX помилка:', error);
        }
    });
}
    fetchAllBookedDates(); 
function renderCalendar(monthOffset) {
    const monthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + monthOffset, 1);
    const monthName = months[monthDate.getMonth()];
    const year = monthDate.getFullYear();
    let daysInMonth = new Date(year, monthDate.getMonth() + 1, 0).getDate();
    let firstDay = new Date(year, monthDate.getMonth(), 1).getDay();
    let calendarHtml = `<h5>${monthName} ${year}</h5>`;
    calendarHtml += '<div class="weekdays">';
    weekdays.forEach(day => {
        calendarHtml += `<div class="weekday">${day}</div>`;
    });
    calendarHtml += '</div>';
    calendarHtml += '<div class="days">';
    for (let i = 0; i < firstDay; i++) {
        calendarHtml += '<div class="day empty"></div>';
    }
    const daysToHighlight = [];
    $('.tour-desc__list li').each(function() {
        const dateSpans = $(this).find('span:last-child span');
        const datesTours = [];
        dateSpans.each(function() {
            datesTours.push($(this).text().trim());
        });
        if (datesTours.length === 2) {
            const startDate = new Date(datesTours[0]);
            const endDate = new Date(datesTours[1]);
            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                daysToHighlight.push({
                    day: d.getDate(),
                    month: d.getMonth(),
                    year: d.getFullYear()
                });
            }
        }
    });
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDate = new Date(year, monthDate.getMonth(), day);
        const isDisabled = isPastDate(dayDate) ? 'disabled' : '';
        const formattedDate = dayDate.toISOString().split('T')[0];
        const isBooked = bookedDates.includes(formattedDate) ? 'booked' : '';
        const isTourDate = daysToHighlight.some(({ day: highlightDay, month: highlightMonth, year: highlightYear }) => {
            return highlightDay === day && highlightMonth === monthDate.getMonth() && highlightYear === year;
        });
        const tourDateClass = isTourDate ? 'tour-date' : '';
        calendarHtml += `<div class="day ${isDisabled} ${isBooked} ${tourDateClass}" data-day="${day}" data-month="${monthDate.getMonth()}" data-year="${year}">${day}</div>`;
    }
    calendarHtml += '</div>';
    return calendarHtml;
}
const rentfor = $('.rentfor').text();
const rentdays = $('.rentdays').text();

function attachDayClickEvent() {
    $('.day').off('click').click(function() {
        if ($('.calendar').hasClass('tour')) {
            return;
        }
        if (!$(this).hasClass('empty') && !$(this).hasClass('disabled') && !$(this).hasClass('booked')) {
            $('.day').removeClass('selected range last-selected');
            const day = parseInt($(this).data('day'));
            const month = parseInt($(this).data('month'));
            const year = parseInt($(this).data('year'));
            const selectedDate = new Date(Date.UTC(year, month, day));
            startDate = selectedDate;
            
            $('input[name="order_start"]').val(startDate.toISOString().split('T')[0]);
            $('#start-date-display').text(startDate.toLocaleDateString('uk-UA'));
            $(this).addClass('selected last-selected');
            $('.tour-desc__date p:nth-child(1)').text(selectedDate);
            
            
            endDate = selectedDate;
            $('input[name="order_end"]').val(endDate.toISOString().split('T')[0]);
            $('#end-date-display').text(endDate.toLocaleDateString('uk-UA'));
            // highlightRange(startDate, endDate);
            $(this).addClass('last-selected');
            $('.link-section .btn-red').addClass('active');
            $('.tour-desc__date p:nth-child(2)').text(selectedDate);
            const dataHref = $('.tour-desc .btn-book').attr('data-href');
            $('.tour-desc .btn-book').attr('href', dataHref);
            $('html, body').animate({
                scrollTop: $('.tour-card').offset().top
            }, 1000);
            const $productBasePriceFirst = $('.tour-desc .item-price').text();
            const $prodctBasePrice = parseFloat($productBasePriceFirst.replace(/[^\d.-]/g, '')) || 0;
            const sumDays = $('.range').length;
            
            const $productTotalPrice = $prodctBasePrice;

            const formattedPrice = new Intl.NumberFormat('uk-UA').format($productTotalPrice);
            $('.tour-desc .item-price').text(formattedPrice + ' €');
            $('.total-price__price').text($productTotalPrice + '€');
                
            
            $('.tour-desc .item-price').attr('data-dates-start', startDate);
            $('.tour-desc .item-price').attr('data-dates-end', endDate);
            $('.tour-desc .item-price').attr('data-total-days', sumDays);
            localStorage.setItem('startDate', $('.tour-desc .item-price').attr('data-dates-start'));
            localStorage.setItem('endDate', $('.tour-desc .item-price').attr('data-dates-end'));
            localStorage.setItem('countDays', 1);
            localStorage.setItem('original_tour_id', $('.tour-desc .btn-book').attr('data-translated'));
            localStorage.setItem('totalPrice', $productTotalPrice);
            setCookie('input_tour_id', localStorage.getItem('tour_id'), 30);
            setCookie('original_tour_id', localStorage.getItem('original_tour_id'), 30);

            
        }else{
        }
    });
}
	

$('#prev').click(function() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendars();
    resetSelection();
});
$('#next').click(function() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendars();
    resetSelection();
});
updateCalendars();
});

function resetSelection() {
    startDate = null;
    endDate = null;
    $('#start-date-display').text('');
    $('#end-date-display').text('');
    $('.day').removeClass('selected range last-selected');
}