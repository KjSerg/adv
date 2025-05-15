const tour_id = localStorage.getItem('tour_id');
const tour_name = localStorage.getItem('tour_title');
$('.tour_id').val(tour_id);
$('.tour_name').val(tour_name);

$(document).on('change', '.item-name', function(e){$('#booking-form input[name="user_name"]').val($(this).val())});
$(document).on('change', '.item-email', function(e){$('#booking-form input[name="user_email"]').val($(this).val())}); 

    

$('#checkout-form').submit(function (e) {
    e.preventDefault();
    var form = $('#checkout-form');
    $.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'POST',
        data: {
            action: 'create_order_temp',
            data: formData
        },
        // type: form.attr('method'),
        // url: form.attr('action'),
        // data: form.serialize(),
        success: (response) => {
            if (response.type === 'success') {
                $('#payment-form input[name="merchant_id"]').val(response.merchant_id);
                $('#payment-form input[name="user_ip"]').val(response.user_ip);
                $('#payment-form input[name="merchant_oid"]').val(response.merchant_oid);
                $('#payment-form input[name="email"]').val(response.email);
                $('#payment-form input[name="payment_type"]').val(response.payment_type);
                $('#payment-form input[name="payment_amount"]').val(response.payment_amount);
                $('#payment-form input[name="currency"]').val(response.currency);
                $('#payment-form input[name="test_mode"]').val(response.test_mode);
                $('#payment-form input[name="non_3d"]').val(response.non_3d);
                $('#payment-form input[name="merchant_ok_url"]').val(response.merchant_ok_url);
                $('#payment-form input[name="merchant_fail_url"]').val(response.merchant_fail_url);
                $('#payment-form input[name="user_name"]').val(response.user_name);
                $('#payment-form input[name="user_address"]').val(response.user_address);
                $('#payment-form input[name="user_phone"]').val(response.user_phone);
                $('#payment-form input[name="user_basket"]').val(response.user_basket);
                $('#payment-form input[name="debug_on"]').val(response.debug_on);
                $('#payment-form input[name="client_lang"]').val(response.client_lang);
                $('#payment-form input[name="paytr_token"]').val(response.paytr_token);
                $('#payment-form input[name="non3d_test_failed"]').val(response.non3d_test_failed);
                $('#payment-form input[name="installment_count"]').val(response.installment_count);
                $('#payment-form input[name="card_type"]').val(response.card_type);
                $('#payment-form').submit();
                form.trigger('reset');
                setTimeout(function(){
                    // $('.booking-nav__title.active').addClass('success').removeClass('active').next().addClass('active');
                    // $('.step-item.active').removeClass('active').next().addClass('active'); 
                    // $('.link-section .btn').remove();
                    console.log('Thank you. Your order has been sent.');
                }, 3000);
            } else {
                $('#card-errors').text(response.message);
                console.log('card errors in pay');
            }
        },
        error: function (xhr, str) {
            console.log('Error occurred: ', xhr);
        }
    });
});
let selectedItems = [];
let selectedItemsEquipment = [];
function handleItemSelection(selector, targetArray, outputField) {
    $(document).on('click', `${selector} .item .btn-red`, function (e) {
        e.preventDefault();
        const days = tourDateSelected() || 1;
        const $item = $(this).closest('.item');
        const itemData = {
            "name": $item.attr('data-title'),
            "id": $item.attr('data-id'),
            "price": parseFloat($item.find('.item-price').text().replace(/[^0-9.-]+/g, '')) * days || 0
        };
        const index = targetArray.findIndex(item => item.id === itemData.id);
        if (index === -1) {
            targetArray.push(itemData);
        } else {
            targetArray.splice(index, 1);
        }
        $(outputField).val(JSON.stringify(targetArray));
    });
}
handleItemSelection('.items-bikes', selectedItems, '.motos');
handleItemSelection('.items-equipment', selectedItemsEquipment, '.equipment');
$('.total-price__price').each(function() {
    const basePrice = $('.booking-tour__info .item-price').text().trim();
    $(this).text(basePrice);
    $(this).attr('data-total-price', basePrice);
    $(this).attr('data-base-price', basePrice);
    
});

