const tour_id = localStorage.getItem('tour_id');
const tour_name = localStorage.getItem('tour_title');
const rent_text = $('.rent_text').text();
const days_text = $('.days_text').text();
$('.tour_id').val(tour_id);
// $('.tour_name').val(tour_name)
$(document).on('change', '.form-item input, .form-item select', function (e) {
    const participantsData = [];
    $('.form-item').each(function () {
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

function sumCalc() {
    const priceTextSum = $('.total-price__price').attr('data-base-price');
    const priceBookingSum = parseFloat(priceTextSum);
    const $selectPrice = $('.payment-sum');
    const priceBookingSumPercent = (priceBookingSum * 40) / 100;
    $selectPrice.find('option').eq(0).val(priceBookingSum);
    $selectPrice.find('option').eq(1).val(priceBookingSumPercent);
    $('.totalRenderSum').val(priceBookingSum);

}


$('.payment-sum').on('change', function (e) {
    sumCalc();
    const selectedValue = $(this).val();
    $('.totalRenderSum').val(selectedValue);
    $('.item-price').text(selectedValue + ' €');
});

$('.total-price .btn-red').on('click', function (e) {
    $('.step-item.active, .booking-nav__title.active').removeClass('active').next().addClass('active');
    $('.form-desc .tour-bottom .item-price').text($('.total-price__price').text());
    $('.form-desc .tour-bottom .item-price').attr('data-price', $('.total-price__price').attr('data-base-price'));
    $('.payment-sum').selectric('refresh');
    $('.select').selectric('refresh');
    sumCalc();
});

$('.booking-moto__wrap .item-price__info').each(function (e) {
    $(this).text(rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text);
});
$('.booking-moto__wrap .item-price').each(function (e) {
    const basePrice = localStorage.getItem('totalPrice');
    const formattedPrice = new Intl.NumberFormat('uk-UA').format(basePrice);
    $(this).text(formattedPrice + '€');
    $('.total-price__price').text(formattedPrice + '€');
    $('.total-price__price').attr('data-total-price', formattedPrice + '€');
    $('.total-price__price').attr('data-base-price', formattedPrice + '€');
});

$('.booking-item__dates .start').each(function (e) {
    var date = new Date(localStorage.getItem('startDate'));
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = date.getDate().toString().padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
    $(this).text(formattedDate);
    $('input[name="order_start"]').val(formattedDate);
});
$('.booking-item__dates .end').each(function (e) {
    var date = new Date(localStorage.getItem('endDate'));
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = date.getDate().toString().padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
    $(this).text(formattedDate);
    $('input[name="order_end"]').val(formattedDate);
});


// $(document).on('click', '.items-equipment .item', function(e) {
//     e.preventDefault();
//     const selectedItems = $('.item.selected').map(function() {
//         return {
//             "name": $(this).attr('data-title'),
//             "id": $(this).attr('data-id'),
//             "price": parseFloat($item.find('.item-price').text().replace(/[^0-9.-]+/g, '')) * days || 0
//         };
//     }).get(); 
//     $('.equipment').val(JSON.stringify(selectedItems));
// });

let selectedItems = [];
let selectedItemsEquipment = [];

function handleItemSelection(selector, targetArray, outputField) {
    $(document).on('click', `${selector} .item .btn-red`, function (e) {
        e.preventDefault();
        const days = localStorage.getItem('countDays') || 1;
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

// handleItemSelection('.items-bikes', selectedItems, '.motos');
handleItemSelection('.items-equipment', selectedItemsEquipment, '.motos');


$('.text-info-days p').each(function () {
    $(this).text(rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text)
});
$(document).on('click', '.items-equipment .item .btn-red', function (e) {
    e.preventDefault();
    const $t = $(this);
    const $item = $(this).closest('.item');
    const basePrice = parseFloat($item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    let countItemVal = parseInt($item.find('.count_item').val()) || 1;
    const days = localStorage.getItem('countDays');
    const totalItemPrice = basePrice * countItemVal * days;
    updatePrice($item);
    const textInfoDays = $item.find('.item-price__info');
    const textInfoDaysUpdate = rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text;
    $item.find('.count').toggleClass('hidden');
    $(this).toggleClass('item-book');
    $(this).find('span').toggleClass('hidden');
    $item.toggleClass('selected');
    const $totalPriceElement = $('.total-price__price');
    let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    if ($item.hasClass('selected')) {
        currentTotalPrice += totalItemPrice;
        textInfoDays.text(textInfoDaysUpdate);
        $item.find('.item-price__old').addClass('hidden');
        $('.text-info-days p').text(rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text);
    } else {
        currentTotalPrice -= totalItemPrice;
        textInfoDays.text(rent_text);
        $item.find('.item-price').text(basePrice + ' €');
        $item.find('.item-price__old').removeClass('hidden');
        $('.text-info-days p').text(rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text);
    }
    updateTotalPriceDisplay(currentTotalPrice);
});
$(document).on('click', '.items-bikes .item .btn-red', function (e) {
    e.preventDefault();
    const $item = $(this).closest('.item');
    const $t = $(this);
    var $selector =  $(document).find('.items-bikes');

    const basePrice = parseFloat($item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;

    let countItemVal = parseInt($item.find('.count_item').val()) || 1;
    const days = localStorage.getItem('countDays');
    const totalItemPrice = basePrice * countItemVal * days;
    updatePrice($item);
    const textInfoDays = $item.find('.item-price__info');
    const textInfoDaysUpdate = rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text;
    $item.find('.count').toggleClass('hidden');
    $(this).toggleClass('item-book');
    $(this).find('span').toggleClass('hidden');
    $item.toggleClass('selected');
    const $totalPriceElement = $('.total-price__price');
    let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    if ($item.hasClass('selected')) {
        currentTotalPrice += totalItemPrice;
        textInfoDays.text(textInfoDaysUpdate);
        $item.find('.item-price__old').addClass('hidden');
        $('.text-info-days p').text(rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text)
        $selector.find('.item .btn-red').not($t).attr('disabled', 'disabled');

    } else {
        currentTotalPrice -= totalItemPrice;
        textInfoDays.text(rent_text);
        $item.find('.item-price').text(basePrice + ' €');
        $item.find('.item-price__old').removeClass('hidden');
        $('.text-info-days p').text(rent_text + ' ' + localStorage.getItem('countDays') + ' ' + days_text)
        $selector.find('.item .btn-red').not($t).removeAttr('disabled');
    }
    updateTotalPriceDisplay(currentTotalPrice);
});

function updatePrice(item) {
    const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    const quantity = parseInt(item.find('.count_item').val()) || 1;
    const days = localStorage.getItem('countDays');
    const newPrice = basePrice * quantity * days;
    item.find('.item-price').text(newPrice.toFixed(0) + ' €');
}

$('.plus').click(function () {
    const item = $(this).closest('.item');
    const input = item.find('.count_item');
    const currentValue = parseInt(input.val()) || 1;
    const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    const days = localStorage.getItem('countDays');
    input.val(currentValue + 1);
    const totalPrice = basePrice * (currentValue + 1) * days;
    item.find('.item-price').text(totalPrice.toFixed(0) + ' €');
    const $totalPriceElement = $('.total-price__price');
    let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
    currentTotalPrice += basePrice * days;
    updateTotalPriceDisplay(currentTotalPrice);
});
$('.minus').click(function () {
    const item = $(this).closest('.item');
    const input = item.find('.count_item');
    const currentValue = parseInt(input.val()) || 1;
    const basePrice = parseFloat(item.find('.item-price').data('price').toString().replace(/[^0-9.-]+/g, '')) || 0;
    const days = localStorage.getItem('countDays');
    if (currentValue > 1) {
        input.val(currentValue - 1);
        const totalPrice = basePrice * (currentValue - 1) * days;
        item.find('.item-price').text(totalPrice.toFixed(0) + ' €');
        const $totalPriceElement = $('.total-price__price');
        let currentTotalPrice = parseFloat($totalPriceElement.attr('data-base-price').replace(/[^0-9.-]+/g, '')) || 0;
        currentTotalPrice -= basePrice * days;
        updateTotalPriceDisplay(currentTotalPrice);
    }
});

function updateTotalPriceDisplay(price) {
    const $totalPriceElement = $('.total-price__price');
    $totalPriceElement.text(`${price.toFixed(0)} €`);
    $totalPriceElement.attr('data-base-price', price.toFixed(0));
}


function countrySelect() {
    const $select = $('.label_counnrty select');
    if (!$select.length) return console.error('Select not found');
    $.getJSON('/wp-content/themes/adv-mania/assets/js/countries.json')
        .done(countries => {
            countries.forEach(c => $select.append(`<option value="${c.country}">${c.country}</option>`));
            $.get("https://get.geojs.io/v1/ip/geo.json")
                .done(data => {
                    console.log('GeoIP:', data.country);
                    if ($select.find(`option[value="${data.country}"]`).length) {
                        $select.val(data.country).selectric('refresh');
                    }
                })
                .fail(() => console.warn('GeoIP failed'));
            $select.selectric({
                onOpen() {
                    const $items = $('.selectric-items');
                    const $scroll = $items.find('.selectric-scroll');
                    if (!$items.find('.selectric-search-wrapper').length) {
                        $('<div class="selectric-search-wrapper"><input type="text" class="selectric-search" placeholder="Type to search…"></div>')
                            .insertBefore($scroll);
                        $items.on('input', '.selectric-search', function () {
                            const term = $(this).val().toLowerCase();
                            $items.find('ul li').each(function () {
                                $(this).toggle($(this).text().toLowerCase().includes(term));
                            });
                        });
                    }
                    $items.find('.selectric-search').focus();
                }
            });

        })
        .fail(() => console.error('countries.json load failed'));
}

countrySelect();


$('.card-m').on('change', function () {
    var value = parseInt($(this).val(), 10);
    if (isNaN(value) || value < 1 || value > 12) {
        $(this).val('');
    }
});
// Маскуємо поля введення
if ($.fn.mask) {
    $('.card-number').mask('0000 0000 0000 0000');
    $('.card-m').mask('00');
    $('.card-y').mask('00');
    $('.card-cvv').mask('000');
}

// Маппінг полів для копіювання значення у приховані поля форми оплати
var fieldMapping = {
    'card-name': 'cc_owner',
    'card-number': 'card_number',
    'card-m': 'expiry_month',
    'card-y': 'expiry_year',
    'card-cvv': 'cvv'
};

$.each(fieldMapping, function (sourceClass, targetClass) {
    $('.' + sourceClass).on('input change', function () {
        var val = $(this).val();
        if ($('.' + sourceClass).hasClass('card-name')) {
            $('.' + targetClass).val(val);
        } else {
            val = val.replace(/\s/g, '');
            $('.' + targetClass).val(val);
        }
    });
});
$('#checkout-form').submit(function (e) {
    e.preventDefault();

    var form = $('#checkout-form');
    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: form.serialize(),
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

                setTimeout(function () {
                    $('.preloader').removeClass('active');
                    console.log('Thank you. Your order has been sent.');
                }, 3000);
            } else {
                $('#card-errors').text(response.message);
                console.log('card errors in pay');
                $('.preloader').removeClass('active');
            }
        },
        error: function (xhr, str) {
            console.log('Error occurred: ', xhr);
        }
    });
});