$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }

    });
});

var urlSelect2 = '/backend/select2';

function searchHandler(callback = null) {
    const $filterSearch = $('#filter_search');
    const $searchSpinner = $('#search-spinner');
    const $serachClear = $('#search-clear');

    $filterSearch.keyup(function () {
        console.log(indexUrl);
        var term = $(this).val();
        $searchSpinner.removeClass('d-none');
        cerca(term);
    });

    $('#search-clear').click(function () {
        cerca('');
        $filterSearch.val('');
        $serachClear.addClass('d-none');
    });

    function cerca(term) {
        $.ajax({
            url: indexUrl,
            type: 'GET',
            dataType: 'json',
            data: {cerca: term},
            success: function (response) {
                $('#tabella').html(base64_decode(response.html));
                $searchSpinner.addClass('d-none');
                $serachClear.removeClass('d-none');
                if (term == '') {
                    $serachClear.addClass('d-none');
                }

                // Esegue la callback se presente
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    }
}

//Autonumeric
function autonumericImporto(classe) {
    var slides = document.getElementsByClassName(classe);
    for (var i = 0; i < slides.length; i++) {
        if (AutoNumeric.getAutoNumericElement(slides.item(i)) === null) {
            new AutoNumeric(slides.item(i), {
                decimalCharacter: ",",
                decimalPlaces: 2,
                digitGroupSeparator: ".",
                watchExternalChanges: true,
                modifyValueOnWheel: false
            });
        }
    }
}

function autonumericImportoById(id) {
    var obj = document.getElementById(id);
    if (AutoNumeric.getAutoNumericElement(obj) === null) {
        new AutoNumeric(obj, {
            decimalCharacter: ",",
            decimalPlaces: 2,
            digitGroupSeparator: ".",
            watchExternalChanges: true,
            modifyValueOnWheel: false
        });
    }
}

function autonumericIntero(classe) {
    var slides = document.getElementsByClassName(classe);
    for (var i = 0; i < slides.length; i++) {
        if (AutoNumeric.getAutoNumericElement(slides.item(i)) === null) {
            new AutoNumeric(slides.item(i), {
                watchExternalChanges: true,
                decimalCharacter: ",",
                minimumValue: "0",
                decimalPlaces: 0,
                digitGroupSeparator: ".",
                modifyValueOnWheel: false
            });
        }
    }
}

function mostraCampo(campo, mostra, required) {
    if (mostra) {
        $('#div_' + campo).show();
        if (typeof required === 'undefined') {
            required = true;
        }
        if (required) {
            $('#' + campo).prop('required', true);
            $('#label_' + campo).addClass('required');
        }
    } else {
        $('#div_' + campo).hide();
        $('#' + campo).prop('required', false);
        $('#label_' + campo).removeClass('required');
    }
}

function richiediCampo(campo, required) {
    if (required) {
        $('#' + campo).prop('required', true);
        $('#label_' + campo).addClass('required');
    } else {
        $('#' + campo).prop('required', false);
        $('#label_' + campo).removeClass('required');
    }
}

function eliminaHandler(testo) {
    $("#elimina").click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: "Sei sicuro?",
            text: testo,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, ELIMINA!",
            cancelButtonText: "No, non eliminare!",
            reverseButtons: true,
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-success"
            }
        }).then(function (result) {
            if (result.value) {
                elimina(url);
                return true;
            } else if (result.dismiss === "cancel") {
            }
        });
    });

}

