$(document).ready(function() {
    var sscValue = "";
    var subId = "";
    var qpCode = "";
    var subjectName = "";
    var catId = "";
    var moduleId ="";

    // Populate SSC Dropdown

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


    $("#sscButton").click(function() {
        var selText = $(this).text();
        loadJobRoles("");
    });


    $('#ssctest-dropdown-menu').on('click', 'li a', function() {
        sscValue = $(this).text();
        var selText = $(this).text();
        $('#jobroletest-dropdown-menu').children().remove();
        $('#selGroupDropdown').children().remove();
        $('#jobroledropdownButton').html("-Select JobRole-" + '<span class="caret"></span>');
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
        loadJobRoles(selText);
    });


    function loadJobRoles(sscValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: {
                get: "jobroleWithQPCode",
                ssc: sscValue
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                var counter = 0;
                $.each(json.job_role, function(i, option) {
                    var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + '">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                    $(rendered_option).appendTo('#jobroletest-dropdown-menu');
                    counter++;
                });
            }
        });
    }


    // Click on Jobrole DropDown Item.
    /*
    $('#jobroletest-dropdown-menu').on('click', 'li a', function() {
        subId = $(this).attr('id');
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        //  $('#createExamForm').removeClass('hide')
    });
    */

    $('#jobroletest-dropdown-menu').on('click', 'li a', function() {
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        subId=$(this).attr("id");
        $('#category_list_group').children().remove();
        $('#module_list_group').children().remove();
        showCategory();
        //showExams();
    });

    function showCategory() {
      $('#category_list_group').children().remove();

        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageCategory.php',
            data: {
                action: "getCategories",
                subId: subId
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                var counter = 0;
                $.each(json.ids, function(i, cat_id) {
                   var rendered_option = '<li id="'+cat_id+'" row="'+counter+'" class="list-group-item"><span>'+json.category[counter]+'</span><button name="renameCategory" id="rename_cat" class="btn btn-sm btn-info pull-right" >  Rename Category  </button><span></span>  <button class="btn btn-xs btn-danger" id="delete_'+cat_id+'">X</button></li>';
                   //  var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + '">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                    $(rendered_option).appendTo('#category_list_group');
                    counter++;
                });
             },
             error: function(data) {
                 // Handle errors here
                 console.log('ERRORS: ' + data);
                 $('#errorModal').modal('show');
                 // Set a timeout to hide the element again
                 setTimeout(function(){
                       $("#errorModal").modal('hide');
                 }, 5000);
                 // STOP LOADING SPINNER
             }
          });
    }


    $('#category_list_group').on('click', 'li', function() {
        //$('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        catId=$(this).attr("id");
        $('#module_list_group').children().remove();
        //alert(catId);
        showModules();
        //showCategory();
        //showExams();
    });

$('#category_list_group').on('click', '#rename_cat', function(){
  $('#categoryRenameModal').modal('show');
});


$('#newCatText').on('keyup', function(e) {
    if ($('#newCatText').val() === "")
        $('#createCat').prop('disabled', true);
    else {
        $('#createCat').prop('disabled', false);
    }
});

