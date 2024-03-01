$(document).ready(function() {
    $('#stu-appointment').select2({
        width: 'resolve',
        placeholder: "Appointment ID or Student Name",
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: getStudentsUrl,
            dataType: 'json',
            delay: 20,
            processResults: function (data) {
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.student_id,
                            text: item.student_number + ' - ' + item.first_name + ' ' + item.last_name
                        };
                    })
                };
            },
            cache: true
        }
    });
});