var $doc = $(document);
var promo = {};

$doc.ready(function () {
    $doc.on('click', '.set-promo-js', function (e) {
        e.preventDefault();
        var $t = $(this);
        var $i = $doc.find('#promocode');
        var val = $i.val();
        if (promo.id !== undefined) {
            return;
        }
        if (promo.id > 0) {
            return;
        }
        if (val.trim().length === 0) {
            $i.addClass('error');
            return;
        }
        $i.removeClass('error');
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'set_promo_code',
                val: val
            },
            success: function (response) {
                if (isJsonString(response)) {
                    var res = JSON.parse(response);
                    if (res.msg !== undefined && res.msg !== '') showMsg(res.msg);
                    if (res.type === "error") return;
                    promo = res;
                    var id = Number(res.id || 0);
                    var percent = Number(res.id || res.percent);
                    var title = res.title;
                    if (id <= 0 && percent <= 0) return;
                    setPromoCode(res);
                } else {
                    showMsg(response);
                }
            },
            error: function () {
                console.log('There was an error submitting the form.');
            }
        });
    });
    setTimeout(setAdvance, 100);
    $doc.on('click', '.total-price .btn-red', function (e) {
        setAdvance();
    });
    $doc.on('input', '.card-number', function (e) {
        var $t = $(this);
        var val = $t.val();
        var length = val.length;
        if (length === 19) {
            $doc.find('.card-m').focus();
        }
    });
    $doc.on('input', '.card-m', function (e) {
        var $t = $(this);
        var val = $t.val();
        var length = val.length;
        if (length === 2) {
            $doc.find('.card-y').focus();
        }
    });
    $doc.on('input', '.card-y', function (e) {
        var $t = $(this);
        var val = $t.val();
        var length = val.length;
        if (length === 2) {
            $doc.find('.card-cvv').focus();
        }
    });
});

function setAdvance() {
    var $inp = $doc.find('.totalRenderSum');
    var sum = parseFloat($inp.val());
    if (isNaN(sum)) return;
    if (bookingData.advanceCoefficient === undefined) return;
    var advance = bookingData.advanceCoefficient * sum;
    var newSum = Number(advance);
    var newSumStr = newSum + '€';
    $doc.find('.totalRenderSum').attr('data-sum', sum);
    $doc.find('.totalRenderSum').val(newSumStr);
    $doc.find('.booking-advance__value').text(newSumStr);
}

function showMsg(msg) {
    var $dialog = $('div[data-popup="dialog"]');
    $dialog.find('.popup-title').html(msg);
    $('.popup').removeClass('active');
    $dialog.addClass('active');
    $('body').addClass('is-scroll');
}

function setPromoCode(res) {
    var id = Number(res.id || 0);
    var percent = Number(res.percent || 0);
    var title = res.title;
    if (id <= 0 && percent <= 0) return;
    var $inp = $doc.find('.totalRenderSum');
    var sum = parseFloat($inp.attr('data-sum') || $inp.val());
    if (isNaN(sum)) return;
    var discount = (percent / 100) * sum;
    var newSum = Number(sum) - Number(discount);
    var $res = $doc.find('.promo-code-result');
    var resHTML = '-' + percent + '%';
    if ($res.length === 0) {
        $doc.find('.promo-code-form').append('<div class="pay-cvv promo-code-result">' + resHTML + '</div>');
    } else {
        $res.html(resHTML);
    }
    $doc.find('.promo-code-form').addClass('not-active');
    $doc.find('#promocode').attr('disabled', 'disabled');
    setOrderPrice(newSum);
}

function setOrderPrice(sum) {
    var newSumStr = sum + '€';
    var $inp = $doc.find('.totalRenderSum');
    var $totalPriceElement = $doc.find('.total-price__price');
    var $totalPriceSelector = $doc.find('.form-desc .tour-bottom .item-price');
    $totalPriceElement.text(newSumStr);
    $totalPriceElement.attr('data-base-price', sum);
    $totalPriceElement.attr('data-total-price', sum);
    $totalPriceSelector.attr('data-price', newSumStr);
    $totalPriceSelector.text(newSumStr);
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}