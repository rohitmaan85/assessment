$(document).ready(function() {

  var subjectName="";
  var qpCode="";
  var examDurationValue="";
  var attemptCountValue="";
  var passingPercentValue="";



  $(function () {
      $('#startDate').datetimepicker();
      $('#endDate').datetimepicker();
  });

  $("#subNameButton").click(function() {
      var selText = $(this).text();
      loadJobRoles("");
  });

  $('#subname-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      subjectName = selText;
      $('#subNameButton').html(selText + '<span class="caret"></span>');
      loadQpCode(selText);
  });

  function loadJobRoles(sscValue) {
      $.ajax({
          url: '/assessment/php/getSubjectDetails.php',
          data: { get: "jobrole", ssc: sscValue },
          dataType: 'json', //since you wait for json
          success: function(json) {
              //showQuestions();
              // Clear dropdown
              $('#subname-dropdown-menu').children().remove();
              //now when you received json, render options
              $.each(json, function(i, option) {
                  var rendered_option = '<li><a href="#">' + option + '</a></li>';
                  $(rendered_option).appendTo('#subname-dropdown-menu');
              });
          }
      });
  }


  function loadQpCode(jobroleValue) {
      $.ajax({
          url: '/assessment/php/getSubjectDetails.php',
          data: { get: "qpcode", jobrole: jobroleValue },
          dataType: 'json', //since you wait for json
          success: function(json) {
              // Clear Text
              //  $('#qpcodeText').remove();
              $('#qpcodeText').val(json.qpcode);
              $('#createExamForm').removeClass('hide');
          }
      });
  }


  $("#examDurButton").click(function() {
      var selText = $(this).text();
      $('#examDurButton').html(selText + '<span class="caret"></span>');
        for (i=5;i<=100;i++){
         var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#examdur-dropdown-menu');
         i=i+4;
      }
  });

  $('#examdur-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      $('#examDurButton').html(selText + ' Minutes <span class="caret"></span>');
      examDurationValue=selText;
  });


  $("#passPercentButton").click(function() {
      var selText = $(this).text();
      $('#passPercentButton').html(selText + '<span class="caret"></span>');
        for (i=30;i<=75;i=i+5){
          var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#passpercent-dropdown-menu');
         //i=i+5;
      }
  });

  $('#passpercent-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      passingPercentValue=selText;
      $('#passPercentButton').html(selText + '    % <span class="caret"></span>');
  });

  $("#atmptCountButton").click(function() {
      var selText = $(this).text();
      $('#atmptCountButton').html(selText + '<span class="caret"></span>');
        for (i=1;i<=5;i++){
         var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#atmptCount-dropdown-menu');
         //i=i+5;
      }
  });

  $('#atmptCount-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      attemptCountValue = selText;
      $('#atmptCountButton').html(selText + ' Attempt<span class="caret"></span>');
  });

  // Create Exam startDate

function checkMandatoryFields(){
  var allFieldsOk = true;
  if($("#examNameText").val()===''){
    alert("Please enter Exam Name !");
    return false;
  }
  if($("#noOfQstnsText").val()===''){
    alert("Please enter Number of Questions!");
    return false;
  }
  if($("#noOfQstnsText").val()===''){
    alert("Please enter Number of Questions!");
    return false;
  }

  if($("#examInstArea").val()===''){
    alert("Please enter Exam Instruction!");
    return false;
  }

  if(examDurationValue === ''){
    alert("Please enter Duration time !");
    return false;
  }
  if( attemptCountValue === ''){
    alert("Please enter Attempt Count !");
    return false;
  }

  var startDate = $("#startDate").find("input").val();
  if(startDate===''){
    alert("Please enter Exam Start Date !");
    return false;
  }

  var endDate = $("#endDate").find("input").val();
  if(endDate===''){
    alert("Please enter Exam End Date !");
    return false;
  }
  if($("#selGroupDropdown").val()==='None Selected'){
    alert("Please enter Exam Batch Id !");
    return false;
  }
  if(passingPercentValue === ''){
    alert("Please enter Passing Percent!");
    return false;
  }

  return true;
}


  $("#createExamButton").click(function() {

       if(checkMandatoryFields()){
       //subDetails
       var qpCode      = $("#qpcodeText").val();
       // examDetails
       var examName   = $("#examNameText").val();
       var noOfQstns  = $("#noOfQstnsText").val();
       var examdesc   = $("#examInstArea").val();
       var startDate  = $("#startDate").find("input").val();
       var endDate    = $("#endDate").find("input").val();
       var decResult  =  $('#decResultRadio input:radio:checked').val();
       var batchId    =  $("#selGroupDropdown").val();
       var negMarking =  $('#negMarkingRadio input:radio:checked').val();
       var randomQstn  =  $('#radomQstnRadio input:radio:checked').val();
       var raf        =  $('#rafRadio input:radio:checked').val();
          // alert('test');
        $.ajax({
            url: '/assessment/php/manageExams.php',
            data: {action:"create",subjectName:subjectName,qpCode:qpCode,exName:examName,noOfQstns:noOfQstns,examDesc:examdesc,examDur:examDurationValue,atmptCount:attemptCountValue,startDate:startDate,endDate:endDate,decResult:decResult,batchId:batchId,negMarking:negMarking,randomQstn:randomQstn,raf:raf,pp:passingPercentValue },
            dataType: 'json', //since you wait for json
            success: function(data) {
                if (typeof data.error === 'undefined') {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                    $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
                    $('#error_msg').addClass('in');
                    $('#error_msg strong').text("Success! " + data.success);
                    /* Get from database using jax request*/
                    //subjectTable.ajax.reload();
                } else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                    $('#error_msg').addClass('in');
                    $('#error_msg strong').text("Error! " + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Error! Invalid response from server" + errorThrown);
            },
        });
      }
    });


});
