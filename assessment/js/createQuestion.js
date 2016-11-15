$(document).ready(function() {

    $('input[type="radio"]').click(function(){
           if($(this).attr("value")=="1"){             //alert("select 1");
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
           } else{

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
       });


    //$('#createExamForm').BootstrapValidator();
    $("#createQstnButton").click(function() {

        var lang =  $('#selectLang input:radio:checked').val();
        var hindiQstn = $("#hindiTextQstn").val();
        var qpCode = $("#name").val();
        var question = $("#questionInputTextArea").val();
        var optiona = $("#optionATextAreaInput").val();
        var optionb = $("#optionBTextAreaInput").val();
        var optionc = $("#optionCTextAreaInput").val();
        var optiond = $("#optionDTextAreaInput").val();
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

        // alert('test');
        $.ajax({
            url: '/assessment/php/manageQuestions.php',
            data: { qpcode: "AGR/Q4804", qstn: question, opta: optiona, optb: optionb, optc: optionc, optd: optiond, corrans: correctanswer, mark: marks },
            dataType: 'json', //since you wait for json
            success: function(data) {
                if (typeof data.error === 'undefined') {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                    $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
                    $('#error_msg').addClass('in');
                    $('#error_msg strong').text("Success! " + data.success);
                    /* Get from database using jax request*/
                    subjectTable.ajax.reload();
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
