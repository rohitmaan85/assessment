$(document).ready(function() {
    var sscValue = "";
    var subId = "";
    var qpCode = "";
    var subjectName = "";

    // Populate SSC Dropdown

    loadSSCList();

    function loadSSCList() {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: {
                get: "ssc"
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                $.each(json, function(i, option) {
                    var rendered_option = '<li><a href="#">' + option + '</a></li>';
                    $(rendered_option).appendTo('#ssctest-dropdown-menu');
                });
            }
        });
    }


    $("#sscButton").click(function() {
        var selText = $(this).text();
        loadJobRoles("");
    });


    $('#ssctest-dropdown-menu').on('click', 'li a', function() {
        sscValue = $(this).text();
        var selText = $(this).text();
        $('#jobroletest-dropdown-menu').children().remove();
        $('#selGroupDropdown').children().remove();
        $('#jobroledropdownButton').html("-Select JobRole-" + '<span class="caret"></span>');
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
        loadJobRoles(selText);
    });


    function loadJobRoles(sscValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: {
                get: "jobroleWithQPCode",
                ssc: sscValue
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                var counter = 0;
                $.each(json.job_role, function(i, option) {
                    var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + '">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                    $(rendered_option).appendTo('#jobroletest-dropdown-menu');
                    counter++;
                });
            }
        });
    }


    // Click on Jobrole DropDown Item.
    $('#jobroletest-dropdown-menu').on('click', 'li a', function() {
        subId = $(this).attr('id');
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        //  $('#createExamForm').removeClass('hide')
    });

    $('#jobroletest-dropdown-menu').on('click', 'li a', function() {
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        showExams($(this).attr("id"), "");
    });


    var examsTable = "";
    showExams("", "");

    function showExams(subjectId, batchId) {
        examsTable = $('#exams').DataTable({
            "oLanguage": {
                "sEmptyTable": '<strong>No Exams Available for this Subject \\ Batch  !</strong>'
            },
            // serverSide: true,
            initComplete: function() {
                examsTable.buttons().container()
                    .appendTo('#exams_wrapper .col-sm-6:eq(0)');
            },

            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            "ajax": {
                'type': 'POST',
                'url': '/assessment/php/manageExams.php',
                'data': {
                    get: 'exams',
                    subid: subjectId
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                        //  $('#examTable').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Exams Found for selected Subject in Database.</strong></div>');
                        json.data = [];
                    } else {
                        $('#error_msg').addClass('hide');
                    }

                    return json.data;
                },
            },
            'columnDefs': [{
                "targets": 1,
            }, {
                "targets": 2,
                "searchable": false,
                "data": null,
                "width": "60%",
                "defaultContent": " <button id=\"editExam\"  type=\"button\" class=\"btn btn-warning btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Edit</button>  <button id=\"manQstsButton\"  type=\"button\" class=\"btn btn-success btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Manage Questions</button> <button id=\"showExamDetails\"  type=\"button\" class=\"btn btn-info btn-sm\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Show Details</button>  <button id=\"showQuestions\"  type=\"button\" class=\"btn btn-warning btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Show Questions</button>"
            }, ],
            "destroy": true,
        });
    }


    // Show Questions on click on shoq qustions button.
    $('#exams tbody').on('click', '#showQuestions', function() {
        //console.log(testTable.row($(this).parents('tr')).data());
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        window.open("examQuestions.php?examname=" + data[0]);
    });

    var exam_name = "";

    $('#exams tbody').on('click', '#showExamDetails', function() {
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        exam_name = data[0];
        getExamInformationForDialog();
        $('#displayExamDetailsModal').modal('show');
    });

    $('#exams tbody').on('click', '#manQstsButton', function() {
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        exam_name = data[0];
        window.open("manageExamQuestions.php?action=manageExamQstn&examName=" + exam_name);
    });




    function getExamInformationForDialog() {
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageExams.php',
            data: {
                get: "examDetailsForInfo",
                exam_name: exam_name
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                    $.each(json, function(i, option) {
                        $('#name_text').val(option[0][0]);
                        $('#batch_text').val(option[0][1]);
                        $('#no_of_qstns_text').val(option[0][2]);
                        $('#duration_text').val(option[0][3] + " Minutes");
                        $('#exam_from_text').val(option[0][4]);
                        $('#exam_to_text').val(option[0][5]);
                        $('#total_marks_text').val(option[0][6]);
                        $('#pass_percent_text').val(option[0][7]);
                    });
                }
                // control.replaceWith(control.val('').clone(true));
        });
    }



});
