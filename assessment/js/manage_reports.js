$(document).ready(function() {
    var sscValue = "";
    var subId = "";
    var qpCode = "";
    var subjectName = "";

    // Populate SSC Dropdown


    Morris.Donut({
         element: 'morris-donut-chart',
         data: [{
             label: "Exam Created",
             value: 12
         }, {
             label: "Upcoming Exams",
             value: 10
         }, {
             label: "Exams Conducted",
             value: 2
         }, {
             label: "Exam deleted",
             value: 0
         },
       ],
         backgroundColor: '#ccc',
        labelColor: '#060',
        colors: [
          'green',
          'blue',
          '#67C69D',
          'red'
        ],
         resize: true
     });

     Morris.Donut({
          element: 'morris-donut-chart-2',
          data: [{
              label: "Batch uploaded",
              value: 12
          }, {
              label: "Batch covered in Exam",
              value: 10
          }, {
              label: "Batch not included in Exam",
              value: 2
          },
          {
              label: "Batch deleted without Exam",
              value: 2
          }],
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: [
            '#0BA462',
            '#39B580',
            '#67C69D',
            '#95D7BB'
          ],
          resize: true
      });



      Morris.Donut({
           element: 'morris-donut-chart-3',
           data: [{
               label: "Total number of students enrolled",
               value: 12
           }, {
               label: "Number of students Passed",
               value: 10
           }, {
               label: "Number of student failed ",
               value: 2
           },
           {
               label: "Number of students without Exam.",
               value: 2
           }],
           resize: true
       });

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
        subId=$(this).attr("id");
        showExams();
    });


    var examsTable = "";
    showExams("");

    function showExams() {
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
                    subid: subId
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
                "width": "55%",
              "defaultContent": " <button id=\"showExamDetails\"  type=\"button\" class=\"btn btn-info btn-sm\" >Show Exam Details</button>   <button id=\"editExamButton\"  type=\"button\" class=\"btn btn-primary btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Edit</button>  <button id=\"manQstsButton\"  type=\"button\" class=\"btn btn-success btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Manage Questions</button>  <button id=\"showQuestions\"  type=\"button\" class=\"btn btn-warning btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Show Questions</button> <button id=\"deleteExamButton\"  type=\"button\" class=\"btn btn-danger btn-sm\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>  "
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

    $('#exams tbody').on('click', '#deleteExamButton', function() {

        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        exam_name = data[0];
        $('#confirm').modal({
                backdrop: 'static',
                keyboard: false
            })
            .one('click', '#delete', function() {
                deleteExam(exam_name);
            });

    });


    function deleteExam(exam_name) {
        $.ajax({
           type: 'POST',
            url: '/assessment/php/manageExams.php',
            data: {
                action: "delete",
                examName: exam_name
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                $('#successMessageText').text(json.message);
                $('#successMessage').modal('show');
                showExams();
                // Set a timeout to hide the element again
                setTimeout(function(){
                      $("#successMessage").modal('hide');
                }, 3000);
            },
            error: function(data) {
                // Handle errors here
                console.log('ERRORS: ' + data);
                $('#errorModal').modal('show');
                    // Set a timeout to hide the element again
                setTimeout(function(){
                      $("#errorModal").modal('hide');
                }, 3000);
                // STOP LOADING SPINNER
            }
        });
    }

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
