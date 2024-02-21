$(document).ready(function() {
    $('#assign_subject').select2({
        width: 'resolve',
        placeholder: "Select a subject",
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: '/admin/functions/get-subjects',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.subject_id,
                            text: item.subject_name 
                        };
                    })
                };
            },
            cache: true
        }
    });
});
