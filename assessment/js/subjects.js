$(document).ready(function() {

    var subjectId="";
    $('#qpcodeText').prop('disabled', true);

    $("#sscdropdownButton").click(function() {
        $.ajax({
                url: '/assessment/php/getSubjectDetails.php',
                data: { get: "ssc" },
                dataType: 'json', //since you wait for json
                success: function(json) {
                    // Clear dropdown
                    $('#ssc-dropdown-menu').children().remove();
                    //now when you received json, render options
                    $.each(json, function(i, option) {
                        var rendered_option = '<li><a href="#">' + option + '</a></li>';
                        $(rendered_option).appendTo('#ssc-dropdown-menu');
                    });
                }
            });
            // control.replaceWith(control.val('').clone(true));
    });


    $('#ssc-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
        //console.log($(this).text());
        loadJobRoles($(this).text());
    });

    function loadJobRoles(sscValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: { get: "jobrole", ssc: sscValue },
            dataType: 'json', //since you wait for json
            success: function(json) {
                showQuestions();
                // Clear dropdown
                $('#jobrole-dropdown-menu').children().remove();
                //now when you received json, render options
                $.each(json, function(i, option) {
                    var rendered_option = '<li><a href="#">' + option + '</a></li>';
                    $(rendered_option).appendTo('#jobrole-dropdown-menu');
                });
            }
        });
    }

    $('#jobrole-dropdown-menu').on('click', 'li a', function() {
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        //console.log($(this).text());
        loadQpCode($(this).text());
    });

    function loadQpCode(jobroleValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: { get: "qpcode", jobrole: jobroleValue },
            dataType: 'json', //since you wait for json
            success: function(json) {
                // Clear dropdown
                //$('#qpcodeText').remove();
                $('#qpcodeText').val(json.qpcode);
                subjectId=json.qpcode;
                showQuestions();
                //questionsTable.ajax.reload();
              //  questionsTable.ajax.url( '/assessment/php/manageQuestions.php?get=questionssubId='+ "test1" ).load();
            }
        });
    }


showQuestions();
function showQuestions(){
    var subjectId = $("#qpcodeText").val();
    var quesstionsTable = $('#qstns').DataTable({
        "ajax": {
          'url' : '/assessment/php/manageQuestions.php',
          'data': {
                  get: 'questions',
                  subId: $("#qpcodeText").val()
                },
         'dataSrc': function (json) {
                        if(!json.data){
                                  $('#qstns').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Questions Found for selected Subject in Database.</strong></div>');
                                  json.data = [];
                              }
                              else{
                                    $('#error_msg').addClass('hide');
                              }
                        return json.data;
                  },

        },

        'columnDefs': [{
                    "targets": 7,
                    "data": null,
                    "defaultContent": "<button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Edit</button>  <button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-danger btn-xs\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>"
                }],
        "destroy" : true,
      });
  }



    $('#test tbody').on('click', 'button', function() {
        console.log(testTable.row($(this).parents('tr')).data());
        var data = testTable.row($(this).parents('tr')).data();
        alert(data[0] + "'s salary is: " + data[5]);
    });


});
