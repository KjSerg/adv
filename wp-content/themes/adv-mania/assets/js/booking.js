$('.moto-id').each(function (e) {
    infoId = [];
    const motoId = $('.moto-id ul li');
    for (var i = 0; i < motoId.length; i++) {
        infoId.push(motoId[i].innerHTML);
    }
    const jsonInfoId = JSON.stringify(infoId);
    $('.btn-book').attr('data-moto-id', jsonInfoId);
});
$('.btn-book').on('click', function (e) {
    localStorage.setItem('moto_tour_id', $(this).data('moto-id'));
});


// function handlePriceCalculation(element, priceSelector) {
//     if (element.hasClass('selected')) {
//         return;
//     } else {
//         element.siblings().removeClass('selected');
//         element.addClass('selected');
//         if (element.find(priceSelector).length > 0) {
//             const $basePriceText = $('.booking-tour__info .item-price').text().trim();
//             const $addToPriceText = element.find(priceSelector).text().trim();
//             const $basePrice = parseFloat($basePriceText.replace(/[^\d.-]/g, '')) || 0;
//             const $addToPrice = parseFloat($addToPriceText.replace(/[^\d.-]/g, '')) || 0;
//             const $totalPrice = $basePrice + $addToPrice;
//             $('.total-price__price').text($totalPrice + '€');
//             $('.total-price__price').attr('data-total-price', $totalPrice + '€');
//             $('.total-price__price').attr('data-base-price', $totalPrice + '€');
//         }
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


// $('.total-price__price').each(function(e){
//     const $basePrice = $('.booking-tour__info .item-price').text();
//     $(this).text($basePrice);
//     $(this).attr('data-total-price', $basePrice);
//     $(this).attr('data-base-price', $basePrice);
// });

$('.booking-item').on('click', function (e) {
    $('.booking-item').removeClass('selected');
    $(this).addClass('selected');
});

// $('.items-bikes .item .btn-red').on('click', function(e) {
//     e.preventDefault();
//     if($(this).parents('.item').hasClass('selected')){
//         $(this).parents('.item').removeClass('selected');
//         $(this).removeClass('item-book');
//     }else{
//         $(this).parents('.item').addClass('selected');
//         $(this).addClass('item-book');
//     }
//     if($(this).parents('.item').find($('.count').length > 0)){
//         $(this).parents('.item').find($('.count')).toggleClass('hidden');
//         $(this).parents('.item').find($('.btn-red span')).toggleClass('hidden');
//     }else{}
// });
