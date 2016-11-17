$(document).ready(function() {
    var testTable = $('#test').DataTable({
        "ajax": '/assessment/php/Subjects.php',
        "columnDefs": [{
            "targets": -7,
            "data": null,
            "defaultContent": "<button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>  Manage Questions</button>"
        }]
    });

    $('#test tbody').on('click', 'button', function() {
        console.log(testTable.row($(this).parents('tr')).data());
        var data = testTable.row($(this).parents('tr')).data();
        alert(data[0] + "'s salary is: " + data[5]);
    });
});
