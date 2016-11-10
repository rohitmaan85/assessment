$(document).ready(function() {


    var courseData = [{
            "fname": "",
            "lname": "",
            "pos": "0000-00-00",
            "office": "",
            "age": "",
            "sdate": "",
            "sal": "",
            "ext": "",
            "email": ""
        },
        {
            "fname": "Federico",
            "lname": "Lupieri",
            "pos": "2015-09-16",
            "office": "",
            "age": "",
            "sdate": "34170",
            "sal": "Via Ascoli 1",
            "ext": "00112233445566",
            "email": "00112233445566"
        }
    ];

     $('#courses').bootstrapTable({
           data: courseData
     });

/** 
    $.ajax({
        url: 'php/process.php?method=fetchdata',
        dataType: 'json',
        success: function(data) {
            $('#courses').bootstrapTable({
                data: courseData
            });
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
    */
});