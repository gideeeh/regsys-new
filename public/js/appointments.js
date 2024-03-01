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

    $('#request-service').select2({
        width: 'resolve',
        placeholder: 'Select Service',
        allowClear: true,
        minimumInputLength: 0,
        ajax: {
            url: servicesUrl,
            dataType: 'json',
            delay: 20,
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.service_name
                        };
                    })
                };
            },
            cache: true
        }
    }).on("select2:select", function(e) {
        let serviceData = e.params.data.id;
        $("#request-service").val(serviceData);
        console.log(`${serviceData}`);
    });

    $('#hello').on('click', function() {
        alert("Hello");
    });
});