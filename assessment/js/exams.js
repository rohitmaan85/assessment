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
                  $(rendered_option).appendTo('#ssc-dropdown-menu');
              });
          }
      });
  }

    $('#ssc-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
        // Flush dropdown
        $('#jobrole-dropdown-menu').children().remove();
        // ReSet button value
        $('#jobroledropdownButton').html("-Select JobRole-" + '<span class="caret"></span>');
        //console.log($(this).text());
        loadJobRoles($(this).text());
    });


function loadJobRoles() {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: {
                get: "jobroleWithQPCode",
                ssc: sscValue
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                // Clear dropdown
                $('#jobrole-dropdown-menu').children().remove();
                //now when you received json, render options
                var counter = 0;
                $.each(json.job_role, function(i, option) {
                    var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + '">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                    $(rendered_option).appendTo('#jobrole-dropdown-menu');
                    counter++;
                });
            }
        });
    }

    $('#jobrole-dropdown-menu').on('click', 'li a', function() {
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        showExams($(this).attr("id"),"");
    });


    var examsTable = "";
    showExams("","");
    function showExams(subjectId,batchId) {
        examsTable = $('#exams').DataTable({
            "oLanguage": {
                "sEmptyTable": '<strong>No Exams Available for this Subject \\ Batch  !</strong>'
            },
            // serverSide: true,
            initComplete : function () {
             examsTable.buttons().container()
                               .appendTo( '#exams_wrapper .col-sm-6:eq(0)' );
           },

            buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
            "ajax": {
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
                "bVisible": false,
                "searchable": false
            }, {
                "targets": 8,
                "data": null,
                "defaultContent": "<button id=\"manQstsButton\"  type=\"button\" class=\"btn btn-success btn-xs\" ><span class=\"glyphicon glyphicon-edit\"></span>Manage Questions</button> <button id=\"openQuestion\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Edit</button>  <button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-warning btn-xs\" ><span class=\"glyphicon glyphicon-edit\"></span>Show Questions</button>"
            }, ],
            "destroy": true,
        });
    }


    // Show Questions on click on shoq qustions button.
    $('#exams tbody').on('click', 'button', function() {
        //console.log(testTable.row($(this).parents('tr')).data());
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        window.open("examQuestions.php?examname="+ data[0]);
    });



});
