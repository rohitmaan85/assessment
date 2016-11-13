$(function() {

       var subjectTable =  $('#courses').DataTable({
            "ajax": '/assessment/php/Subjects.php',
        });

    $("#uploadFileButton").click(function() {
        // alert('button clicked');
        $("#uploadXlsForm").submit();
    });

    $("#cancelButton").click(function() {
        // alert(true);
        //$("#uploadXlsForm").submit();
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
            if (extension != "xls" && extension != "xlsx") {
                // $("#error_msg").css('display', 'inline');
                $('.alert-success').removeClass('alert-success').addClass('alert-danger');
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Error! File should be in excel format.");
                $('#uploadFileButton').addClass('disabled')
                    //$("error_msg.alert alert-danger fade in").css("display", "inline");
            } else {
                // $("#success_msg").css('display', 'inline-block');
                //$('#error_msg').addClass('alert alert-success fade');
                $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
                // $('#error_msg').addClass('in');
                //  $('#error_msg strong').text("Success! File has been imported successfully.");
                $('#uploadFileButton').removeClass('disabled')
                    // $('#success_msg').addClass('in');
                    //  $('#success_msg').addClass('in');
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
    $("#uploadXlsForm").submit(function(event) {

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
            url: 'php/uploadSubjects.php?files',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    // Success so call function to process the form
                    submitForm(event, data);
                } else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    });

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
            url: 'php/uploadSubjects.php',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                    $('#error_msg').addClass('in');
                    $('#error_msg strong').text("Success! " + data.success);
                    /* Get from database using jax request*/
                    subjectTable.ajax.reload();
                } else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
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