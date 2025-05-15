$(window).on('scroll', function () {
    var $this = $(this),
        $header = $('.header');
    if ($this.scrollTop() > 1) {
        $header.addClass('scroll-nav');
    } else {
        $header.removeClass('scroll-nav');
    }
});

function popupOpen() {

    $(document).on('click', '.btn-popup', function (e) {
        e.preventDefault();
        var $this = $(this);
        var popupButtonData = $this.data('popup');
        if ($this.data('video-link')) {
            $('.popup video').attr('src', $this.data('video-link'));
            $('.popup video').attr('poster', $this.data('video-poster'));
            $('.popup').removeClass('active');
            $('div[data-popup = ' + popupButtonData + ']').addClass('active');
            $('body').addClass('is-scroll');
        } else {
            $('.popup').removeClass('active');
            $('div[data-popup = ' + popupButtonData + ']').addClass('active');
            $('body').addClass('is-scroll');
        }

    });

}

popupOpen();
$('.popup-close').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.parent().parent().removeClass('active');
    $('.popup-overlay').removeClass('active');
    $('body').removeClass('is-scroll');
    $('.popup-suptitle').removeClass('send');
    $('.popup-form, .popup-title, .popup-text').removeClass('hidden');
    $('.popup-success').addClass('hidden');
});
$('.popup-overlay').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.removeClass('active');
    $('.popup').removeClass('active');
    $('body').removeClass('is-scroll');
});
$('.info-slider').slick({
    dots: true,
    infinite: false,
    speed: 500,
    fade: true,
    // autoplay: true,
    // autoplaySpeed: 2000,
    arrows: true,
    cssEase: 'linear'
});
$('.safety-slider').slick({
    dots: true,
    infinite: false,
    speed: 500,
    fade: true,
    // autoplay: true,
    // autoplaySpeed: 2000,
    arrows: true,
    cssEase: 'linear'
});

$(document).ready(function () {
    let currentAudioPlayer = null;
    $('.audio-item').each(function () {
        const audioElement = $(this).find('audio')[0];
        const audioElementSrc = $(this).find('audio').attr('data-src');
        $(audioElement).attr('src', audioElementSrc);
        const playButton = $(this).find('.play-btn');
        const progressBar = $(this).find('.progress-bar');
        const seekBar = $(this).find('.seek-bar');
        const currentTimeDisplay = $(this).find('.current-time');
        const durationTimeDisplay = $(this).find('.duration-time');
        const loadingIndicator = $(this).find('.loading-indicator');
        const svgBars = $(this).find('.bars rect');

        audioElement.addEventListener('loadedmetadata', function () {
            seekBar.attr('max', audioElement.duration);
            durationTimeDisplay.text(formatTime(audioElement.duration));
        });

        audioElement.addEventListener('loadstart', function () {
            loadingIndicator.show();
        });

        audioElement.addEventListener('canplaythrough', function () {
            loadingIndicator.hide();
        });

        audioElement.addEventListener('ended', function () {
            playButton.find('span').toggleClass('hidden');
            currentAudioPlayer = null;
        });

        playButton.on('click', function () {
            if (currentAudioPlayer && currentAudioPlayer !== audioElement) {
                currentAudioPlayer.pause();
                $(currentAudioPlayer).closest('.audio-item').find('.play-btn span').toggleClass('hidden');
            }

            if (audioElement.paused) {
                audioElement.play();
                currentAudioPlayer = audioElement;
                playButton.find('span').toggleClass('hidden');
            } else {
                audioElement.pause();
                currentAudioPlayer = null;
                playButton.find('span').toggleClass('hidden');
            }
        });

        audioElement.addEventListener('timeupdate', function () {
            const percent = (audioElement.currentTime / audioElement.duration) * 100;
            progressBar.css('width', percent + '%');
            seekBar.val(audioElement.currentTime);
            currentTimeDisplay.text(formatTime(audioElement.currentTime));
            updateSvgColor(percent);
        });

        seekBar.on('input', function () {
            audioElement.currentTime = seekBar.val();
        });

        function updateSvgColor(percent) {
            svgBars.each(function (index) {
                $(this).attr('fill', index < (percent / 100) * svgBars.length ? '#e7343f' : '#8e8e89');
            });
        }

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        }
    });
});


$('.reviews-video').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    fade: true,
    asNavFor: '.reviews-slider'
});
$('.reviews-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    asNavFor: '.reviews-video',
    dots: false,
    focusOnSelect: true
});

