$(document).ready(function() {
    const bookedDates = [];
    const daysToHighlight = [];
    const testDaysToHighlight = [];
    $('.item').each(function() {
        const dateItems = $(this).find('.item-list li.date-item span:last-child'); // Збираємо всі елементи з датами
        dateItems.each(function() {
            const dateText = $(this).text().trim();
            const dateMatch = dateText.match(/^(\d{4}-\d{2}-\d{2})\s*-\s*(\d{4}-\d{2}-\d{2})$/);
            const dateMatchTest = dateMatch.input;
            testDaysToHighlight.push({
                dateMatchTest
                });
            if (dateMatch) {
                const startDate = new Date(dateMatch[1]);
                const endDate = new Date(dateMatch[2]);
                for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                    daysToHighlight.push({
                        day: d.getDate(),
                        month: d.getMonth(),
                        year: d.getFullYear(),
                    });
                    
                }
                
            }
        });
        
    });
    console.log(testDaysToHighlight);
    
    const tour_id = localStorage.getItem('tour_id');
    const tour_name = localStorage.getItem('tour_title');
    $('.tour_id').val(tour_id);
    $('.tour_name').val(tour_name);

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
            url: `/wp-admin/admin-ajax.php?action=get_booked_dates&tour_id=${tourId}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    bookedDates.length = 0;
                    bookedDates.push(...data.data.booked_dates.map(date => new Date(date + 'T00:00:00Z').toISOString().split('T')[0]));
                    updateCalendars();
                }
            },
            error: function(error) {
                console.error('Помилка:', error);
            }
        });
    }

    function fetchBikeBookedDates(tourId) {
        $.ajax({
            url: `/wp-admin/admin-ajax.php?action=get_booked_dates_moto&tour_bike_id=${tourId}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    bookedDates.push(...data.data.booked_dates);
                    updateCalendars();
                } else {
                    console.log('Не вдалося отримати заброньовані дати:', data);
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
        fetchBikeBookedDates(tourIdFromStorage);
    }

    $('select[name="tour_id"]').change(function() {
        const selectedTourId = $(this).val();
        localStorage.setItem('tour_id', selectedTourId);

        if (selectedTourId) {
            fetchBookedDates(selectedTourId);
            fetchBikeBookedDates(selectedTourId);
        }
    });

    let currentDate = new Date();
    const cookiepLanguage = Cookies.get('pll_language');
    let months, weekdays;
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

        // Створюємо календар з виділеними днями турів
        for (let day = 1; day <= daysInMonth; day++) {
            const dayDate = new Date(year, monthDate.getMonth(), day);
            const isDisabled = isPastDate(dayDate) ? 'disabled' : '';
            const formattedDate = dayDate.toISOString().split('T')[0];
            const isBooked = bookedDates.includes(formattedDate) ? 'booked' : '';

            // Перевірка, чи день є туровою датою
            const isTourDate = daysToHighlight.some(({ day: highlightDay, month: highlightMonth, year: highlightYear }) => {
                return highlightDay === day && highlightMonth === monthDate.getMonth() && highlightYear === year;
            });
            
            
            const tourDateClass = isTourDate ? 'tour-date' : '';
            calendarHtml += `<div class="day ${isDisabled} ${tourDateClass}" data-day="${day}" data-month="${monthDate.getMonth()}" data-year="${year}">${day}</div>`;
        }

        calendarHtml += '</div>';
        return calendarHtml;
    }
    function attachDayClickEvent() {
        $('.day').off('click').click(function() {
            if ($('.calendar').hasClass('tour')) {
                return;
            }
            if (!$(this).hasClass('empty') && !$(this).hasClass('disabled') && !$(this).hasClass('booked')) {
                const day = parseInt($(this).data('day'));
                const month = parseInt($(this).data('month'));
                const year = parseInt($(this).data('year'));

                const selectedDate = new Date(year, month, day);
                const yyyy = selectedDate.getFullYear();
                const mm   = String(selectedDate.getMonth() + 1).padStart(2, '0');
                const dd   = String(selectedDate.getDate()).padStart(2, '0');
                const formattedDate = `${yyyy}-${mm}-${dd}`;
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        action: 'get_tours_for_date',
                        selected_date: formattedDate 
                    },
                    success: function(response) {
                        console.log('AJAX response:', response);
                        if (response.success) {
                            $('#tour-items').html(response.data.html);
                        } else {
                            $('#tour-items').html(`<p>${response.data.message || 'No tours available'}</p>`);
                        }
                    },
                    
                    error: function(xhr) {
                        console.log('AJAX error:', xhr.status, xhr.responseText);
                        $('#tour-items').html('<p>An error occurred. Please try again.</p>');
                        console.log(selectedDate);
                    }
                });

                if (!startDate) {
                    startDate = selectedDate;
                    // $('input[name="order_start"]').val(startDate.toISOString().split('T')[0]);
                    // $('#start-date-display').text(startDate.toLocaleDateString('uk-UA'));
                    
                    // $(this).addClass('selected last-selected');
                    // $('.tour-desc__date p:nth-child(1)').text(selectedDate);
                    // autoSelectDays();
                    
                } else if (startDate && !endDate && selectedDate > startDate) {
                    endDate = selectedDate;
                    // $('input[name="order_end"]').val(endDate.toISOString().split('T')[0]);
                    // $('#end-date-display').text(endDate.toLocaleDateString('uk-UA'));
                    // highlightRange(startDate, endDate);
                    // $(this).addClass('last-selected');
                    // $('.link-section .btn-red').addClass('active');
                    // $('.tour-desc__date p:nth-child(2)').text(selectedDate);
                    
                } else {
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
        processTourDates(dateRanges);
    });

    $('#next').click(function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendars();
        resetSelection();
        processTourDates(dateRanges);
    });

    updateCalendars();
    
    $(document).on('click', '.tour-desc__list li', function() {
        const dateSpans = $(this).find('span:last-child span');
        const dates = [];
        dateSpans.each(function() {
            dates.push($(this).text().trim());
        });
        if (dates.length === 2) {
            startDate = new Date(dates[0]);
            endDate = new Date(dates[1]);
            $('input[name="order_start"]').val(startDate.toISOString().split('T')[0]);
            $('input[name="order_end"]').val(endDate.toISOString().split('T')[0]);
            currentDate.setFullYear(startDate.getFullYear());
            currentDate.setMonth(startDate.getMonth());
            updateCalendars();
        } else {
            console.error('Неправильний формат дат');
        }
        $('.day').removeClass('selected last-selected range');
        highlightRange(startDate, endDate);
    });

    
    
    function fetchToursForDates() {
        if (!startDate || !endDate) {
            console.log('Please select both start and end dates.');
            return;
        }
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'GET',
            data: {
                action: 'get_tours_for_dates',
                start_date: startDate,
                end_date: endDate
            },
            success: function (response) {
                if (response.success) {
                    const bookedDates = response.data.booked_dates;
                    
                    $('.day').removeClass('selected range last-selected');
                    
                    console.log(bookedDates);
                    bookedDates.forEach((date, index) => {
                        const dayElement = $(`.day[data-date="${date}"]`);
                        if (index === 0) {
                            dayElement.addClass('selected range');
                        } else if (index === bookedDates.length - 1) {
                            dayElement.addClass('last-selected range');
                        } else {
                            dayElement.addClass('range');
                        }
                    });
    
                    // Показати результат
                    $('#tour-results').html(response.data.html);
                } else {
                    $('#tour-results').html('<p>Error fetching tours.</p>');
                }
            },
            error: function () {
                $('#tour-results').html('<p>An error occurred. Please try again.</p>');
            }
        });   
    }

    
    const dateRanges = $.map(testDaysToHighlight, function(obj) {
        return obj.dateMatchTest;
    });
    

    function processTourDates(dateRanges) {        
        dateRanges.forEach(range => {
            const [startDateStr, endDateStr] = range.match(/^(\d{4}-\d{2}-\d{2})\s*-\s*(\d{4}-\d{2}-\d{2})$/).slice(1);            
            const startDate = new Date(startDateStr);
            const endDate = new Date(endDateStr);
            const startDayElement = document.querySelector(
                `.day[data-day="${startDate.getDate()}"][data-month="${startDate.getMonth()}"][data-year="${startDate.getFullYear()}"]`
            );
            if (startDayElement) {
                startDayElement.classList.add('start-tour', 'tour-date');
            }
            const endDayElement = document.querySelector(
                `.day[data-day="${endDate.getDate()}"][data-month="${endDate.getMonth()}"][data-year="${endDate.getFullYear()}"]`
            );
            if (endDayElement) {
                endDayElement.classList.add('end-tour', 'tour-date');
            }
    
            // Виділяємо всі дні між початком і кінцем
            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                const dayElement = document.querySelector(
                    `.day[data-day="${d.getDate()}"][data-month="${d.getMonth()}"][data-year="${d.getFullYear()}"]`
                );
                if (dayElement && !dayElement.classList.contains('start-tour') && !dayElement.classList.contains('end-tour')) {
                    dayElement.classList.add('tour-date');
                }
            }
        });
    }
    setTimeout(function() {
        processTourDates(dateRanges);
    }, 1200);
    
});
$(".btn-reset-caledar").on("click", function(e) {
    if($(this).attr('href') === '#'){
        e.preventDefault();
        var $allDays = $(".day");
        $allDays.removeClass("selected last-selected range thitem");

        const heightHeader = $('.header').height() + 100;

        $('html, body').animate({
            scrollTop: $('#tour-items').offset().top - heightHeader
        }, 800);

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'load_all_posts',
            },
            success: function(response) {
                $('#tour-items').html(response);
            },
            error: function() {
                console.log('Помилка при завантаженні постів');
            }
        });

    }
});
// $(document).on('click', '.day', function () {
//     var $allDays = $(".day");
//     var $clickedDay = $(this);

