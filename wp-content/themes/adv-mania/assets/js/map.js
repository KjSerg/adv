
$('.moto-id').each(function(e){
    infoId = [];
    const motoId = $('.moto-id ul li');
    for ( var i = 0; i < motoId.length; i++ ) {
        infoId.push(motoId[i].innerHTML);
    } 
    const jsonInfoId = JSON.stringify(infoId);
    console.log(jsonInfoId);
    $('.btn-book').attr('data-moto-id', jsonInfoId);
});
function setCookie(name, value, minutes) {
    var expires = "";
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime() + (minutes * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
$('.btn-book').on('click', function(e){
    localStorage.removeItem('tour_title');
    localStorage.removeItem('tour_id');
    localStorage.removeItem('moto_tour_id');
    localStorage.removeItem('tour_price');
    localStorage.setItem('tour_id', $(this).data('id'));
    localStorage.setItem('tour_title', $(this).data('title'));
    localStorage.setItem('tour_price', $(this).data('price'));
    localStorage.setItem('moto_tour_id', $(this).data('moto-id'));
    localStorage.setItem('original_tour_id', $(this).data('translated'));
    setCookie('input_tour_id', localStorage.getItem('tour_id'), 30);
    setCookie('original_tour_id', localStorage.getItem('original_tour_id'), 30); // Термін дії 30 хвилин
});


function calcSumCountTour(){
    const basePrice = parseFloat($('.count_item').data('price'));
            function formatPrice(price) {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' €';
            }
            $('.plus').click(function() {
                let input = $(this).siblings('.count_item');
                let currentValue = parseInt(input.val());
                input.val(currentValue + 1);
                let newPrice = basePrice * (currentValue + 1);
                $('.tour-desc .item-price').text(formatPrice(newPrice));
                $('.btn-book').attr('data-price', formatPrice(newPrice));
            });

            $('.minus').click(function() {
                let input = $(this).siblings('.count_item');
                let currentValue = parseInt(input.val());
                if (currentValue > 1) {
                    input.val(currentValue - 1);
                    let newPrice = basePrice * (currentValue - 1);
                    $('.tour-desc .item-price').text(formatPrice(newPrice));
                    $('.btn-book').attr('data-price', formatPrice(newPrice));
                }
            });
}
calcSumCountTour();

document.cookie = "page_type=tour; path=/; max-age=3600; SameSite=Strict";

function showMore() {
    const items = $('.items .item');
    const itemsCount = items.length;
    if (itemsCount > 3) {
        $('#other-bike .btn-blog').removeClass('hidden');
        items.slice(3).addClass('hidden');
    }
}

showMore();

$('#other-bike .btn-blog .btn-red').on('click', function(e) {
    e.preventDefault(); 
    $('.items .item.hidden').removeClass('hidden');
    $(this).closest('.btn-blog').addClass('hidden');
});

if($('.sction-map').length > 0){
    const centerLatLngs = $('.map-inner').attr('data-map-center');
    const [lat, lng] = centerLatLngs.split(',').map(Number);
    const latLngs = [];
    let selectedLatLngs = [];
    let currentIndex = null;
    let markers = [];
    let polyline;
    let redPolyline;
    
    const map = L.map('map').setView([lat, lng], 19); 
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '',
    }).addTo(map);
    document.querySelectorAll('.route-btn').forEach(button => {
        const lat = parseFloat(button.dataset.lat);
        const lng = parseFloat(button.dataset.lng);
        const title = button.innerHTML;
        latLngs.push([lat, lng]);
        const marker = L.marker([lat, lng], {
            icon: L.icon({
                iconUrl: '/wp-content/themes/adv-mania/img/pin-1.svg',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            })
        }).addTo(map);
        marker.bindPopup(title);
        markers.push(marker);
    });
    
    polyline = L.polyline(latLngs, { color: '#000' }).addTo(map);
    map.fitBounds(polyline.getBounds());
    
    document.querySelectorAll('.route-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (currentIndex !== null) {
                document.querySelector(`.route-btn[data-index="${currentIndex}"]`).classList.remove('active');
                markers[currentIndex].setIcon(L.icon({
                    iconUrl: '/wp-content/themes/adv-mania/img/pin-1.svg',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                }));
            }
            currentIndex = button.dataset.index;
            button.classList.add('active');
            markers[currentIndex].setIcon(L.icon({
                iconUrl: '/wp-content/themes/adv-mania/img/pin-2.svg',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            }));
            selectedLatLngs = latLngs.slice(0, parseInt(currentIndex) + 1); 
            if (redPolyline) {
                map.removeLayer(redPolyline);
            }
            if (selectedLatLngs.length > 0) {
                redPolyline = L.polyline(selectedLatLngs, { color: 'red' }).addTo(map);
                map.fitBounds(redPolyline.getBounds());
            }
            markers[currentIndex].getElement().classList.add('bounce');
            setTimeout(() => {
                markers[currentIndex].getElement().classList.remove('bounce');
            }, 1000);
        });
    });
}
