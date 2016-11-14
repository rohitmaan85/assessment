$(document).ready(function() {

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
                    })
                }
            })
            // control.replaceWith(control.val('').clone(true));
    });


    $('#ssc-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');

        console.log($(this).text());
        loadJobRoles($(this).text());
    });

    function loadJobRoles(sscValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: { get: "jobrole", ssc: sscValue },
            dataType: 'json', //since you wait for json
            success: function(json) {
                // Clear dropdown
                $('#jobrole-dropdown-menu').children().remove();
                //now when you received json, render options
                $.each(json, function(i, option) {
                    var rendered_option = '<li><a href="#">' + option + '</a></li>';
                    $(rendered_option).appendTo('#jobrole-dropdown-menu');
                })
            }
        })
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
            }
        })
    }

});