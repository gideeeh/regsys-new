$(document).ready(function() {
    let $enrollSubjects = $('#enroll_subjects');
    let selectedSubjects = [];

    function logCurrentInputs() {
        var inputs = $('#enrollmentForm').find('input, select').serializeArray();
        console.log("Current Form Inputs:", inputs);
        console.log("Selected Subjects:", selectedSubjects);
    }

    $('.log-data').on('click', function() {
        updateSelectedSubjects();
        logCurrentInputs();
    });
    $('#enroll_subjects').select2({
        width: 'resolve',
        placeholder: "Select Subject to Enroll",
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: getSubjectsUrl,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.subject_id,
                            text: item.subject_code + ' - ' + item.subject_name,
                            subject_code: item.subject_code,
                            subject_name: item.subject_name,
                            units_lec: item.units_lec,
                            units_lab: item.units_lab,
                            total_units: item.units_lec + item.units_lab
                        };
                    })
                };
            },
            cache: true
        }
    }).on("select2:select", function(e) {
        let subjectDetails = e.params.data;
        selectedSubjects.push({
            subject_id: subjectDetails.id,
        });

        let newRow = `<tr data-subject-id="${subjectDetails.id}">
            <td class="border border-gray-300 text-center""><strong>${subjectDetails.subject_code}</strong></td>
            <td class="border border-gray-300 text-center"">${subjectDetails.subject_name}</td>
            <td class="border border-gray-300 text-center""><!-- Section --></td>
            <td class="border border-gray-300 text-center">${subjectDetails.units_lec}</td>
            <td class="border border-gray-300 text-center"">${subjectDetails.units_lab}</td>
            <td class="border border-gray-300 text-center"">${subjectDetails.total_units}</td>
            <td class="border border-gray-300 text-center""><!-- Day --></td>
            <td class="border border-gray-300 text-center""><!-- Time --></td>
            <td class="border border-gray-300">
                <button type="button" class="remove-subject bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition ease-in-out duration-150">Remove</button>
            </td>
        </tr>`;

        $("table tbody").prepend(newRow);

        $enrollSubjects.val(null).trigger('change');
        updateTotalUnits();
    });

    $('table').on('click', '.remove-subject', function() {
        let subjectIdToRemove = $(this).closest('tr').data('subjectId');
        $(this).closest('tr').remove();
        selectedSubjects = selectedSubjects.filter(subject => subject.subject_id != subjectIdToRemove);

        updateTotalUnits();
        updateSelectedSubjects();
    });

    $('.enroll').on('click', function() {
        updateSelectedSubjects();
        var form = document.getElementById('enrollmentForm');
        if (form.checkValidity()) {
            if (selectedSubjects.length > 0) {
                form.submit();
            } else {
                alert("Please select at least one subject to enroll.");
            }
        } else {
            form.reportValidity();
        }
    });

    function updateTotalUnits() {
        let totalLec = 0;
        let totalLab = 0;

        $("table tbody tr").each(function() {
            let lecUnits = parseInt($(this).find("td:nth-child(4)").text()) || 0;
            let labUnits = parseInt($(this).find("td:nth-child(5)").text()) || 0;

            totalLec += lecUnits;
            totalLab += labUnits;
        });

        let totalUnits = totalLec + totalLab;

        $(".total-units-lec").text(`Total Units(Lec): ${totalLec}`);
        $(".total-units-lab").text(`Total Units(Lab): ${totalLab}`);
        $(".total-units").text(`Total Units: ${totalUnits}`);
    }

    function updateSelectedSubjects() {
        $("table tbody tr").each(function() {
            // Assuming the subject ID is stored in the row's data attribute
            let subjectId = $(this).data('subjectId');
            if (subjectId) {
                selectedSubjects.push(subjectId);
            }
        });
        $('#selectedSubjectsInput').val(JSON.stringify(selectedSubjects.map(subject => subject.subject_id)));
    }

    $('#stu_to_enroll').select2({
        width: 'resolve',
        placeholder: "Student Name or Student Number",
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: getStudentsUrl ,
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
    }).on("select2:select", function(e) {
    var studentId = e.params.data.id;
        $.ajax({
            url: `/admin/students/get-students/${studentId}`,
            type: "GET",
            success: function(response) {
                $('#student_id').val(response.student_id);
                $('#first_name').val(response.first_name);
                $('#middle_name').val(response.middle_name);
                $('#last_name').val(response.last_name);
                $('#suffix').val(response.suffix);
                $('#student_number').val(response.student_number);
                $('#studentName').text(response.first_name);
                $('#studentEmail').text(response.personal_email);
                $('#studentMobile').text(response.phone_number);
                // $('#studentDetails').show();
            },
            error: function(error) {
                console.error('Error fetching student details:', error);
            }
        });
    });
    updateTotalUnits();
});
