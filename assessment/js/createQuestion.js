$(document).ready(function() {

  var qstnId= "";
  var qp_code ="";
  var ssc = "";
  var job_role="";
  var isImage= false;

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


  $('#ssctest-dropdown-menu').on('click', 'li a', function() {
      ssc = $(this).text();
      var selText = $(this).text();
      $('#jobroletest-dropdown-menu').children().remove();
      $('#jobroledropdownButton').html("- Select JobRole -" + '<span class="caret"></span>');
      $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
      loadJobRoles(selText);
  });


  function loadJobRoles(sscValue) {
      $.ajax({
          url: '/assessment/php/getSubjectDetails.php',
          data: {
              get: "jobroleWithQPCode",
              ssc: ssc
          },
          dataType: 'json', //since you wait for json
          success: function(json) {
              //now when you received json, render options
              var counter = 0;
              $.each(json.job_role, function(i, option) {
                  var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + ":"+option +'">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                  $(rendered_option).appendTo('#jobroletest-dropdown-menu');
                  counter++;
              });
          }
      });
  }

  // Click on Jobrole DropDown Item.
  $('#jobroletest-dropdown-menu').on('click', 'li a', function() {
      var res =  $(this).attr('id').split(":");
      qp_code = res[0];
      job_role = res[1];
      $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
    });

   // check if form is editable
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
           //id= ($('#id').val());
           qstnId= ($('#qstnId').val());
            enableEditMode(qstnId);
          $('#newModeButton').addClass('hide');
          $('#editModeButton').removeClass('hide');
         }
       else {
           $('#heading').html("Create New Question");
            $('#newModeButton').removeClass('hide');
            $('#editModeButton').addClass('hide');
          }
     }

  function enableEditMode(qstnId){
       // Get Data from Database using subId and qstnId
       $.ajax({
           url: '/assessment/php/manageQuestions.php',
           data: { action :"edit" , qstnId : qstnId },
           dataType: 'json', //since you wait for json
           success: function(data) {
               if (typeof data.error === 'undefined') {
                   // Success so call function to process the form
                   console.log('SUCCESS: ' + data.question);
                   // Select SSC and job Role dropdown
                   ssc = data.ssc;
                   job_role=data.job_role;
                   qp_code=data.qp_code;

                   $('#sscdropdownButton').html(data.ssc + '<span class="caret"></span>');
                   $('#jobroledropdownButton').html(data.job_role + " (" + data.qp_code + ")" + '<span class="caret"></span>');
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

                   // Set type of Question
                   if(data.type=="normal"){
                      $("input[name=radioGroupType][value=text]").click();
                    }
                    else if(data.type=="image"){
                        $("input[name=radioGroupType][value=image]").click();
                        $('#qstnImageDiv').removeClass('hide');
                          $("#qstnImageDiv").html(
                          '<img src="'+data.image_path+'..." alt="question Image" class="img-responsive center-block" />');
                    }


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
          } else if($(this).attr("value")=="2"){
               isHindi  =  false;
               showEnglishOptions();
           }
           else if($(this).attr("value")=="image"){
                 isImage  =  true;
                 // Show Image upload div
                 $('#uploadImageForm').removeClass('hide');
                  $('#qstnImageDiv').removeClass('hide');
           }
           else if($(this).attr("value")=="text"){
                 isImage  =  false;
                 $('#uploadImageForm').addClass('hide');
                  $('#qstnImageDiv').addClass('hide');
           }
           else if($(this).attr("value")=="boolean"){
                 isImage  =  false;
                 $('#uploadImageForm').addClass('hide');
                  $('#qstnImageDiv').addClass('hide');
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


        $("#uploadImageForm").submit();
// comment to handle image questions
/*

        // alert('test');
        $.ajax({
            url: '/assessment/php/manageQuestions.php',
            data: {action:"create",ssc:ssc,job_role:job_role,qpcode:qp_code, qstn: question, opta: optiona, optb: optionb, optc: optionc, optd: optiond, corrans: correctanswer, mark: marks,language:lang,noOfOptions:noOfOptions },
            dataType: 'json', //since you wait for json
            success: function(data) {
                if (typeof data.error === 'undefined') {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                    $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
                    $('#error_msg').addClass('in');
                    $('#error_msg strong').text("Success! " + data.success);
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

        */
    });



// Create Image Type Questions
var files;
// Add events
$('input[type=file]').on('change', prepareUpload);

// Grab the files and set them to our variable
function prepareUpload(event) {
    files = event.target.files;
}

$('.close').click(function() {
    $(this).parent().removeClass('in'); // hides alert with Bootstrap CSS3 implem
});


// We can attach the `fileselect` event to all file inputs on the page
$(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});



// We can watch for our custom `fileselect` event like this
$(document).ready(function() {
    $(':file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        var extension = log.substr((log.lastIndexOf('.') + 1));
        if (extension != "jpeg" && extension != "jpg") {
            $('#error_msg').addClass('in');
            $('#error_msg strong').text("Error! File should be in .jpeg or jpg format.");
            $('#uploadFileButton').addClass('disabled');
            $('#uploadFileButton').prop('disabled', true);

            //$("error_msg.alert alert-danger fade in").css("display", "inline");
        } else {
            $('#uploadFileButton').removeClass('disabled');
            $('#uploadFileButton').prop('disabled', false);
        }
        if (input.length) {
            input.val(log);
        } else {
            if (log) alert(log);
        }

    });
});


// Catch the form submit and upload the files
$("#uploadImageForm").submit(function(event) {
    $('#uploadFileButton').addClass('disabled');
    $('#uploadFileButton').prop('disabled', true);
    $("#files").append($("#fileUploadProgressTemplate").tmpl());
    event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening
    // Create a formdata object and add the files
    var data = new FormData();
    $.each(files, function(key, value) {
        data.append(key, value);
    });

    $.ajax({
        url: '/assessment/php/manageQuestions.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request

        xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            if (xhr.upload) {
                xhr.upload.addEventListener('progress', function(evt) {
                    var percent = (evt.loaded / evt.total) * 100;
                    $("#files").find(".progress-bar").width(percent + "%");
                }, false);
            }
            return xhr;
        },

        success: function(data, textStatus, jqXHR) {
            $("#files").children().last().remove();
            $("#files").append($("#fileUploadItemTemplate").tmpl(data));
            if (typeof data.error === 'undefined') {
                // Success so call function to process the form
                submitForm(event, data);
            } else {
                // Handle errors here
                console.log('ERRORS: ' + data.error);
                $('#error_msg').removeClass('alert-danger').addClass( 'alert-success');
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Error! " + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('ERRORS: ' + textStatus);
        }
    });
});




function submitForm(event, data) {
    $form = $(event.target);
    // Serialize the form data
    var formData = $form.serialize();
    // You should sterilise the file names
    $.each(data.files, function(key, value) {
        formData = formData + '&filenames[]=' + value;
    });

/*
    data: {action:"create",ssc:ssc,job_role:job_role,qpcode:qp_code,
     qstn: question, opta: optiona, optb: optionb, optc: optionc,
      optd: optiond, corrans: correctanswer, mark: marks,
      language:lang,noOfOptions:noOfOptions },
      */

    formData = formData + '&action=' + create;
    formData = formData + '&ssc=' + ssc;
    formData = formData + '&job_role=' + job_role;
    formData = formData + '&qpcode=' + qpcode;
    formData = formData + '&qstn=' + question;
    formData = formData + '&opta=' + optiona;
    formData = formData + '&optb=' + optionb;
    formData = formData + '&optc=' + optionc;
    formData = formData + '&optd=' + optiond;
    formData = formData + '&corrans=' + correctanswer;
    formData = formData + '&mark=' + marks;
    formData = formData + '&language=' + lang;
    formData = formData + '&noOfOptions=' + noOfOptions;

    $.ajax({
        url: '/assessment/php/manageQuestions.php',
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        success: function(data, textStatus, jqXHR) {
            if (typeof data.error === 'undefined') {
                // Success so call function to process the form
                console.log('SUCCESS: ' + data.success);
                $('#error_msg').removeClass('alert-danger').addClass('alert-success');
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Success! " + data.success);
            } else {
                // Handle errors here
                console.log('ERRORS: ' + data.error);
                $('#error_msg').removeClass( 'alert-success' ).addClass('alert-danger');
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Error! " + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Handle errors here
            $('#error_msg').removeClass( 'alert-success' ).addClass('alert-danger');
            $('#error_msg').addClass('in');
            $('#error_msg strong').text("Error! " + data.error);
            console.log('ERRORS: ' + textStatus);
        },
        complete: function() {
            // STOP LOADING SPINNER
        }
    });
}

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
       data: { action :"update" ,id:qstnId, ssc:ssc,job_role:job_role,qpcode:qp_code,qstn: question, opta: optiona, optb: optionb, optc: optionc, optd: optiond, corrans: correctanswer, mark: marks,language:lang,noOfOptions:noOfOptions },
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