function elimina(url) {
    $.ajax(url,
        {
            method: 'DELETE',
            success: function (resp) {
                if (resp.success) {
                    eseguiAzioniResp(resp);
                } else {
                    Swal.fire(
                        "Errore!",
                        resp.message,
                        "error"
                    )
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var err = eval("(" + xhr.responseText + ")");
                Swal.fire(
                    "Errore " + xhr.status,
                    err.message,
                    "error"
                )
            }
        }
    );
}

function eseguiAzioniResp(resp) {
    if (typeof resp.redirect !== 'undefined') {
        location.href = resp.redirect;
        return;
    }
    if (typeof resp.eseguiFunzione !== 'undefined') {
        window[resp.eseguiFunzione]();
    }
    if (typeof resp.chiudiModal !== 'undefined') {
        $(resp.chiudiModal).modal('toggle');
    }

    if (typeof resp.rimuoviOggetti !== 'undefined' && resp.rimuoviOggetti !== null) {
        if (Array.isArray(resp.rimuoviOggetti)) {
            resp.rimuoviOggetti.forEach((oggetto) => $(oggetto).remove());
        }
    }

    if (typeof resp.mostraOggetto !== 'undefined' && resp.mostraOggetto !== null) {
        if (Array.isArray(resp.mostraOggetto)) {
            resp.mostraOggetto.forEach((oggetto) => $(oggetto).show());
        }
    }

    if (typeof resp.nascondiOggetto !== 'undefined' && resp.nascondiOggetto !== null) {
        if (Array.isArray(resp.nascondiOggetto)) {
            resp.nascondiOggetto.forEach((oggetto) => $(oggetto).hide());
        }
    }

    /*  Nuovi */
    if (typeof resp.oggettiReload !== 'undefined' && resp.oggettiReload !== null) {
        // Funzione per gestire ogni coppia chiave-valore
        const processaOggettoReload = (chiave, valore) => {
            var $oggetto = $('#' + chiave);
            if ($oggetto.length === 0) {
                console.log('L\'oggetto con ID=' + '#' + chiave + ' non esiste');
            } else {
                $oggetto.html(base64_decode(valore));
            }
        };
        // Verifica se è un array o un oggetto singolo
        if (Array.isArray(resp.oggettiReload)) {
            resp.oggettiReload.forEach((oggetto, indice) => {
                for (let chiave in oggetto) {
                    if (oggetto.hasOwnProperty(chiave)) {
                        processaOggettoReload(chiave, oggetto[chiave]);
                    }
                }
            });
        } else {
            // Se è un oggetto singolo
            for (let chiave in resp.oggettiReload) {
                if (resp.oggettiReload.hasOwnProperty(chiave)) {
                    processaOggettoReload(chiave, resp.oggettiReload[chiave]);
                }
            }
        }
    }
    if (typeof resp.oggettiReplace !== 'undefined') {
        // Funzione per gestire ogni coppia chiave-valore
        const processaOggettoReplace = (chiave, valore) => {
            var $oggetto = $('#' + chiave);
            if ($oggetto.length === 0) {
                console.log('L\'oggetto con ID=' + '#' + chiave + ' non esiste');
            } else {
                $oggetto.replaceWith(base64_decode(valore));
            }
        };
        // Verifica se è un array o un oggetto singolo
        if (Array.isArray(resp.oggettiReplace)) {
            resp.oggettiReplace.forEach((oggetto, indice) => {
                for (let chiave in oggetto) {
                    if (oggetto.hasOwnProperty(chiave)) {
                        processaOggettoReplace(chiave, oggetto[chiave]);
                    }
                }
            });
        } else {
            // Se è un oggetto singolo
            for (let chiave in resp.oggettiReplace) {
                if (resp.oggettiReplace.hasOwnProperty(chiave)) {
                    processaOggettoReplace(chiave, resp.oggettiReplace[chiave]);
                }
            }
        }
    }
    if (typeof resp.message !== 'undefined') {
        var titolo = 'Bene!';
        if (typeof resp.title !== 'undefined') {
            titolo = resp.title;
        }
        Swal.fire(titolo, resp.message, "success");
    }
}


//varie ajax
function tabAjax() {
    $(document).on('click', '[data-toggle="tabajax"]', function (e) {

        var $this = $(this),
            targ = $this.attr('href'),
            loadurl = $this.attr('data-url');

        console.log('loadurl:' + loadurl);
        console.log('targ:' + targ);

        if (!$(targ).html() || true) {

            $.get(loadurl, function (data) {
                $(targ).html(data);
                //window.livewire.rescan();
            }).fail(function () {
                alert('Errore nel caricamento dei contenuti'); // or whatever
            });

        } else {
            console.log('tab già caricato');
        }

        $this.tab('show');
        return false;
    });

}

function modalAjax() {
    $(document).on('click', '[data-toggle="modal-ajax"]', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var target = '#' + $(this).data('target');
        $.ajax({
            url: url,
            success: function (response, status, xhr) {
                var ct = xhr.getResponseHeader("content-type") || "";
                if (ct.indexOf('json') > -1) {
                    if (typeof response.error !== 'undefined') {
                        Swal.fire(
                            "Errore ",
                            response.error,
                            "error"
                        )
                    }
                    return;
                }
                $(target).html(response);
                $(target).modal('show');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                visualizzaErroreAjax(xhr, ajaxOptions, thrownError);
            }
        });
    });
}


