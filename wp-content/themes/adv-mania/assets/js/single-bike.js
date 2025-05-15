if (document.cookie.split('; ').find(row => row.startsWith('page_type='))?.split('=')[1] === 'tour') {
    $('.btn-book').remove();
    $('.tour-desc__list li:first').remove();
    $('.tour-calendar').remove();
    // $('.item-price').text('Included');
    // $('.item-price__info').text('Rent on tour');
}
function setCookie(name, value, minutes) {
var expires = "";
if (minutes) {
    var date = new Date();
    date.setTime(date.getTime() + (minutes * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
}
document.cookie = name + "=" + (value || "") + expires + "; path=/"; 
}

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
    autoSelectDays();
}

function fetchBookedDates(tourId) {
    $.ajax({
        url: `/wp-admin/admin-ajax.php?action=get_booked_dates_moto&tour_id=${tourId}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const bookedCounts = data.data.total_bikes_booked || {};
                bookedDates = Array.isArray(data.data.booked_dates) ? data.data.booked_dates : [];
                const totalBikes = parseInt(data.data.total_bikes, 10) || 0;

                if (bookedDates.length > 0) {
                    bookedDates.forEach(date => {
                        const dateParts = date.split('-'); 
                        const year = dateParts[0];
                        const month = parseInt(dateParts[1], 10) - 1;
                        const day = dateParts[2];
                        const targetDay = $(`.day[data-day="${day}"][data-month="${month}"][data-year="${year}"]`);
                        if (targetDay.length) {
                            const bookedCount = bookedCounts[date] || 0;
                            if (bookedCount >= totalBikes) {
                                targetDay.addClass('booked');
                            }
                        } else {
                            console.log(`Не знайдено елемент для дати: ${date}`);
                        }
                    });
                    updateCalendars(); 
                }
            } else {
                console.log('Не вдалося отримати заброньовані дати:', response);
            }
        },
        error: function(error) {
            console.error('Помилка:', error);
        }
    });
}

const tourIdFromStorage = localStorage.getItem('tour_id');
if (tourIdFromStorage) {
    fetchBookedDates(tourIdFromStorage);
}
$('select[name="tour_id"]').change(function() {
    const selectedTourId = $(this).val();
    localStorage.setItem('tour_id', selectedTourId);
    if (selectedTourId) {
        fetchBookedDates(selectedTourId);
    }
});
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
            const day = parseInt($(this).data('day'));
            const month = parseInt($(this).data('month'));
            const year = parseInt($(this).data('year'));
            const selectedDate = new Date(Date.UTC(year, month, day));
            if (!startDate) {
                startDate = selectedDate;
                $('input[name="order_start"]').val(startDate.toISOString().split('T')[0]);
                $('#start-date-display').text(startDate.toLocaleDateString('uk-UA'));
                $(this).addClass('selected last-selected');
                $('.tour-desc__date p:nth-child(1)').text(selectedDate);
                autoSelectDays();
            } else if (startDate && !endDate && selectedDate > startDate) {
                endDate = selectedDate;
                $('input[name="order_end"]').val(endDate.toISOString().split('T')[0]);
                $('#end-date-display').text(endDate.toLocaleDateString('uk-UA'));
                highlightRange(startDate, endDate);
                $(this).addClass('last-selected');
                $('.link-section .btn-red').addClass('active');
                $('.tour-desc__date p:nth-child(2)').text(selectedDate);
                const dataHref = $('.tour-desc .btn-book').attr('data-href');
                $('.tour-desc .btn-book').attr('href', dataHref);
                $('html, body').animate({
                    scrollTop: $('.tour-card').offset().top
                }, 1000);
                const $productBasePriceFirst = $('.tour-desc .item-price').attr('data-base-price');
                const $prodctBasePrice = parseFloat($productBasePriceFirst.replace(/[^\d.-]/g, '')) || 0;
                const sumDays = $('.range').length;
                const $productTotalPrice = sumDays * $prodctBasePrice;
                const formattedPrice = new Intl.NumberFormat('uk-UA').format($productTotalPrice);
                $('.tour-desc .item-price').text(formattedPrice + ' €');
                $('.total-price__price').text($productTotalPrice + '€');
                
                $('.item-price__info').text(rentfor +' '+ sumDays + ' ' + rentdays);
                $('.tour-desc .item-price').attr('data-dates-start', startDate);
                $('.tour-desc .item-price').attr('data-dates-end', endDate);
                $('.tour-desc .item-price').attr('data-total-days', sumDays);
                localStorage.setItem('startDate', $('.tour-desc .item-price').attr('data-dates-start'));
                localStorage.setItem('endDate', $('.tour-desc .item-price').attr('data-dates-end'));
                localStorage.setItem('countDays', $('.tour-desc .item-price').attr('data-total-days'));
                localStorage.setItem('original_tour_id', $('.tour-desc .btn-book').attr('data-translated'));
                localStorage.setItem('totalPrice', $productTotalPrice);
                setCookie('input_tour_id', localStorage.getItem('tour_id'), 30);
                setCookie('original_tour_id', localStorage.getItem('original_tour_id'), 30);
            } else {
                const $productBasePriceFirst = $('.tour-desc .item-price').attr('data-base-price');
                $('.item-price__info').text(rentfor);
                $('.tour-desc .item-price').text($productBasePriceFirst);
                $('.tour-desc .btn-book').attr('href', '#');
                resetSelection();
            }
        }
    });
}
function highlightRange(start, end) {
    $('.day').removeClass('range');
    const startDay = start.getDate();
    const endDay = end.getDate();
    const startMonth = start.getMonth();
    const endMonth = end.getMonth();
    const year = start.getFullYear();
    for (let month = startMonth; month <= endMonth; month++) {
        let startDayInRange = month === startMonth ? startDay : 1;
        let endDayInRange = month === endMonth ? endDay : new Date(year, month + 1, 0).getDate();
        for (let day = startDayInRange; day <= endDayInRange; day++) {
            $(`.day[data-day="${day}"][data-month="${month}"][data-year="${year}"]`).addClass('range');
        }
    }
}
function resetSelection() {
    startDate = null;
    endDate = null;
    $('#start-date-display').text('Немає');
    $('#end-date-display').text('Немає');
    $('.day').removeClass('selected range last-selected');
}
function autoSelectDays() {
    const daysBike = parseInt($('#daysBike').val());
    if (daysBike > 0 && startDate) {
        const newEndDate = new Date(startDate);
        newEndDate.setDate(startDate.getDate() + daysBike - 1);
        endDate = newEndDate;
        $('#end-date-display').text(endDate.toLocaleDateString('uk-UA'));
        highlightRange(startDate, endDate);
        $('.day[data-day="' + endDate.getDate() + '"][data-month="' + endDate.getMonth() + '"][data-year="' + endDate.getFullYear() + '"]').addClass('last-selected');
    }
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
$('.moto-id').each(function(e){
infoId = [];
const motoId = $('.moto-id ul li');
for ( var i = 0; i < motoId.length; i++ ) {
    infoId.push(motoId[i].innerHTML);
} 
const jsonInfoId = JSON.stringify(infoId);
$('.btn-book').attr('data-moto-id', jsonInfoId);
});
$('.btn-book').on('click', function(e){
localStorage.setItem('moto_tour_id', $(this).data('moto-id'));
});
function handlePriceCalculation(element, priceSelector) {
if (element.hasClass('selected')) {
    return;
} else {
    element.siblings().removeClass('selected');
    element.addClass('selected');
    if (element.find(priceSelector).length > 0) {
        const $basePriceText = $('.booking-tour__info .item-price').text().trim();
        const $addToPriceText = element.find(priceSelector).text().trim();
        const $basePrice = parseFloat($basePriceText.replace(/[^\d.-]/g, '')) || 0;
        const $addToPrice = parseFloat($addToPriceText.replace(/[^\d.-]/g, '')) || 0;
        const $totalPrice = $basePrice + $addToPrice;
        console.log($basePrice + ' + ' + $addToPrice + ' = ' + $totalPrice);
        $('.total-price__price').text($totalPrice + '€');
    }
}
}
$('.btn-book').on('click', function(e){
const $thisDataBooking = $(this).attr('data-dates-booking'); 
if($thisDataBooking.length > 0){
    localStorage.removeItem('tour_title');
    localStorage.removeItem('tour_id');
    localStorage.removeItem('startDate');
    localStorage.removeItem('endDate');
    localStorage.removeItem('countDays');
    localStorage.setItem('tour_id', $('.tour-desc .item-price').data('id'));
    localStorage.setItem('tour_title', $('.tour-desc .item-price').data('title'));
    localStorage.setItem('original_tour_id', $('.tour-desc .btn-book').attr('data-translated'));
    setCookie('input_tour_id', localStorage.getItem('tour_id'), 30); // Термін дії 30 хвилин
    setCookie('original_tour_id', localStorage.getItem('original_tour_id'), 30); // Термін дії 30 хвилин
}else{
    $('html, body').animate({
    scrollTop: $('.tour-calendar').offset().top
}, 1000);
}
})
$('#submit-booking').click(function(e) {
    e.preventDefault();
    const formData = {
        moto_id: $('#moto_id').val(),
        moto_count: $('#moto_count').val(),
        moto_count_total: $('#moto_count_total').val(),
        order_start: $('#order_start').val(),
        order_end: $('#order_end').val(),
        order_sum: $('#order_sum').val(),
        payment_status: $('#payment_status').val()
    };

    $.ajax({
        url: '/wp-admin/admin-ajax.php',
        type: 'POST',
        data: {
            action: 'create_motorcycle_booking',
            booking_data: formData
        },
        success: function(response) {
            if (response.success) {
                console.log('Бронювання успішно створено!');
            } else {
                console.log('Помилка: ' + response.data.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Сталася помилка при відправці форми: ' + error);
        }
    });
});