$("#createCat").click(function() {
    $.ajax({
        type: 'POST',
        url: '/assessment/php/manageCategory.php',
        data: {
            action: "renameCategory",
            subId: subId,
            cat_id: catId,
            new_name:$('#newCatText').val()
        },
        dataType: 'json', //since you wait for json
        success: function(json) {
           $('#successMessageText').text(json.message);
           $('#successMessage').modal('show');
           showCategory();
         },
         error: function(data) {
             // Handle errors here
             console.log('ERRORS: ' + data);
             $('#errorMessageText').text(data.message);
             $('#errorModal').modal('show');
             // Set a timeout to hide the element again
             setTimeout(function(){
                   $("#errorModal").modal('hide');
             }, 5000);
             // STOP LOADING SPINNER
         }
      });
});

    function showModules() {
       $('#module_list_group').children().remove();
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageCategory.php',
            data: {
                action: "getModules",
                subId: subId,
                cat_id: catId
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                var counter = 0;
                $.each(json.ids, function(i, mod_id) {
                   var rendered_option = '<li id="'+mod_id+'" row="'+counter+'" class="list-group-item"><span>'+json.module[counter]+'</span> <span><button name="renameCategory" id="rename_module" class="btn btn-sm btn-info pull-right" >  Rename Module  </button></span><button class="btn btn-xs btn-danger" id="delete_field_btn_'+mod_id+'" rowid="0">X</button></li>';
                   //  var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + '">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                    $(rendered_option).appendTo('#module_list_group');
                    counter++;
                });

                /*
                $.each(data, function(i, option) {
                    var rendered_option_new = '<li id="field_row_0" row="0" class="list-group-item"><span>test</span><button class="btn btn-xs btn-danger pull-right" id="delete_field_btn_0" rowid="0">X</button></li>';
                    var rendered_option = '<li><a href="#">' + option + '</a></li>';
                    $(rendered_option).appendTo('#cat-dropdown-menu');
                });*/
             },
             error: function(data) {
                 // Handle errors here
                 console.log('ERRORS: ' + data);
                 $('#errorModal').modal('show');
                 // Set a timeout to hide the element again
                 setTimeout(function(){
                       $("#errorModal").modal('hide');
                 }, 5000);
                 // STOP LOADING SPINNER
             }
          });
    }



    $('#module_list_group').on('click', 'li', function() {
        //$('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        moduleId=$(this).attr("id");
        //$('#module_list_group').children().remove();
        //alert(catId);
        //showModules();
        //showCategory();
        //showExams();
    });


    $('#module_list_group').on('click', '#rename_module', function(){
      $('#moduleRenameModal').modal('show');
    });


    $('#newModuleText').on('keyup', function(e) {
        if ($('#newModuleText').val() === "")
            $('#createModule').prop('disabled', true);
        else {
            $('#createModule').prop('disabled', false);
        }
    });

    $("#createModule").click(function() {
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageCategory.php',
            data: {
                action: "renameModule",
                subId: subId,
                cat_id: catId,
                mod_id:moduleId,
                new_name:$('#newModuleText').val()
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
               $('#successMessageText').text(json.message);
               $('#successMessage').modal('show');
               showModules();
             },
             error: function(data) {
                 // Handle errors here
                 console.log('ERRORS: ' + data);
                 $('#errorMessageText').text(data.message);
                 $('#errorModal').modal('show');
                 // Set a timeout to hide the element again
                 setTimeout(function(){
                       $("#errorModal").modal('hide');
                 }, 5000);
                 // STOP LOADING SPINNER
             }
          });
    });



    var examsTable = "";
    showExams("");

    function showExams() {
        examsTable = $('#exams').DataTable({
            "oLanguage": {
                "sEmptyTable": '<strong>No Exams Available for this Subject \\ Batch  !</strong>'
            },
            // serverSide: true,
            initComplete: function() {
                examsTable.buttons().container()
                    .appendTo('#exams_wrapper .col-sm-6:eq(0)');
            },

            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            "ajax": {
                'type': 'POST',
                'url': '/assessment/php/manageExams.php',
                'data': {
                    get: 'exams',
                    subid: subId
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                        //  $('#examTable').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Exams Found for selected Subject in Database.</strong></div>');
                        json.data = [];
                    } else {
                        $('#error_msg').addClass('hide');
                    }

                    return json.data;
                },
            },
            'columnDefs': [{
                "targets": 1,
            }, {
                "targets": 2,
                "searchable": false,
                "data": null,
                "width": "55%",
              "defaultContent": " <button id=\"showExamDetails\"  type=\"button\" class=\"btn btn-info btn-sm\" >Show Exam Details</button>   <button id=\"editExamButton\"  type=\"button\" class=\"btn btn-primary btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Edit</button>  <button id=\"manQstsButton\"  type=\"button\" class=\"btn btn-success btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Manage Questions</button>  <button id=\"showQuestions\"  type=\"button\" class=\"btn btn-warning btn-sm\" ><span class=\"glyphicon glyphicon-edit\"></span>Show Questions</button> <button id=\"deleteExamButton\"  type=\"button\" class=\"btn btn-danger btn-sm\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>  "
            }, ],
            "destroy": true,
        });
    }


    // Show Questions on click on shoq qustions button.
    $('#exams tbody').on('click', '#showQuestions', function() {
        //console.log(testTable.row($(this).parents('tr')).data());
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        window.open("examQuestions.php?examname=" + data[0]);
    });

    var exam_name = "";

    $('#exams tbody').on('click', '#showExamDetails', function() {
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        exam_name = data[0];
        getExamInformationForDialog();
        $('#displayExamDetailsModal').modal('show');
    });

    $('#exams tbody').on('click', '#manQstsButton', function() {
        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        exam_name = data[0];
        window.open("manageExamQuestions.php?action=manageExamQstn&examName=" + exam_name);
    });

    $('#exams tbody').on('click', '#deleteExamButton', function() {

        var data = $('#exams').DataTable().row($(this).parents('tr')).data();
        exam_name = data[0];
        $('#confirm').modal({
                backdrop: 'static',
                keyboard: false
            })
            .one('click', '#delete', function() {
                deleteExam(exam_name);
            });

    });


    function deleteExam(exam_name) {
        $.ajax({
           type: 'POST',
            url: '/assessment/php/manageExams.php',
            data: {
                action: "delete",
                examName: exam_name
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                $('#successMessageText').text(json.message);
                $('#successMessage').modal('show');
                showExams();
                // Set a timeout to hide the element again
                setTimeout(function(){
                      $("#successMessage").modal('hide');
                }, 3000);
            },
            error: function(data) {
                // Handle errors here
                console.log('ERRORS: ' + data);
                $('#errorModal').modal('show');
                    // Set a timeout to hide the element again
                setTimeout(function(){
                      $("#errorModal").modal('hide');
                }, 3000);
                // STOP LOADING SPINNER
            }
        });
    }

    function getExamInformationForDialog() {
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageExams.php',
            data: {
                get: "examDetailsForInfo",
                exam_name: exam_name
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                    $.each(json, function(i, option) {
                        $('#name_text').val(option[0][0]);
                        $('#batch_text').val(option[0][1]);
                        $('#no_of_qstns_text').val(option[0][2]);
                        $('#duration_text').val(option[0][3] + " Minutes");
                        $('#exam_from_text').val(option[0][4]);
                        $('#exam_to_text').val(option[0][5]);
                        $('#total_marks_text').val(option[0][6]);
                        $('#pass_percent_text').val(option[0][7]);
                    });
                }
                // control.replaceWith(control.val('').clone(true));
        });
    }



});
