$(document).ready(function() {



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


     // check if form is editable
     var id= "";
     var subId= "";
     var qstnId= "";

     isEditable() ;
     function isEditable(){
       // For testing
         // $('#isEditable').val("yes");
         /*
         $('#subId').val("AGR/Q4804");
         $('#qstnId').val("7316_AGR/Q4804");
         */
       // Testing  End
      if($('#isEditable').val()=="yes")
        {
          $('#heading').html("Edit Question");
           id= ($('#id').val());
           subId= ($('#subId').val());
           qstnId= ($('#qstnId').val());
          enableEditMode(id,subId,qstnId);
          $('#newModeButton').addClass('hide');
          $('#editModeButton').removeClass('hide');
         }
       else {
           $('#heading').html("Create New Question");
            $('#newModeButton').removeClass('hide');
            $('#editModeButton').addClass('hide');
          }
     }

  function enableEditMode(id,subId,qstnId){
       // Get Data from Database using subId and qstnId
       $.ajax({
           url: '/assessment/php/manageQuestions.php',
           data: { action :"edit" ,subId: subId, qstnId : qstnId },
           dataType: 'json', //since you wait for json
           success: function(data) {
               if (typeof data.error === 'undefined') {
                   // Success so call function to process the form
                   console.log('SUCCESS: ' + data.question);
                    // populate English Form
                   $("#questionInputTextArea").val(data.question);
                   $("#optionATextAreaInput").val(data.optiona);
                   $("#optionBTextAreaInput").val(data.optionb);
                   $("#optionCTextAreaInput").val(data.optionc);
                   $("#optionDTextAreaInput").val(data.optiond);
                   // populate hindi Form
                   $("#hindiTextQstn").val(data.question);
                   $("#hindiTextOptionA").val(data.optiona);
                   $("#hindiTextOptionB").val(data.optionb);
                   $("#hindiTextOptionC").val(data.optionc);
                   $("#hindiTextOptionD").val(data.optiond);
                   // populate correct and marks option
                   $("#corrOption").val(data.correctanswer);
                   $("#marksText").val(data.marks);
                   // Set language radio button
                   if(data.language=="hindi"){
                      $("input[name=radioGroup][value=" + 1 + "]").click();
                    }
                  // populate number of options option dropdown
                   $('#noOfOptions').val(data.noOfOption);
                   // show Number of Options divs
                   showNoOfOptions(data.noOfOption);
                   // populate correct option dropdown
                   setCorrectAnswerDropdown(data.correctanswer);


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

function setCorrectAnswerDropdown(correctanswer){

  if (correctanswer == "optiona")
      correctOptDropValue = "Option A";
  if (correctanswer == "optionb")
      correctOptDropValue = "Option B";
  if (correctanswer == "optionc")
      correctOptDropValue = "Option C";
  if (correctanswer == "optiond")
      correctOptDropValue = "Option D";
    $("#corrOption").val(correctOptDropValue);
}

function showHindiOptions(){
    $("#hindiTextArea").removeClass('hide');
    $("#hindioptionATextAreaInput").removeClass('hide');
    $("#hindioptionBTextAreaInput").removeClass('hide');
    $("#hindioptionCTextAreaInput").removeClass('hide');
    $("#hindioptionDTextAreaInput").removeClass('hide');

     $("#questionInputTextArea").addClass('hide');
     $("#optionATextAreaInput").addClass('hide');
     $("#optionBTextAreaInput").addClass('hide');
     $("#optionCTextAreaInput").addClass('hide');
     $("#optionDTextAreaInput").addClass('hide');
}

function showEnglishOptions(){
      $("#questionInputTextArea").removeClass('hide');
      $("#optionATextAreaInput").removeClass('hide');
      $("#optionBTextAreaInput").removeClass('hide');
      $("#optionCTextAreaInput").removeClass('hide');
      $("#optionDTextAreaInput").removeClass('hide');

     $("#hindiTextArea").addClass('hide');
     $("#hindioptionATextAreaInput").addClass('hide');
     $("#hindioptionBTextAreaInput").addClass('hide');
     $("#hindioptionCTextAreaInput").addClass('hide');
     $("#hindioptionDTextAreaInput").addClass('hide');
}


 var isHindi = false;
  $('input[type="radio"]').click(function(){
           if($(this).attr("value")=="1"){
               isHindi  =  true;
               showHindiOptions();
           } else{
               showEnglishOptions();
         }
       });



    //$('#createExamForm').BootstrapValidator();
  $("#createQstnButton").click(function() {
       var qpCode = $("#name").val();
       var question = "";
       var optiona =  "";
       var optionb =  "";
       var optionc =  "";
       var optiond =  "";
       var lang =  "";
       //  var lang =  $('#selectLang input:radio:checked').val();
        if(isHindi){
           lang = "hindi";
           question = $("#hindiTextQstn").val();
           optiona = $("#hindiTextOptionA").val();
           optionb = $("#hindiTextOptionB").val();
           optionc = $("#hindiTextOptionC").val();
           optiond = $("#hindiTextOptionD").val();
        }else{
           lang = "eng";
           question = $("#questionInputTextArea").val();
           optiona = $("#optionATextAreaInput").val();
           optionb = $("#optionBTextAreaInput").val();
           optionc = $("#optionCTextAreaInput").val();
           optiond = $("#optionDTextAreaInput").val();
        }

        var correctanswer = $("#corrOption").val();
        if (correctanswer == "Option A")
            correctanswer = "optiona";
        if (correctanswer == "Option B")
            correctanswer = "optionb";
        if (correctanswer == "Option C")
            correctanswer = "optionc";
        if (correctanswer == "Option D")
            correctanswer = "optiond";

        var marks = $("#marksText").val();
        var noOfOptions = $('#noOfOptions').find("option:selected").text();

        // alert('test');
        $.ajax({
            url: '/assessment/php/manageQuestions.php',
            data: {action:"create",qpcode: "AGR/Q4804", qstn: question, opta: optiona, optb: optionb, optc: optionc, optd: optiond, corrans: correctanswer, mark: marks,language:lang,noOfOptions:noOfOptions },
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
    });


function getCorrectAnswerValue(){
  var correctanswer = $("#corrOption").val();
  if (correctanswer == "Option A")
      correctanswer = "optiona";
  if (correctanswer == "Option B")
      correctanswer = "optionb";
  if (correctanswer == "Option C")
      correctanswer = "optionc";
  if (correctanswer == "Option D")
      correctanswer = "optiond";
      return correctanswer;
}

$("#saveQstnButton").click(function() {
     saveQuestion();
});

function saveQuestion(){

  var qpCode = $("#name").val();
  var question = "";
  var optiona =  "";
  var optionb =  "";
  var optionc =  "";
  var optiond =  "";
  var lang =  "";
  //  var lang =  $('#selectLang input:radio:checked').val();
   if(isHindi){
      lang = "hindi";
      question = $("#hindiTextQstn").val();
      optiona = $("#hindiTextOptionA").val();
      optionb = $("#hindiTextOptionB").val();
      optionc = $("#hindiTextOptionC").val();
      optiond = $("#hindiTextOptionD").val();
   }else{
      lang = "eng";
      question = $("#questionInputTextArea").val();
      optiona = $("#optionATextAreaInput").val();
      optionb = $("#optionBTextAreaInput").val();
      optionc = $("#optionCTextAreaInput").val();
      optiond = $("#optionDTextAreaInput").val();
   }
   var correctanswer = getCorrectAnswerValue();
   var marks = $("#marksText").val();
   var noOfOptions = $('#noOfOptions').find("option:selected").text();
   $.ajax({
       url: '/assessment/php/manageQuestions.php',
       data: { action :"update" ,id:id,subId: subId, qstnId : qstnId, qstn: question, opta: optiona, optb: optionb, optc: optionc, optd: optiond, corrans: correctanswer, mark: marks,language:lang,noOfOptions:noOfOptions },
       dataType: 'json', //since you wait for json
       success: function(data) {
           if (typeof data.error === 'undefined') {
               // Success so call function to process the form
               console.log('SUCCESS: ' + data.success);
               $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
               $('#error_msg').addClass('in');
               $('#error_msg strong').text("Success! " + data.success);
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

function showNoOfOptions(noOfOptions){
  if (noOfOptions == 1) {
      $("#optionBDiv").addClass('hide');
      $("#optionCDiv").addClass('hide');
      $("#optionDDiv").addClass('hide');
  }
  if (noOfOptions == 2) {
      $("#optionADiv").removeClass('hide');
      $("#optionBDiv").removeClass('hide');
      $("#optionCDiv").addClass('hide');
      $("#optionDDiv").addClass('hide');
  }
  if (noOfOptions == 3) {
      $("#optionADiv").removeClass('hide');
      $("#optionBDiv").removeClass('hide');
      $("#optionCDiv").removeClass('hide');
      $("#optionDDiv").addClass('hide');
  }
  if (noOfOptions == 4) {
      $("#optionADiv").removeClass('hide');
      $("#optionBDiv").removeClass('hide');
      $("#optionCDiv").removeClass('hide');
      $("#optionDDiv").removeClass('hide');
  }
}

    $('#noOfOptions').change(function() {
        //var noOfOptions = $(this).text();
        var noOfOptions = $(this).find("option:selected").text();
        if (noOfOptions == 1) {
            $("#optionBDiv").addClass('hide');
            $("#optionCDiv").addClass('hide');
            $("#optionDDiv").addClass('hide');
        }
        if (noOfOptions == 2) {
            $("#optionADiv").removeClass('hide');
            $("#optionBDiv").removeClass('hide');
            $("#optionCDiv").addClass('hide');
            $("#optionDDiv").addClass('hide');
        }
        if (noOfOptions == 3) {
            $("#optionADiv").removeClass('hide');
            $("#optionBDiv").removeClass('hide');
            $("#optionCDiv").removeClass('hide');
            $("#optionDDiv").addClass('hide');
        }
        if (noOfOptions == 4) {
            $("#optionADiv").removeClass('hide');
            $("#optionBDiv").removeClass('hide');
            $("#optionCDiv").removeClass('hide');
            $("#optionDDiv").removeClass('hide');
        }
    });

});