function paginazioneAjax(url, selectorContent, selectorToScroll) {
    $.ajax({
        url: url,
        error: function (xhr, ajaxOptions, thrownError) {
            var err = eval("(" + xhr.responseText + ")");
            Swal.fire(
                "Errore " + xhr.status,
                err.message,
                "error"
            )
        }
    }).done(function (data) {
        $(selectorContent).html(data);
        if (selectorToScroll !== undefined) {

            console.log('scrollo su');
            $('html, body').stop().animate({
                'scrollTop': $(selectorToScroll).offset().top - 120
            }, 400, 'swing');
        } else {
            console.log('No scroll, manca parametro selectorToScroll');
        }
    });
}


function azioneAjax() {
    $('.azione').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax(url,
            {
                method: 'POST',
                success: function (resp) {
                    if (resp.success) {
                        eseguiAzioniResp(resp);
                    } else {
                        Swal.fire(
                            "Errore!",
                            resp.message,
                            "error"
                        )
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    visualizzaErroreAjax(xhr, ajaxOptions, thrownError);
                }
            });
    });
}


function handlerInputTextDropDown() {
    $('.piu-usato').click(function (e) {
        const nome = $(this).text();
        const id = $(this).closest('ul').data('id');
        $('#' + id).val(nome);
    });
}


function formSubmitAndDeleteHandler(form) {
    formSubmitHandler(form)
    formDelete();
}

function formSubmitHandler(form) {
    $(form).off('submit').on('submit', function (e) {
        e.preventDefault();
        formSubmit(this);
    });
}