// Функція для обчислення кількості днів між обраними датами
function tourDateSelected() {
    let startDateStr = $('.booking-item.selected .booking-item__dates span').eq(0).text().trim();
    let endDateStr = $('.booking-item.selected .booking-item__dates span').eq(1).text().trim();
    let startDate = new Date(startDateStr);
    let endDate = new Date(endDateStr);
    if (isNaN(startDate) || isNaN(endDate)) {
        console.error('Некоректна дата!');
        return 0; // Повертаємо 0, якщо дати некоректні
    }
    let diffInTime = endDate - startDate;
    let diffInDays = diffInTime / (1000 * 60 * 60 * 24);
    
    return diffInDays;
    
}

// Функція для оновлення загальної ціни
function updateTotalPriceDisplay(price) {
    const $totalPriceElement = $('.total-price__price');
    $totalPriceElement.text(`${price.toFixed(0)} €`);
    $totalPriceElement.attr('data-base-price', price.toFixed(0));
    $('.totalRenderSum').val(price.toFixed(0));
}

$(document).on('click', '.people-item', function() {
    const $this = $(this);
    
    $('.people_count').val($(this).find('.add-price').text());
    $('.people_count_title').val($(this).find('.people-item__title').text());
    $(this).val($('.people-item.selected').find('.add-price'));
    if ($this.hasClass('selected')) {
        return;
    } else {
        const $previousSelected = $('.people-item.selected');
        const previousPrice = parseFloat($previousSelected.find('.add-price').attr('data-add-people').replace(/[^0-9.-]+/g, '')) || 0;
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice -= previousPrice;
        let thisItemPrice = parseFloat($this.find('.add-price').attr('data-add-people').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice += thisItemPrice;
        updateTotalPriceDisplay(currentTotalPrice);
        $previousSelected.removeClass('selected');
        $this.addClass('selected');
        
    }
});





$(document).on('click', '.accommodation-item', function() {
    const $this = $(this);
    $('.accommodation_count').val($(this).find('.add-price').attr('data-add-accommodation'));
    $('.accommodation_count_title').val($(this).find('.accommodation-item__title span').text());
    if ($this.hasClass('selected')) {
        return;
    } else {
        const $previousSelected = $('.accommodation-item.selected');
        const previousPrice = parseFloat($previousSelected.find('.add-price').attr('data-add-accommodation').replace(/[^0-9.-]+/g, '')) || 0;
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice -= previousPrice;
        let thisItemPrice = parseFloat($this.find('.add-price').attr('data-add-accommodation').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice += thisItemPrice;
        updateTotalPriceDisplay(currentTotalPrice);
        $previousSelected.removeClass('selected');
        $this.addClass('selected');
    }
});

// Обробник для кнопки "додати/видалити" велосипеди
$(document).on('click', '.items-bikes .item .btn-red', function(e) {
    e.preventDefault;
    const $this = $(this).closest('.item');
    const thisItemPrice = parseFloat($this.find('.item-price').attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    const $totalPriceElement = $('.total-price__price');
    let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    const days = tourDateSelected();
    const totalItemPrice = thisItemPrice * days;

    // console.log(`Ціна за день: ${thisItemPrice}, Загальна ціна за ${days} днів: ${totalItemPrice}`);
    
    


    const textInfoDays = $this.find('.item-price__info'); 
    const textInfoDaysUpdate = 'Add' + ' x ' + days + ' days';

    $this.find('.item-price').toggleClass('item-book');
    $this.find('.item-price span').toggleClass('hidden');
    if ($this.hasClass('selected')) {
        currentTotalPrice -= totalItemPrice;
        $this.removeClass('selected');
        $this.find('.btn-red').removeClass('item-book');
        $this.find('.btn-red span').toggleClass('hidden');

        $this.find('.item-price__old').removeClass('hidden');

        textInfoDays.text('Add to booking');
        $this.find('.item-price').text(thisItemPrice + ' €');
        $('.text-info-days p').text('Rent for '+ days + ' day');
       


    } else {
        currentTotalPrice += totalItemPrice;
        $this.addClass('selected');
        $this.find('.btn-red').addClass('item-book');
        textInfoDays.text(textInfoDaysUpdate);
        $this.find('.btn-red span').toggleClass('hidden');
        $this.find('.item-price').text(totalItemPrice + ' €');
        $this.find('.item-price__old').addClass('hidden');
        $('.text-info-days p').text('Rent for '+ days + ' day + Equipment')

    }
    updateTotalPriceDisplay(currentTotalPrice);
});

// Обробник для кнопки "додати/видалити" обладнання
$(document).on('click', '.items-equipment .item .btn-red', function(e) {
    e.preventDefault();
    const $item = $(this).closest('.item');
    const basePrice = parseFloat($item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    let countItemVal = parseInt($item.find('.count_item').val()) || 1;
    const days = tourDateSelected() || 1;
    const totalItemPrice = basePrice * countItemVal * days;
    
    updatePrice($item);
    const textInfoDays = $item.find('.item-price__info'); 
    const textInfoDaysUpdate = 'Add' + ' x ' + days + ' days';

    $item.find('.count').toggleClass('hidden');
    $(this).toggleClass('item-book');
    $(this).find('span').toggleClass('hidden');
    $item.toggleClass('selected');
    
    // Оновлюємо загальну ціну
    const $totalPriceElement = $('.total-price__price');
    let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    
    if ($item.hasClass('selected')) {
        currentTotalPrice += totalItemPrice;
        textInfoDays.text(textInfoDaysUpdate);
        $item.find('.item-price__old').addClass('hidden');
        $('.text-info-days p').text('Rent for '+ days + ' day')
    } else {
        currentTotalPrice -= totalItemPrice;
        textInfoDays.text('Add to booking');
        $item.find('.item-price').text(basePrice + ' €');
        $item.find('.item-price__old').removeClass('hidden');

        $('.text-info-days p').text('Rent for '+ days + ' day + Equipment')
    }
    updateTotalPriceDisplay(currentTotalPrice);
});

// Функція для оновлення ціни елемента
function updatePrice(item) {
    const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    const quantity = parseInt(item.find('.count_item').val()) || 1;
    const days = tourDateSelected() || 1; 
    const newPrice = basePrice * quantity * days;
    item.find('.item-price').text(newPrice.toFixed(0) + ' €');
}

// Клік по кнопці + (збільшуємо кількість товару)
$('.plus').click(function() {
    const item = $(this).closest('.item');
    const input = item.find('.count_item');
    const currentValue = parseInt(input.val()) || 1;
    const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    const days = tourDateSelected() || 1;
    
    input.val(currentValue + 1);
    
    // Оновлюємо загальну ціну цього товару
    const totalPrice = basePrice * (currentValue + 1) * days;
    item.find('.item-price').text(totalPrice.toFixed(0) + ' €');
    
    // Оновлюємо загальну ціну
    const $totalPriceElement = $('.total-price__price');
    let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    currentTotalPrice += basePrice * days;
    
    updateTotalPriceDisplay(currentTotalPrice);
});

// Клік по кнопці - (зменшуємо кількість товару)
$('.minus').click(function() {
    const item = $(this).closest('.item');
    const input = item.find('.count_item');
    const currentValue = parseInt(input.val()) || 1;
    const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    const days = tourDateSelected() || 1;

    if (currentValue > 1) {
        input.val(currentValue - 1);
        
        // Оновлюємо загальну ціну цього товару
        const totalPrice = basePrice * (currentValue - 1) * days;
        item.find('.item-price').text(totalPrice.toFixed(0) + ' €');
        
        // Оновлюємо загальну ціну
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice -= basePrice * days;
        
        updateTotalPriceDisplay(currentTotalPrice);
    }
});

// Функція для оновлення загальної ціни
function updateTotalPriceDisplay(price) {
    const $totalPriceElement = $('.total-price__price');
    $totalPriceElement.text(`${price.toFixed(0)} €`);
    $totalPriceElement.attr('data-base-price', price.toFixed(0));
}


// Функція для оновлення загальної ціни
function updateTotalPrice() {
    let totalPrice = 0;
    // Додаємо всі ціни вибраних елементів
    $('.items-bikes .item.selected, .items-equipment .item.selected').each(function() {
        const itemPrice = parseFloat($(this).find('.item-price').text().replace(/[^0-9.-]+/g, '')) || 0;
        totalPrice += itemPrice;
    });

    updateTotalPriceDisplay(totalPrice);
}


//end  count price 









// function updateTotalPriceDisplay(totalPrice) {
//     const $totalPriceElement = $('.total-price__price');
//     $totalPriceElement.attr('data-total-price', totalPrice.toFixed(0) + '€').text(totalPrice.toFixed(0) + ' €');
// }

// function updateTotalPrice() {
//     const baseTotalPrice = parseFloat($('.total-price__price').attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
//     let additionalPrice = 0;
//     $('.item.selected').each(function() {
//         const itemPrice = parseFloat($(this).find('.item-price').text().replace(/[^0-9.-]+/g, '')) || 0;
//         additionalPrice += itemPrice;
//     });
//     const updatedTotalPrice = baseTotalPrice + additionalPrice;
//     updateTotalPriceDisplay(updatedTotalPrice);
// }






// function handlePriceCalculation(element, priceSelector) {
//     if (element.hasClass('selected')) {
//         return;
//     } else {
//         element.siblings().removeClass('selected');
//         element.addClass('selected');
//         const basePrice = parseFloat($('.booking-tour__info .item-price').text().replace(/[^0-9.-]+/g, '')) || 0;
//         const addToPrice = parseFloat(element.find(priceSelector).text().replace(/[^0-9.-]+/g, '')) || 0;
//         const totalPrice = basePrice + addToPrice;
//         updateTotalPriceDisplay(totalPrice);
//         $('.total-price__price').attr('data-total-price', totalPrice.toFixed(0) + '€');
//         console.log(totalPrice)
//     }
// }
// $('.people-item').on('click', function(e) {
//     e.preventDefault();
//     handlePriceCalculation($(this), '.add-price');

// });
// $('.accommodation-item').on('click', function(e) {
//     e.preventDefault();
//     handlePriceCalculation($(this), '.add-price');

// });
// $('.total-price__price').each(function() {
//     const basePrice = $('.booking-tour__info .item-price').text().trim();
//     $(this).text(basePrice);
//     $(this).attr('data-total-price', basePrice);
//     $(this).attr('data-base-price', basePrice);
// });





// get.geojs.---------------.---------------.---------------.---------------.---------------.---------------.---------------.---------------

// $(document).on('click', '.link-section .btn.active', function(e){
//     if($('.no-bikes').length > 0){
//         $('a[data-tab-nav="tab2"]').trigger('click');
//         $('.link-section').addClass('hidden');
//     }else{
//         $('.booking-nav__title.active').addClass('success').removeClass('active').next().addClass('active');
//         $('.step-item.active').removeClass('active').next().addClass('active'); 
//         $('.link-section .btn').removeClass('active');
//         $('.link-section').addClass('hidden');
//         updatePersonsSelect();    
//     }
//     $('.select').selectric('refresh');
//     $.get("https://get.geojs.io/v1/ip/geo.json", function(data) {
//         var country = data.country;
//         $('.country').val(country);
//     });
// });



$('.booking-item.selected').each(function(){
    $('input[name="order_start"]').val($(this).find('span:first-child').text());
    $('input[name="order_end"]').val($(this).find('span:last-child').text())
    console.log($('input[name="order_start"]').val());
});
$('.booking-item').on('click', function(){
    $('input[name="order_start"]').val($(this).find('span:first-child').text());
    $('input[name="order_end"]').val($(this).find('span:last-child').text())
});

function addParticipantFields() {
    const template = document.querySelector('.form-item-template').value;
    return template;
  }


$('.people-item').on('click', function(){
    
    const countPeopele = $(this).data('people');
    function updateParticipants() {
        $('#participants-container').empty();
        for (let i = 0; i < countPeopele; i++) {
            $('#participants-container').append(addParticipantFields());
        }
    }
    updateParticipants();
    countrySelect();
    $('.select').selectric('refresh');
});







function sumCalc() {
    const priceTextSum = $('.total-price__item .total-price__price').attr('data-base-price');
    const priceBookingSum = parseFloat(priceTextSum);
    const $selectPrice = $('.payment-sum');
    const priceBookingSumPercent = (Number(priceTextSum) * 40) / 100;
    $selectPrice.find('option').eq(0).val(priceBookingSum);
    $selectPrice.find('option').eq(1).val(priceBookingSumPercent);
    // $('.totalRenderSum').val(priceBookingSum);
    // $selectPrice.addClass('select');
        
}


$('.payment-sum').on('change', function(e) {
    sumCalc();
    const selectedValue = $(this).val();
    // $('.totalRenderSum').val(selectedValue);
    $('.item-price').text(selectedValue + ' €');
});




$('.total-price .btn-red').on('click', function(e){
    $('.step-item.active, .booking-nav__title.active').removeClass('active').next().addClass('active');
    $('.form-desc .tour-bottom .item-price').text($('.total-price__price').text());

    $('.totalRenderSum').val($('.total-price__price').text());

    $('.form-desc .tour-bottom .item-price').attr('data-price', $('.total-price__price').attr('data-total-price'));
    $('.payment-sum').selectric('refresh');
    $('.select').selectric('refresh');
    sumCalc();
});




$(document).on('change', '#participants-container input, #participants-container select', function(e) {
    const participantsData = [];
    $('#participants-container .form-item').each(function() {
        const participant = {
            name: $(this).find('input[type="text"]').val(),
            country: $(this).find('select').eq(0).val(),
            phone: $(this).find('input[type="text"]').eq(1).val(),
            communication: $(this).find('select').eq(1).val(),
            email: $(this).find('input[type="email"]').val()
        };
        participantsData.push(participant);
    });
    const jsonData = JSON.stringify(participantsData);
    $('.items').val(jsonData);
  });

  

  function showMore() {
    const items = $('.items-bikes .items .item');
    const itemsCount = items.length;
    if (itemsCount > 3) {
        $('.items-bikes .btn-blog').removeClass('hidden');
        items.slice(3).addClass('hidden');
    }
}

showMore();

$('.items-bikes .btn-blog .btn-red').on('click', function(e) {
    e.preventDefault(); 
    $('.items .item.hidden').removeClass('hidden');
    $(this).closest('.btn-blog').addClass('hidden');
});

$('.people_count').each(function (){
    $(this).val($('.people-item.selected').find('.add-price').attr('data-add-people'));
});
$('.accommodation_count').each(function (){
    $(this).val($('.accommodation-item.selected').find('.add-price').attr('data-add-accommodation'));
});
$('.people_count_title').each(function (){
    $(this).val($('.people-item.selected').find('.people-item__title').text());
});
$('.accommodation_count_title').each(function (){
    $(this).val($('.accommodation-item.selected').find('.accommodation-item__title span').text());
});


function countrySelect() {
    const selectElements = $('.label_counnrty select');

    selectElements.each(function () {
        const select = $(this);        
        select.empty();        
        $.getJSON('/wp-content/themes/adv-mania/assets/js/countries.json', function (countries) {            
            countries.forEach(function (item) {
                select.append(`<option value="${item.country}">${item.country}</option>`);
            });            
            $.get("https://get.geojs.io/v1/ip/geo.json", function (data) {
                const userCountry = data.country;                
                if (countries.some(c => c.country === userCountry)) {
                    select.val(userCountry);
                }                
                select.selectric('refresh');
            }).fail(function () {
                console.error('Не вдалося отримати країну за IP.');                
                select.selectric('refresh');
            });
        }).fail(function () {
            console.error('Не вдалося завантажити JSON-файл.');
        });
    });
}


countrySelect();