$(function() {

    /*
        $("#cancelButton").click(function() {
            //window.fileUploadInput.reset();
            clearFileInput();
            //$("#fileUploadInput").val('').clone(true);
        });*/


    $("#uploadFileButton").click(function() {
            $("#uploadEncryptExamForm").submit();
    });

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
            if (extension != "bme") {
                $('#error_msg').removeClass('hide');
                $('#error_msg').addClass('in');
                $('#error_msg').removeClass('alert-success').addClass('alert-danger');
                $('#error_msg strong').text("Error! Invalid File Format , File should be in '.bme' format.");
                $('#uploadFileButton').prop('disabled', true);
            } else {
                $('#error_msg').addClass('hide');
                $('#uploadFileButton').prop('disabled', false);
            }
            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }

        });
    });

    //$('uploadXlsForm').on('submit', uploadFiles);

    // Catch the form submit and upload the files
    $("#uploadEncryptExamForm").submit(function(event) {
        $('#uploadFileButton').prop('disabled', true);
        $("#files").append($("#fileUploadProgressTemplate").tmpl());
        //alert('here1');
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE

        // Create a formdata object and add the files
        var data = new FormData();
        $.each(files, function(key, value) {
            data.append(key, value);
        });

        $.ajax({
            url: 'php/uploadEncryptExam.php?files',
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
                    $('#error_msg').removeClass('hide');
                    $('#error_msg').addClass('in');
                    $('#error_msg').removeClass('alert-success').addClass('alert-danger');
                    $('#error_msg strong').text("Error! Invalid File Format , File should be in '.bme' format.");

                    $('#error_msg strong').text("Error! " + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    });

  // showBatchDetails();
  function showBatchDetails(){
              var batchTable = $('#batchTable').DataTable({
                "oLanguage": {
                "sEmptyTable": '<strong>No Exams Information have been imported yet !</strong>'
                },
                "ajax": {
                  'url' : '/assessment/php/manageBatch.php',
                  'data': {
                            get: 'batchList',
                          },
                 'dataSrc': function (json) {
                                if(!json.data){
                                          $('#batch').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Batch Information Found.</strong></div>');
                                          json.data = [];
                                      }
                                      else{
                                            $('#error_msg').addClass('hide');
                                  }
                                return json.data;
                          },
                  'complete': function() {
                                                $('#uploadFileButton').prop('disabled', false);
                          }

                },
                "destroy" : true,
              });
          }


function submitForm(event, data) {
        // Create a jQuery object from the form
        $form = $(event.target);
        // Serialize the form data
        var formData = $form.serialize();
        // You should sterilise the file names
        $.each(data.files, function(key, value) {
            formData = formData + '&filenames[]=' + value;
        });
        $.ajax({
            url: 'php/uploadBatch.php',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                    $('#error_msg').removeClass('hide');
                    $('#error_msg').addClass('in');
                    $('#error_msg').removeClass('alert-danger').addClass('alert-success');
                    $('#error_msg strong').text("Success! " + data.success);
                    /* Get from database using jax request*/
                    //  questionTable.ajax.reload();
                    //showBatchDetails();
                } else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                    $('#error_msg').removeClass('hide');
                    $('#error_msg').addClass('in');
                    $('#error_msg').removeClass('alert-success').addClass('alert-danger');
                    $('#error_msg strong').text("Error! " + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
            },
            complete: function() {
                // STOP LOADING SPINNER
            }
        });
    }
});