function formSubmit(obj) {
    var mainDialog = $(obj).closest('.modal');
    var $form = $(obj).closest('form');
    var methodObj = $form.find('input[name="_method"]').first();
    var method = methodObj.val();
    console.log("mainDialog:" + mainDialog.attr('id'));
    console.log('salva premuto');
    console.log('_method: ' + method);
    var url = $form.prop('action').replace(/\/$/, "");
    console.log("url:" + url);
    var datiForm = $form.serializeArray();
    console.log(datiForm);
//Se sono definite aggiungo i campi mainPage e mainPageId
    if (typeof mainPage !== 'undefined') {
        datiForm.push({name: 'mainPage', value: mainPage});
        if (typeof mainPageId !== 'undefined') {
            datiForm.push({name: 'mainPageId', value: mainPageId});
        }
    }
    $.ajax(url,
        {
            method: method,
            data: datiForm,
            dataType: 'json',
            success: function (resp) {
                if (resp.success) {
                    cancellaErrori();
                    eseguiAzioniResp(resp);
                } else {
                    if (typeof resp.errors !== 'undefined') {
                        visualizzaErrori(resp.errors);
                    } else {
                        $("#submit").off('click');
                        alert(resp.message);
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if (xhr.status === 422) {
                    var json = $.parseJSON(xhr.responseText);
                    visualizzaErrori(json.errors);
                } else {
                    visualizzaErroreAjax(xhr, ajaxOptions, thrownError);
                }
            }
        }
    );

    function visualizzaErrori(errori) {
        console.log(errori);
        if ($('#alert-errori').length !== 0) {
            var htmlList = '';
            $.each(errori, function (key, value) {
                htmlList += '<li>' + value + '</li>';
            });
            $("#elenco-errori").html('<ul>' + htmlList + '</ul>');
            $("#alert-errori").show();
        } else {
            var txtList = '';
            $.each(errori, function (key, value) {
                txtList += '\n*' + value;
            });
            console.log(txtList);
            alert('Si sono verificati degli errori:' + txtList);
        }
    }

    function cancellaErrori() {
        $("#elenco-errori").html('');
        $("#alert-errori").hide();
    }

    function aggiornaORicarica(body, objId) {
        console.log('objId:' + objId);
        if (body === undefined || body === '') {
            if (objId != '') {
                location.href = '/' + objId + '/';
            }
        }
    }
}

function formDelete() {
    $("#elimina").click(function (e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        var url = $form.prop('action');
        console.log("url delete:" + url);
        $.ajax(url,
            {
                method: 'DELETE',
                dataType: 'json',
                success: function (resp) {
                    if (resp.success) {
                        eseguiAzioniResp(resp);
                    } else {
                        alert(resp.message);
                    }
                },
                error: function () {
                    jQuery("body").html(resp.responseText);
                }

            });


    });

}


function visualizzaErroreAjax(xhr, ajaxOptions, thrownError) {
    try {
        // Parsing the JSON error response
        const errorResponse = JSON.parse(xhr.responseText);
        Swal.fire(
            "Errore",
            errorResponse.message || "Errore sconosciuto.",
            "error"
        );
    } catch (e) {
        // If response is not JSON
        Swal.fire(
            "Errore",
            "Impossibile elaborare la richiesta. Dettagli: " + thrownError,
            "error"
        );
    }
}


function base64_decode(encodedData) {
    // eslint-disable-line camelcase
    //  discuss at: http://locutus.io/php/base64_decode/
    // original by: Tyler Akins (http://rumkin.com)
    // improved by: Thunder.m
    // improved by: Kevin van Zonneveld (http://kvz.io)
    // improved by: Kevin van Zonneveld (http://kvz.io)
    //    input by: Aman Gupta
    //    input by: Brett Zamir (http://brett-zamir.me)
    // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
    // bugfixed by: Pellentesque Malesuada
    // bugfixed by: Kevin van Zonneveld (http://kvz.io)
    // improved by: Indigo744
    //   example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==')
    //   returns 1: 'Kevin van Zonneveld'
    //   example 2: base64_decode('YQ==')
    //   returns 2: 'a'
    //   example 3: base64_decode('4pyTIMOgIGxhIG1vZGU=')
    //   returns 3: '✓ à la mode'
    // decodeUTF8string()
    // Internal function to decode properly UTF8 string
    // Adapted from Solution #1 at https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
    var decodeUTF8string = function (str) {
        // Going backwards: from bytestream, to percent-encoding, to original string.
        return decodeURIComponent(str.split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
        }).join(''))
    };
    if (typeof window !== 'undefined') {
        if (typeof window.atob !== 'undefined') {
            return decodeUTF8string(window.atob(encodedData))
        }
    } else {
        return new Buffer(encodedData, 'base64').toString('utf-8')
    }
    var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    var o1;
    var o2;
    var o3;
    var h1;
    var h2;
    var h3;
    var h4;
    var bits;
    var i = 0;
    var ac = 0;
    var dec = '';
    var tmpArr = [];
    if (!encodedData) {
        return encodedData
    }
    encodedData += '';
    do {
        // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(encodedData.charAt(i++));
        h2 = b64.indexOf(encodedData.charAt(i++));
        h3 = b64.indexOf(encodedData.charAt(i++));
        h4 = b64.indexOf(encodedData.charAt(i++));
        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;
        if (h3 === 64) {
            tmpArr[ac++] = String.fromCharCode(o1)
        } else if (h4 === 64) {
            tmpArr[ac++] = String.fromCharCode(o1, o2)
        } else {
            tmpArr[ac++] = String.fromCharCode(o1, o2, o3)
        }
    } while (i < encodedData.length);
    dec = tmpArr.join('');
    return decodeUTF8string(dec.replace(/\0+$/, ''))
}

