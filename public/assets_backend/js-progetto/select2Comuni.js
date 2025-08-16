function select2UniversaleUnita(idSenzaCancelletto, testo, minimumInputLength, select2) {
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
                var impiantoId = $('#impianto_id').val();
                return {
                    term: term.term,
                    impianto_id: impiantoId // Passa l'impianto_id come parametro
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
