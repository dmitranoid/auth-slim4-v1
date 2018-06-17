/// toasts

$(function () {
    //datepicker
    $('.datepicker').pickadate({
        format: 'dd.mm.yyyy',
        formatSubmit: 'dd.mm.yyyy'
    });

    // toasts close button
    $('.toast .btn-clear').on('click', function () {
        $(this).parents('.toast').first().fadeOut(500);
    });

    // закрытие модального окна
    $('.modal .btn-clear').on('click', function () {
        $(this).parents('.modal').removeClass('active');
        return false;
    });

    // flash уведомления
    if (typeof(flashMessages) !== 'undefined') {
        if (flashMessages.hasOwnProperty('info')) {
            successToast(flashMessages.info.join('<br>'), 0);
        }
        if (flashMessages.hasOwnProperty('error')) {
            errorToast(flashMessages.error.join('<br>'), 0);
        }
    }
    // показ кнопки scroll-to-top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            $('#scroll-to-top').fadeIn();
        } else {
            $('#scroll-to-top').fadeOut();
        }
    });
    $('#scroll-to-top').on('click', scrollToTop);
});

/**
 * добавляет параметр к текущему url b возвращает измененный url
 * @param name наименование параметра
 * @param value значение параметра
 */
function urlAddParam(name, value) {
    var l = window.location;

    /* разбираем url исходной страницы */
    var params = {};
    var x = /(?:\??)([^=&?]+)=?([^&?]*)/g;
    var s = l.search;
    for (var r = x.exec(s); r; r = x.exec(s)) {
        r[1] = decodeURIComponent(r[1]);
        if (!r[2]) r[2] = '%%';
        params[r[1]] = r[2];
    }

    /* добавляем новый параметр*/
    params[name] = encodeURIComponent(value);

    /* заново строим queryString */
    var search = [];
    for (var i in params) {
        var p = encodeURIComponent(i);
        var v = params[i];
        if (v != '%%') p += '=' + v;
        search.push(p);
    }
    search = search.join('&');

    //l.search = search; // сразу перейти
    return l.href.replace(l.search, '?' + search);
}

/**
 * обработчик кнопки scroll-to-top
 */
function scrollToTop() {
    $("html, body").animate({scrollTop: 0}, "fast");
    return false;
}


/**
 * показать сообщение по типу
 * @param type
 * @param message
 * @param hideTime
 * @returns {boolean}
 */
function showToast(type, message, hideTime) {
    if (typeof hideTime === 'undefined') {
        hideTime = 2000;
    }
    toastClass = '.toast-' + type;
    toastElement = $(toastClass);
    toastElement.find('p').html(message);
    toastElement.show(200);
    if (hideTime > 0) {
        setTimeout(function () {
            toastElement.hide(200);
            toastElement.find('p').html('');
        }, hideTime);
    }
    return true;
}

/**
 * основное сообщение
 * @param message
 * @param hideTime
 */
function primaryToast(message, hideTime) {
    showToast('primary', message, hideTime)
}

/**
 * сообщение об успехе
 * @param message
 * @param hideTime
 */
function successToast(message, hideTime) {
    showToast('success', message, hideTime)
}

/**
 * сообщение с предупреждением
 * @param message
 * @param hideTime
 */
function warningToast(message, hideTime) {
    showToast('warning', message, hideTime)
}

/**
 * сообщение об ошибке
 * @param message
 * @param hideTime
 */
function errorToast(message, hideTime) {
    showToast('error', message, hideTime)
}

// модальные окна
/**
 * показать настраиваемое модальное окно
 * @param title заголовок
 * @param content   содержание
 * @param footer  блок с кнопками
 */
function showModalWindow(title, content, footer) {
    var modalElement = $('.modal').first();
    modalElement.find('.modal-title').html(title);
    modalElement.find('.modal-body .content').html(content);
    modalElement.find('.modal-footer').html(footer);
    modalElement.addClass('active');
}

function hideModalWindow() {
    var modalElement = $('.modal').first();
    modalElement.find('.modal-title').html('');
    modalElement.find('.modal-body .content').html('');
    modalElement.find('.modal-footer').html('');
    modalElement.removeClass('active');
}

/**
 * показавать модальное окно с кнопками
 * @param title заголовок окна
 * @param content содержимое
 * @param okButtonCallback callback нажатия на кнопку подтверждения
 * @param options дополнительные параметры
 */
function showModalWindowOkCancel(title, content, okButtonCallback, options) {
    var defaults = {
        okButtonText: 'ок',
        okButtonTemplate: '<button class="btn btn-primary m-2 modal-ok-button" value=""></button>',
        cancelButtonText: 'отменить',
        cancelButtonTemplate: '<button class="btn m-2 modal-cancel-button" value=""></button>'
    };
    options = $.extend({}, defaults, options || {});
    var okButton = $(options.okButtonTemplate).html(options.okButtonText);
    var cancelButton = $(options.cancelButtonTemplate).html(options.cancelButtonText);
    var footer = $('<div></div>')
        .append(okButton)
        .append(cancelButton);

    var modalElement = $('.modal').first();
    okButton.on('click', okButtonCallback);
    // на кнопку cancel просто закрываем
    cancelButton.on('click', function () {
        $('.modal .btn-clear').trigger('click')
    });

    modalElement.find('.modal-title').html(title);
    modalElement.find('.modal-body .content').html(content);
    modalElement.find('.modal-footer').empty().append(footer);
    modalElement.addClass('active');
}