function resizeCanvas(canvas) {
    //Ridimensiona lo screenshot
    var settings = {
        max_width: 470,
        max_height: 400
    };
    var ratiow = 1, ratioh = 1;

    if (canvas.width > settings.max_width)
        ratiow = settings.max_width / canvas.width;
    if (canvas.height > settings.max_height)
        ratioh = settings.max_height / canvas.height;
    console.log('ratiow:' + ratiow + ' ratioh:' + ratioh);


    var ratio = ratiow > ratioh ? ratioh : ratiow;
    ratio = ratio.toFixed(2);
    console.log('ratio:' + ratio);


    var width = canvas.width * ratio;
    var height = canvas.height * ratio;

    console.log('w:' + width + ' h:' + height);

    var canvasCopy = document.createElement("canvas");
    var copyContext = canvasCopy.getContext("2d");
    canvasCopy.width = width;
    canvasCopy.height = height;
    copyContext.drawImage(canvas, 0, 0, width, height);
    return canvasCopy;

}

function select2Focus(obj) {
    var id = obj.attr('id');
    id = "select2-" + id + "-results";
    var input = $("[aria-controls='" + id + "']");
    setTimeout(function () {
        input.delay(100).focus().select();
    }, 100);
}

function select2Universale(idSenzaCancelletto, testo, minimumInputLength, select2) {
    var url = urlSelect2;
    if (minimumInputLength === undefined) {
        minimumInputLength = 2;
    }
    if (select2 === undefined) {
        select2 = idSenzaCancelletto;
    }
    $obj = $('#' + idSenzaCancelletto);
    if ($obj.data('url')) {
        url = $obj.data('url');
    }
    return $obj.select2({
        placeholder: 'Seleziona ' + testo,
        minimumInputLength: minimumInputLength,
        allowClear: true,
        width: '100%',
        dropdownParent: dentroLaModal($obj),
        ajax: {
            quietMillis: 150,
            url: url + "?" + select2,
            dataType: 'json',
            data: function (term, page) {
                return {
                    term: term.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });
}

function dentroLaModal($obj) {
    var modal = $obj.closest('.modal'); // Trova il genitore con la classe .modal
    if (modal.length > 0) {
        return '#' + modal.attr('id'); // Recupera l'ID della modal
    } else {
        return null;
    }
}

function select2Citta(campo) {
    if (campo === undefined) {
        campo = 'citta';
    }
    $('#' + campo).select2({
        placeholder: 'Seleziona una città',
        minimumInputLength: 1,
        allowClear: true,
        width: '100%',
        // dropdownParent: $('#modalPosizione'),
        ajax: {
            quietMillis: 150,
            url: urlSelect2 + "?citta",
            dataType: 'json',
            data: function (term, page) {
                return {
                    term: term.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    }).on('select2:select', function (e) {
        // Access to full data
        if (campo === 'citta') {
            $("#cap").val(e.params.data.cap);
        } else if (campo.startsWith('citta_')) {
            $("#cap_" + campo.replace('citta_', '')).val(e.params.data.cap);
        } else {
            $("#cap_" + campo).val(e.params.data.cap);
        }
    });
}

$.fn.isInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
};


function confirmDatePlugin(pluginConfig) {
    const defaultConfig = {
        confirmIcon: "",
        confirmText: "Conferma",
        showAlways: false,
        theme: "light"
    };

    const config = {};
    for (let key in defaultConfig) {
        config[key] = pluginConfig && pluginConfig[key] !== undefined
            ? pluginConfig[key]
            : defaultConfig[key];
    }

    return function (fp) {
        const hooks = {
            onKeyDown(_, __, ___, e) {
                if (fp.config.enableTime && e.key === "Tab" && e.target === fp.amPM) {
                    e.preventDefault();
                    fp.confirmContainer.focus();
                } else if (e.key === "Enter" && e.target === fp.confirmContainer)
                    fp.close();
            },

            onReady() {
                if (fp.calendarContainer === undefined)
                    return;

                fp.confirmContainer = fp._createElement(
                    "button",
                    `btn btn-primary btn-sm mb-2 flatpickr-confirm ${config.showAlways ? "visible" : ""} ${config.theme}Theme`,
                    config.confirmText
                );

                fp.confirmContainer.tabIndex = -1;
                fp.confirmContainer.innerHTML += config.confirmIcon;

                fp.confirmContainer.addEventListener("click", fp.close);
                fp.calendarContainer.appendChild(fp.confirmContainer);
            }
        };

        if (!config.showAlways)
            hooks.onChange = function (dateObj, dateStr) {
                const showCondition = fp.config.enableTime || fp.config.mode === "multiple";
                if (dateStr && !fp.config.inline && showCondition)
                    return fp.confirmContainer.classList.add("visible");
                fp.confirmContainer.classList.remove("visible");
            }

        return hooks;
    }
}


function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function updateQueryStringParameter(uri, key, value) {
    if (uri == null) {
        uri = window.location.href;
    }
    var uriObj = new URL(uri);
    var params = new URLSearchParams(uriObj.search);
    if (params.has(key)) {
        params.set(key, value);
    } else {
        params.append(key, value);
    }

    uriObj.search = params.toString();

    return uriObj.toString();
}

function selezionaHandler(urlTutto, urlSingolo) {
    $('#seleziona_tutto').click(function () {
        const checked = $(this).is(':checked');
        var arrid = [];
        $('.seleziona').each(function () {
            $(this).prop('checked', checked)
            arrid.push($(this).val());
        });
        $.ajax({
            url: urlTutto,
            type: 'post',
            dataType: 'json',
            method: 'POST',
            data: {
                ids: arrid,
                checked: checked
            },
            success: function (resp) {

            }
        });
    });
    $('.seleziona').click(function () {
        const url = urlSingolo;
        const id = $(this).val();
        const checked = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: updateQueryStringParameter(updateQueryStringParameter(url, 'id', id), 'checked', checked),
            type: 'post',
            dataType: 'json',
            method: 'POST',
            data: {},
            success: function (resp) {
                $('#label_' + resp.id).html(resp.label);
                console.log(resp);
            }
        });
    });
}

function disableSubmitButton() {

    const submitButton = document.getElementById('submit');
    if (submitButton.disabled) {
        return false; // Prevenire il submit se il pulsante è già disabilitato
    }
    submitButton.disabled = true;
    return true; // Permettere il submit del form
}

function reInitKtButtons() {
    [].slice.call(document.querySelectorAll('[data-kt-buttons="true"]')).map((function (e) {
        if ("1" !== e.getAttribute("data-kt-initialized")) {
            var t = e.hasAttribute("data-kt-buttons-target") ? e.getAttribute("data-kt-buttons-target") : ".btn",
                n = [].slice.call(e.querySelectorAll(t));
            KTUtil.on(e, t, "click", (function (e) {
                n.map((function (e) {
                    e.classList.remove("active")
                })), this.classList.add("active")
            })), e.setAttribute("data-kt-initialized", "1")
        }
    }));
}

function reinitializeMenus() {
    console.log('reinitializeMenus');
    // Reinizializza tutti i menu KT
    KTMenu.createInstances();

    // Oppure specificamente per gli elementi aggiornati
    document.querySelectorAll('[data-kt-menu-trigger]').forEach(function (element) {
        KTMenu.getInstance(element)?.destroy();
        new KTMenu(element);
    });
}

(function ($) {
    //
    // $('#element').donetyping(callback[, timeout=1000])
    // Fires callback when a user has finished typing. This is determined by the time elapsed
    // since the last keystroke and timeout parameter or the blur event--whichever comes first.
    //   callback: function to be called when even triggers
    //   timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
    //              caused by blur.
    // Requires jQuery 1.7+
    // https://stackoverflow.com/questions/14042193/how-to-trigger-an-event-in-input-text-after-i-stop-typing-writing

    $.fn.extend({
        donetyping: function (callback, timeout) {
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function (el) {
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function (i, el) {
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you palerdot
                $el.is(':input') && $el.on('keyup keypress paste', function (e) {
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too preemptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type == 'keyup' && e.keyCode != 8) return;

                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function () {
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur', function () {
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})
(jQuery);
