$(function() {

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
            if (extension != "xls") {
                // $("#error_msg").css('display', 'inline');
                $('.alert-success').removeClass('alert-success').addClass('alert-danger');
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Error! File should be in excel format.");

                //$("error_msg.alert alert-danger fade in").css("display", "inline");
            } else {
                // $("#success_msg").css('display', 'inline-block');
                //$('#error_msg').addClass('alert alert-success fade');
                $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
                $('#error_msg').addClass('in');
                $('#error_msg strong').text("Success! File has been imported successfully.");

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

});