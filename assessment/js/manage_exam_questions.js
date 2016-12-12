$(document).ready(function() {

    var examName = $('#examName').val();
    var sscValue = "";
    var job_role = "";
    var category = "";
    var module = "";
    var quesstionsTable = "";

    showQuestions("", "", "", "");

    $("td.myrow").mouseenter(function() {
        $(this).attr("Question", $(this).html());
    });

    getExamDetails();

    function getExamDetails() {
        //examName = "rohit_1";
        console.warn("get Exam details of exam :" + examName);
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageExams.php',
            data: {
                action: "getExamInfo",
                exam_name: examName,
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                    $.each(json, function(i, option) {
                        $('#examNameText').val(option[0][0]);
                        $('#noOfQstnsText').val(option[0][1]);
                        $('#batchText').val(option[0][2]);
                        $('#examDateText').val(option[0][3]);
                        $('#durationText').val(option[0][4]);
                        $('#tmText').val(option[0][5]);
                        $('#validFromText').val(option[0][6]);
                        $('#validToText').val(option[0][7]);
                        $('#JobroleText').val(option[0][8]);
                        $('#sscText').val(option[0][9]);
                    });
                }
                // control.replaceWith(control.val('').clone(true));
        });
    }


    function showQuestions(exam_id) {
        exam_id = 3;
        quesstionsTable = $('#qstns').DataTable({
            "oLanguage": {
                "sEmptyTable": '<strong>No Questions Available for this Exam !</strong>'
            },
            // serverSide: true,
            initComplete: function() {
                quesstionsTable.buttons().container()
                    .appendTo('#qstns_wrapper .col-sm-6:eq(0)');
            },

            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            "ajax": {
                'type': 'POST',
                'url': '/assessment/php/manageExams.php',
                'data': {
                    action: 'getExamQstns',
                    examName: examName,
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                        //  $('#qstns').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Questions Found for selected Subject in Database.</strong></div>');
                        json.data = [];
                    } else {
                        $('#error_msg').addClass('hide');
                    }
                    return json.data;
                },
            },
            /* "scrollY": "200px",
            "paging": false, */
            'columnDefs': [
                 {
                    "targets": [ 3 ],
                    "visible": false
                  },
                  {
                    "targets": 8,
                    "data": null,
                    "defaultContent": "<button id=\"openQuestion\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span> Edit Qstn</button>"
                },

            ],
            "destroy": true,
        });
    }

    $('#qstns tbody')
        .on('click', 'td', function() {
            var colIdx = quesstionsTable.cell(this).index().column;
           if(colIdx==2 || colIdx==3 || colIdx==4 || colIdx==5 || colIdx==6){
              var data = $('#qstns').DataTable().row($(this).parents('tr')).data();
              //alert(data);
              $('#qstnCompleteVal').val(data[colIdx]);
              $('#displayQstnModal').modal('show');
            }
        });

    $('#qstns tbody').on('click', 'button', function() {
        //console.log(testTable.row($(this).parents('tr')).data());
        var data = $('#qstns').DataTable().row($(this).parents('tr')).data();
        window.open("createQuestionPage.php?action=edit&qstnid=" + data[10]);
    });


});
