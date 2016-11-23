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
            showExams();
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
            showExams();
            //questionsTable.ajax.reload();
          //  questionsTable.ajax.url( '/assessment/php/manageQuestions.php?get=questionssubId='+ "test1" ).load();
        }
    });
}

showExams();
function showExams(){
    var subjectId = $("#qpcodeText").val();
    var examTable = $('#examTable').DataTable({
    "ajax": {
       'url' : '/assessment/php/manageExams.php',
       'data': {
                get: 'exams',
                subid:subjectId
               },
       'dataSrc': function (json) {
                    if(!json.data){
                              $('#examTable').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Exams Found for selected Subject in Database.</strong></div>');
                              json.data = [];
                          }
                          else{
                                $('#error_msg').addClass('hide');
                          }

                    return json.data;
          },
      },
    'columnDefs': [
        {
           "targets": 1,
           "bVisible": false,
           "searchable": false
         },
        {
            "targets":6,
            "data": null,
            "defaultContent": "<button id=\"manQstsButton\"  type=\"button\" class=\"btn btn-success btn-xs\" ><span class=\"glyphicon glyphicon-remove\"></span>Manage Questions</button> <button id=\"openQuestion\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Edit</button>  <button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-danger btn-xs\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>"
          },
        ],
    "destroy" : true,
  });
}

});
