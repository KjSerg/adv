var $doc = $(document);
var promo = {};
var submitForm = false;

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
    $doc.on('submit', '.form-js', function (e) {
        e.preventDefault();
        var $form = jQuery(this);
        var this_form = $form.attr('id');
        var test = true,
            thsInputs = $form.find('input, textarea'),
            $select = $form.find('select[required]');
        var $address = $form.find('input.address-js[required]');
        $select.each(function () {
            var $ths = jQuery(this);
            var $label = $ths.closest('.form-label');
            var val = $ths.val();
            if (Array.isArray(val) && val.length === 0) {
                test = false;
                $label.addClass('error');
            } else {
                $label.removeClass('error');
                if (val === null || val === undefined) {
                    test = false;
                    $label.addClass('error');
                }
            }
        });
        thsInputs.each(function () {
            var thsInput = jQuery(this),
                $label = thsInput.closest('.form-label'),
                thsInputType = thsInput.attr('type'),
                thsInputVal = thsInput.val().trim(),
                inputReg = new RegExp(thsInput.data('reg')),
                inputTest = inputReg.test(thsInputVal);
            if (thsInput.attr('required')) {
                if (thsInputVal.length <= 0) {
                    test = false;
                    thsInput.addClass('error');
                    $label.addClass('error');
                    thsInput.focus();
                } else {
                    thsInput.removeClass('error');
                    $label.removeClass('error');
                    if (thsInput.data('reg')) {
                        if (inputTest === false) {
                            test = false;
                            thsInput.addClass('error');
                            $label.addClass('error');
                            thsInput.focus();
                        } else {
                            thsInput.removeClass('error');
                            $label.removeClass('error');
                        }
                    }
                }
            }
        });
        if (!validationInputs($form)) return;
        var $inp = $form.find('input[name="consent"]');
        if ($inp.length > 0) {
            if ($inp.prop('checked') === false) {
                $inp.closest('.form-consent').addClass('error');
                return;
            }
            $inp.closest('.form-consent').removeClass('error');
        }
        if (test) {
            if (submitForm) return;
            submitForm = true;
            var thisForm = document.getElementById(this_form);
            var formData = new FormData(thisForm);
            var data = {
                type: $form.attr('method'),
                url: $form.attr('action'),
                processData: false,
                contentType: false,
                data: formData,
            };
            $form.trigger('reset');
            sendRequest(data);
        }
    });
    $(document).on('click', '.items-bikes .item .btn-red', function (e) {
        e.preventDefault();
        const $item = $(this).closest('.item');
        const $t = $(this);
        var $selector = $(document).find('.items-bikes');
        if ($item.hasClass('selected')) {
            $selector.find('.item .btn-red').not($t).attr('disabled', 'disabled');
        } else {
            $selector.find('.item .btn-red').not($t).removeAttr('disabled');
        }
    });
});

function sendRequest(data) {
    jQuery.ajax(data).done(function (r) {
        if (r) {
            if (isJsonString(r)) {
                var res = JSON.parse(r);
                if (res.msg !== '' && res.msg !== undefined) {
                    showMsg(res.msg);
                }
            } else {
                showMsg(r);
            }
        }
        submitForm = false;
    });
}

function validationInputs($form) {
    var obj = {};
    var res = true;
    var $requiredInputs = $form.find('[data-required]');
    $requiredInputs.each(function () {
        var $t = jQuery(this);
        var name = $t.attr('name');
        if (name !== undefined) {
            var hasChecked = $t.prop('checked') === true;
            if (obj[name] === undefined) obj[name] = [];
            if (hasChecked) {
                obj[name].push($t.val());
            }
        }
    });
    for (var key in obj) {
        var items = obj[key];
        if (items.length === 0) {
            res = false;
            $form.find('[name="' + key + '"]').closest('.form-label').addClass('error');
        } else {
            $form.find('[name="' + key + '"]').closest('.form-label').removeClass('error');
        }

    }
    return res;
}

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
    $doc.find('#promocode').addClass('not-active');
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