//     $allDays.removeClass("selected last-selected range");

//     var clickedIndex = $allDays.index($clickedDay);
//     var $closestStartTour = $allDays.slice(0, clickedIndex + 1).filter(".start-tour").last();
//     var $closestEndTour = $allDays.slice(clickedIndex).filter(".end-tour").first();
//     if ($closestStartTour.length && $closestEndTour.length) {
//         var startIndex = $allDays.index($closestStartTour);
//         var endIndex = $allDays.index($closestEndTour);
//         $allDays.slice(startIndex, endIndex + 1).addClass("range");
//         $closestStartTour.addClass("selected");
//         $closestEndTour.addClass("last-selected");
//     }
//     else if($clickedDay.hasClass('end-tour start-tour')){
//         var startIndex = $allDays.index($closestStartTour);
//         var endIndex = $allDays.index($closestEndTour);
//         $allDays.slice(startIndex, endIndex + 1).addClass("range");
//         $closestStartTour.addClass("selected");
//         $closestEndTour.addClass("last-selected");
//     }
//     const heightHeader = $('.header').height() + 100;
//     $('html, body').animate({
//         scrollTop: $('#tour-items').offset().top - heightHeader
//     }, 800);
// });


$(document).on('click', '.day', function () {
    var $allDays = $(".day");
    var $clickedDay = $(this);
    $allDays.removeClass("selected last-selected range");
    if ($clickedDay.hasClass('start-tour') || $clickedDay.hasClass('end-tour')) {}else{
        if ($clickedDay.hasClass('start-tour') && $clickedDay.hasClass('end-tour')) {
            // var clickedIndex = $allDays.index($clickedDay);
            // var $closestStartTour = $allDays.slice(0, clickedIndex).filter(".start-tour").last();
            // var $closestEndTour = $allDays.slice(clickedIndex).filter(".end-tour").last();
            // if ($closestStartTour.length && $closestEndTour.length) {
            //     var startIndex = $allDays.index($closestStartTour);
            //     var endIndex = $allDays.index($closestEndTour);
            //     $allDays.slice(startIndex, endIndex + 1).addClass("range");
            //     $closestStartTour.addClass("selected");
            //     $closestEndTour.addClass("last-selected");
                
            // }
        } else {
            // var clickedIndex = $allDays.index($clickedDay);
            // var $closestStartTour = $allDays.slice(0, clickedIndex + 1).filter(".start-tour").last();
            // var $closestEndTour = $allDays.slice(clickedIndex).filter(".end-tour").first();
            
            // if ($closestStartTour.length && $closestEndTour.length) {
            //     var startIndex = $allDays.index($closestStartTour);
            //     var endIndex = $allDays.index($closestEndTour);
            //     $allDays.slice(startIndex, endIndex + 1).addClass("range");
            //     $closestStartTour.addClass("selected");
            //     $closestEndTour.addClass("last-selected");
            // }
        }
    }
    

    const heightHeader = $('.header').height() + 100;
    $('html, body').animate({
        scrollTop: $('#tour-items').offset().top - heightHeader
    }, 800);
});


    