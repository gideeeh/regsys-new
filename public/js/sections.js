$(document).ready(function() {

    $('#filter-confirm-button').on('click', function() {
        fetchAndUpdateSections();
        updateSectionScheduleInfo();
    });

    /* Used to update info from filter modal */
    function updateSectionScheduleInfo() {
        var acadYear = $('#filter_acad_year').val();
        var term = $('#filter_term').val();
        var programText = $('#filter_program').find(":selected").text();
        var yearLevel = $('#filter_year_level').val();

        $('#display_acad_year').text(acadYear);
        $('#display_term').text(term);
        $('#display_program').text(programText === 'Select All' ? "All Programs" : programText);
        $('#display_year_level').text(yearLevel);
    }

    function fetchProgramSubjects() {
        $.ajax({
            url: programSubjectsUrl,
            type: 'GET',
            data: {
                acad_year: $('#filter_acad_year').val(),
                term: $('#filter_term').val(),
                program: $('#filter_program').val(),
                year_level: $('#filter_year_level').val(),
            },
            success: function(response) {
                updateTableWithProgramSubjects(response);
            },
            error: function(error) {
                console.error("Error fetching program subjects:", error);
            }
        });
    }

    function updateTableWithProgramSubjects(data) {
        var tbody = $('table > tbody');
        console.log(data);
        tbody.empty(); 
    
        data.forEach(function(subject) {
            var row = $('<tr></tr>');
            // row.append($('<td></td>').text(subject.category));
            row.append($('<td class="border font-semibold border-gray-300"></td>').text(subject.course_code));
            row.append($('<td class="border border-gray-300"></td>').text(subject.course_name));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($('<td class="border border-gray-300"></td>'));
            row.append($("<td><div class='flex justify-start'><button @click='manageSchedule=true' class='manageButton bg-green-500 w-full text-white text-xs px-1 py-1 rounded hover:bg-green-600 transition ease-in-out duration-150'>Manage</button></div></td>"));
            tbody.append(row);
        });
    }

    let manageSchedule = false;

    // jQuery click event handler for the button
    $('body').on('click', '.manageButton', function() {
        console.log('managebuttonclicked')
        $('#manageSchedule').show();
        // manageSchedule = !manageSchedule; 
        // showOrHideManageSchedule(); 
    });
    // Function to show or hide the manageSchedule div based on the value of manageSchedule
    function showOrHideManageSchedule() {
        if (manageSchedule) {
            $('#manageSchedule').show(); // Show the div
        } else {
            $('#manageSchedule').hide(); // Hide the div
        }
    }

    function fetchAndUpdateSections() {
        $.ajax({
            url: sectionsUrl, 
            type: 'GET',
            data: {
                acad_year: $('#filter_acad_year').val(),
                term: $('#filter_term').val(),
                program: $('#filter_program').val(),
                year_level: $('#filter_year_level').val(),
            },
            success: function(data) {
                updateSectionButtons(data.sections);
                selectSection(data.sections[0].section_id); 
                fetchProgramSubjects();
            },
            error: function(xhr, status, error) {
                updateSectionButtonsError();
                console.error("Error fetching sections:", error);
            }
        });
    }

    function updateSectionButtonsError() {
        var container = $('#display-sections');
        container.empty(); 
        var notice = $('<div/>', {
            text: 'No sections have been created for the selected academic year, term, and program.',
            class: 'text-red-500' 
        });
        container.append(notice);
    }

    function updateSectionButtons(sections) {
        var container = $('#display-sections');
        container.empty(); 
        sections.forEach(function(section) {
            var button = $('<button/>', {
                text: section.section_name,
                id: 'section-' + section.section_id,
                class: 'bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150',
                click: function() { 
                    selectSection(section.section_id); 
                    console.log(`Year level: ${section.year_level}`);
                    console.log(`Section Id: ${section.section_id}`);
                    console.log(`Section name: ${section.section_name}`);
                }
            });
            container.append(button);
        });
    }

    $('#display-sections').on('click', 'button[id^="section-"]', function() {
        var sectionId = this.id.replace('section-', ''); 
        selectSection(sectionId);
    });

    function selectSection(sectionId) {
        $('button[id^="section-"]').attr('class', 'bg-gray-500 text-white px-2 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150');
        $('#section-' + sectionId).attr('class', 'bg-rose-500 text-white text-md px-2 py-2 rounded hover:bg-rose-600 transition ease-in-out duration-150');
    }

    // $('#filter_acad_year, #filter_term, #filter_program, #filter_year').on('change', function() {
    //     fetchAndUpdateSections();
    // });

    updateSectionScheduleInfo();
    fetchAndUpdateSections();
});