$('.faq-item').on('click', function (e) {
    e.preventDefault();
    const $ths = $(this);
    if ($ths.hasClass('active')) {
        $ths.find('.faq-item__text').slideUp();
        $ths.removeClass('active');
    } else {
        $ths.find('.faq-item__text').slideDown();
        $ths.addClass('active');
    }

});
$('.header-btn').on('click', function (e) {
    const $this = $(this);
    $this.toggleClass('active');
    $('.header').toggleClass('active');
    $('body').toggleClass('is-scroll');
});
$('.header-overlay').on('click', function (e) {
    const $this = $(this);
    $('.header-btn').removeClass('active');
    $('.header').removeClass('active');
    $('body').removeClass('is-scroll');
});

$('.btn-scroll').on('click', function (e) {
    const $this = $(this);
    if ($this.data('scroll')) {
        e.preventDefault();
        const thisData = $this.data('scroll');
        $('html, body').animate({
            scrollTop: $('.' + thisData).offset().top
        }, 1500);
    } else {

    }
});
$('.header-lang').on('click', function (e) {
    const $this = $(this);
    $this.toggleClass('active');
});

$(document).mouseup(function (e) {
    var div = $('.header-lang');
    if (!div.is(e.target)
        && div.has(e.target).length === 0) {
        div.removeClass('active');
    }
});
// blog-slider
$('.blog-slider').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    dots: true,
    responsive: [
        {
            breakpoint: 769,
            settings: {
                slidesToShow: 1,
                adaptiveHeight: true,
                slidesToScroll: 1
            }
        }
    ]
});
$('.tour-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    dots: true,
    fade: true,
    asNavFor: '.tour-nav'
});
$('.tour-nav').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    asNavFor: '.tour-for',
    focusOnSelect: true,
    arrows: false,
});
$('.accordion-item__title').on('click', function (e) {
    e.preventDefault();
    const $ths = $(this);

    if ($ths.parent().hasClass('active')) {
        $('.accordion-item').removeClass('active');
        $('.accordion-item__text').slideUp();
        $ths.next().slideUp();
        $ths.parent().removeClass('active');
    } else {
        $('.accordion-item').removeClass('active');
        $('.accordion-item__text').slideUp();
        $ths.next().slideDown();
        $ths.parent().addClass('active');
    }
});

$('.select').selectric();

$(document).ready(function () {
    if ($('.wp-block-gallery .wp-block-image').length) {
        const gal = '<div class="gal-post active-gal"></div>';
        $('.wp-block-gallery').append(gal);
        $('.wp-block-gallery .wp-block-image').each(function () {
            const img = $(this).find('img');
            const src = img.attr('src');
            const galLink = '<a href="' + src + '" data-fancybox="gal"><img src="' + src + '" alt="post"></a>';
            $('.gal-post').append(galLink);
            $('.wp-block-image').remove();
        });
        Fancybox.bind("[data-fancybox]", {});
        $('.gal-post').each(function () {
            const allElemns = $(this).find('a').length;
            if (allElemns > 3) {
                $(this).addClass('active-gal');
                $(this).find('a:nth-child(3)').append('<span class="title-gal"> See all ' + allElemns + '</span>');
            }
        });
        // const galleryContainer = $('<div class="gal-post active-gal"></div>');
        // $('.wp-block-gallery .wp-block-image').each(function() {
        //     const img = $(this).find('img');
        //     const src = img.attr('src');
        //     const link = $('<a></a>')
        //         .attr('href', src)
        //         .attr('data-fancybox', 'gal')
        //         .append(img.clone());
        //     galleryContainer.append(link);
        // });
        // $('.wp-block-gallery').replaceWith(galleryContainer);
        // $('[data-fancybox="gal"]').fancybox({});
    }

    $('#submit-btn').on('click', function (event) {
        event.preventDefault();
        if ($('#ajax-form')[0].checkValidity()) {
            var formData = $('#ajax-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/wp-admin/admin-ajax.php',
                data: {
                    action: 'send_email',
                    form_data: formData
                },
                success: function (response) {
                    if (response.success) {
                        $('.popup-form, .popup-title, .popup-text').addClass('hidden');
                        $('.popup-suptitle').addClass('send');
                        $('.popup-success, .popup-success .popup-title, .popup-success .popup-text').removeClass('hidden');
                        $('#ajax-form')[0].reset();
                        console.log('Form submitted successfully!');
                    } else {
                        console.log('There was an error submitting the form.');
                    }
                },
                error: function () {
                    console.log('There was an error submitting the form.');
                }
            });
        } else {
            console.log('Please fill in all required fields.');
        }
    });
});

AOS.init({disable: 'mobile'});