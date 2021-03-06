$(document).ready(function() {
    var sscValue = "";
    var subId = "";
    var qpCode = "";
    var subjectName = "";
    var examDurationValue = "";
    var attemptCountValue = "";
    var passingPercentValue = "";
    var counterforRequiredQstns = 0;
    var moduleIds = [];
    var moduleNoOfQstns = [];
    var batchId = "";


    $("#noOfQstnsText").focusout(function() {
        if( $("#totalMarksText").val()!== "" && $("#noOfQstnsText").val()!== ""){

            var marksEachQuestion = $("#totalMarksText").val()/$("#noOfQstnsText").val();
            $( "#marksForEachQuestionText" ).val(marksEachQuestion);
        }
      });

      $("#totalMarksText").focusout(function() {
          if( $("#totalMarksText").val()!== "" && $("#noOfQstnsText").val()!== ""){

              var marksEachQuestion = $("#totalMarksText").val()/$("#noOfQstnsText").val();
              $( "#marksForEachQuestionText" ).val(marksEachQuestion);
          }
        });


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


    // Bind Enter Key to 'Create Button'
    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#createExamButton").click();
        }
    });

    //Create Module Section dynamically
    //displayModuleSection();


    $("#showModules-checkbox").change(function() {
        if (this.checked) {
            $('#showModuleDiv').removeClass('hide');
            //Do stuff
        } else {
            $('#showModuleDiv').addClass('hide');
        }
    });

    $(function() {
      //  $('#startDate').datetimepicker();
      //  $('#endDate').datetimepicker();
    });

    $(function () {
    $('#startDate').datetimepicker();
    $('#endDate').datetimepicker({
        useCurrent: false //Important! See issue #1075
    });
    $("#startDate").on("dp.change", function (e) {
        $('#endDate').data("DateTimePicker").minDate(e.date);
    });
    $("#endDate").on("dp.change", function (e) {
        $('#startDate').data("DateTimePicker").maxDate(e.date);
    });
});


/*
    $(document).ready(function() {

        $("#startDate").datetimepicker({
            //numberOfMonths: 2,
            onSelect: function(selected) {
                $("#endDate").datepicker("option", "minDate", selected);
            }

        });

        $("#endDate").datetimepicker({
            //numberOfMonths: 2,
            onSelect: function(selected) {
                $("#startDate").datepicker("option", "maxDate", selected);
            }
        });

    });
*/

    $("#sscButton").click(function() {
        var selText = $(this).text();
        loadJobRoles("");
    });


    $('#ssctest-dropdown-menu').on('click', 'li a', function() {
        batchId = "";
        $('#selGroupDropdown').val("");
        sscValue = $(this).text();
        var selText = $(this).text();
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');

        $('#jobroletest-dropdown-menu').children().remove();
        $('#jobroledropdownButton').html("-Select JobRole-" + '<span class="caret"></span>');

        $('#batch-dropdown-menu').children().remove();
        $('#batchdropdownButton').html(" -- Select BatchId -- " + '<span class="caret"></span>');

        $('#selGroupDropdown').children().remove();
        loadJobRoles(selText);
        $('#createExamForm').addClass('hide');
        $('#studentDetails').prop('disabled', true);
        $('#batch_info').prop('disabled', true);

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
    $('#jobroletest-dropdown-menu').on('click', 'li a', function() {
        $('#studentDetails').prop('disabled', true);
        $('#batch_info').prop('disabled', true);

        batchId = "";
        $('#selGroupDropdown').val("");
        $('#createExamForm').addClass('hide');
        subId = $(this).attr('id');
        displayModuleSection();
        loadBatchList();
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        //$('#createExamForm').removeClass('hide');

        $('#batch-dropdown-menu').children().remove();
        $('#batchdropdownButton').html(" -- Select BatchId -- " + '<span class="caret"></span>');
    });


    $('#batch-dropdown-menu').on('click', 'li a', function() {
        batchId = $(this).attr('id');
        $('#selGroupDropdown').val(batchId);
        $('#selGroupDropdown').prop('disabled', true);
        $('#batchdropdownButton').html($(this).text() + '<span class="caret"></span>');
        $('#createExamForm').removeClass('hide');
        $('#studentDetails').prop('disabled', false);
        $('#batch_info').prop('disabled', false);

    });

    function loadBatchList() {
        $.ajax({
            url: '/assessment/php/manageAttendence.php',
            type: 'POST',
            data: {
                action: "getBatchList",
                subId: subId
            },
            dataType: 'json', //since you wait for json
            success: function(json) {

                //now when you received json, render options
                var counter = 0;
                $('#batch-dropdown-menu').children().remove();
                //  $('#batchdropdownButton').html(" -- Select BatchId -- " + '<span class="caret"></span>');
                $('#selGroupDropdown').children().remove();
                $('#selGroupDropdown')
                    .append($("<option></option>").attr("value", "None Selected")
                        .text("None Selected"));
                // Get  Data from Json
                $.each(json, function(i, option) {
                    var rendered_option = '<li><a id="' + option + '" href="#">' + option + '</a></li>';
                    $(rendered_option).appendTo('#batch-dropdown-menu');

                    $('#selGroupDropdown').append($("<option></option>")
                        .attr("value", option)
                        .text(option));
                    counter++;
                });
            }
        });
    }

    function displayModuleSection() {

        $.ajax({
            url: '/assessment/php/manageCategory.php',
            data: {
                get: "catNModules",
                subId: subId
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                //var counter = 0;
                var moduleCompleteDiv = "";
                var moduleRowDiv = "";
                var startDiv = '<div class="col-xs-14"><hr></div>';
                var endDiv = '<div class="col-xs-14"><hr></div></div>';
                var counter = 0;
                var categoryExist = false;
                $.each(json.data, function(i, option) {
                    // Iterate Modules if exists
                    if (option.modules !== null) {
                        $.each(option.modules, function(i, module) {
                            //  moduleIds.push(option.modules.id);
                            counter++;
                            counterforRequiredQstns++;
                            categoryExist = true;
                            moduleRowDiv = "";
                            var categoryDiv = '<div class="form-group"><div class="col-xs-2"><input id="category_' + counter + '" class="form-control" type="text" 	value="' + option.category + '" disabled="true"></div>';
                            var moduleDiv = '<div class="col-xs-2"><input id="module_' + counter + '" class="form-control" type="text" value="' + module.moduleName + '" disabled="true"></div>';
                            var moduleQstnsAvailDiv = ' <label for="qstnsAvailable"  class="col-xs-1">Available Qstns</label><div class="col-xs-1"><input type="text" id="moduleAvailQstns_' + counter + '" class="form-control" disabled="true" value="' + module.noOfQstns + '" ></div>';
                            var moduleRequiredQstnsDiv = "";
                            var moduleAdditionalDiv = "";
                            var moduleMarksDiv = "";
                            if (module.noOfQstns > 0) {
                                moduleIds[module.moduleName] = module.id;
                                //  moduleIds[module.id] = module.moduleName;
                                moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control"></div>';
                                moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control"></div>';
                                moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox"></div></div>';
                            } else {
                                moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control" disabled="true"></div>';
                                moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control" disabled="true"></div>';
                                moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox" disabled="true"></div></div>';
                            }
                            moduleRowDiv = categoryDiv + moduleDiv + moduleQstnsAvailDiv + moduleMarksDiv + moduleRequiredQstnsDiv + moduleAdditionalDiv;
                            moduleCompleteDiv = moduleCompleteDiv + moduleRowDiv;
                        });
                    } else {

                        counter++;
                        counterforRequiredQstns++;
                        categoryExist = true;
                        moduleRowDiv = "";
                        var moduleRequiredQstnsDiv = "";
                        var moduleAdditionalDiv = "";
                        var moduleMarksDiv = "";

                        var categoryDiv = '<div class="form-group"><div class="col-xs-2"><input id="category_' + counter + '" class="form-control" type="text" 	value="' + option.category + '" disabled="true"></div>';
                        var moduleDiv = '<div class="col-xs-2"><input style="color: red;" id="module_' + counter + '" class="form-control" type="text" value="No Module Found" disabled="true"></div>';
                        var moduleQstnsAvailDiv = ' <label for="qstnsAvailable"  class="col-xs-1">Available Qstns</label><div class="col-xs-1"><input type="text" id="moduleAvailQstns_' + counter + '" class="form-control" disabled="true" value="' + option.noOfQstnsInCategory + '"></div>';

                        if (option.noOfQstnsInCategory > 0) {
                            //  moduleIds[option.id] = option.category;
                            moduleIds[option.category] = option.id;
                            moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control"></div>';
                            moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control"></div>';
                            moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox"></div></div>';

                        } else {
                            moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control" disabled="true"></div>';
                            moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control" disabled="true"></div>';
                            moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox" disabled="true"></div></div>';

                        }
                        /*
                            var moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control"></div>';
                            var moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control"></div>';
                            var moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox"></div></div>';
                       */
                        moduleRowDiv = categoryDiv + moduleDiv + moduleQstnsAvailDiv + moduleMarksDiv + moduleRequiredQstnsDiv + moduleAdditionalDiv;
                        moduleCompleteDiv = moduleCompleteDiv + moduleRowDiv;
                    }

                });
                // Display module Rows
                if (categoryExist)
                    $('#showModuleDiv').html(startDiv + moduleCompleteDiv + endDiv);
                else {
                    $('#showModuleDiv').html('<div class="form-group"><div class="col-xs-14"><input style="color: red;" class="form-control" type="text" value="No Category Found , Please create it first" disabled="true"></div></div>');
                }
            }
        });

    }


    $("#studentDetails").click(function() {
        $('#displayStudentsModal').modal('show');
        showStudentsList();
    });

    $("#batch_info").click(function() {
        //var batchId = $("#selGroupDropdown").val();
        if (batchId !== "") {
            getBatchInformationForDialog(batchId);
        }
        $('#displayBatchDetailsModal').modal('show');
        /*
        $(this).css("background-color", "yellow");
        }, function(){
        $(this).css("background-color", "pink");
    */
    });
    // Handle show Modules Part




    $("#examDurButton").click(function() {
        var selText = $(this).text();
        $('#examDurButton').html(selText + '<span class="caret"></span>');
        for (i = 5; i <= 100; i++) {
            var rendered_option = '<li><a href="#">' + i + '</a></li>';
            $(rendered_option).appendTo('#examdur-dropdown-menu');
            i = i + 4;
        }
    });

    $('#examdur-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        $('#examDurButton').html(selText + ' Minutes <span class="caret"></span>');
        examDurationValue = selText;
    });


    $("#passPercentButton").click(function() {
        var selText = $(this).text();
        $('#passPercentButton').html(selText + '<span class="caret"></span>');
        for (i = 30; i <= 75; i = i + 5) {
            var rendered_option = '<li><a href="#">' + i + '</a></li>';
            $(rendered_option).appendTo('#passpercent-dropdown-menu');
            //i=i+5;
        }
    });

    $('#passpercent-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        passingPercentValue = selText;
        $('#passPercentButton').html(selText + '    % <span class="caret"></span>');
    });

    $("#atmptCountButton").click(function() {
        var selText = $(this).text();
        $('#atmptCountButton').html(selText + '<span class="caret"></span>');
        for (i = 1; i <= 5; i++) {
            var rendered_option = '<li><a href="#">' + i + '</a></li>';
            $(rendered_option).appendTo('#atmptCount-dropdown-menu');
            //i=i+5;
        }
    });

    $('#atmptCount-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        attemptCountValue = selText;
        $('#atmptCountButton').html(selText + ' Attempt<span class="caret"></span>');
    });

    // Create Exam startDate

    function checkMandatoryFields() {
        var allFieldsOk = true;
        var fieldsValue = "";
        if ($("#examNameText").val() === '') {
            fieldsValue += "* Exam Name \n";

            //  $('#alertMessage').val(fieldsValue);
            //  $('#alertModal').modal('show');
            //  alert("Please enter Exam Name !");
            allFieldsOk = false;
        }
        if ($("#noOfQstnsText").val() === '') {
            fieldsValue += "* Number of Questions\n";
            //  alert("Please enter Number of Questions!");
            allFieldsOk = false;
        }

        if ($("#examInstArea").val() === '') {
            fieldsValue += "* Exam Instruction\n";
            //  alert("Please enter Exam Instruction!");
            allFieldsOk = false;
        }

        if (examDurationValue === '') {
            fieldsValue += "* Exam Duration\n";
            //  alert("Please enter Duration time !");
            allFieldsOk = false;
        }
        /*
        if (attemptCountValue === '') {
            fieldsValue += "* Exam Duration";
            //  alert("Please enter Attempt Count !");
            allFieldsOk = false;
        }
        */

        var startDate = $("#startDate").find("input").val();
        if (startDate === '') {
            fieldsValue += "* Start Date \n";
            //  alert("Please enter Exam Start Date !");
            allFieldsOk = false;
        }

        var endDate = $("#endDate").find("input").val();
        if (endDate === '') {
            fieldsValue += "* End Date \n";
            //    alert("Please enter Exam End Date !");
            allFieldsOk = false;
        }
        if ($("#selGroupDropdown").val() === 'None Selected') {
            fieldsValue += "* Batch Id \n";
            //  alert("Please enter Exam Batch Id !");
            allFieldsOk = false;
        }

        if ($("#totalMarksText").val() === '') {
            fieldsValue += "* Total Marks \n";
            //  alert("* Total Marks");
            allFieldsOk = false;
        }


        // $("#examNameText").focus();
        //moduleMarks_
        // Check Total Number of Questions :
        var qstnId = "";
        var categoryNameId = "";
        var categoryName = "";
        var categoryId = "";

        var moduleNameId = "";
        var moduleName = "";
        var moduleId = "";

        var totalModuleQstns = parseInt(0, 10);
        moduleNoOfQstns = [];
        //  var moduleRequiedQstnsByUser = [];
        //var row1 = $('#moduleReqQstns_1').val();
        var i = 1;
        for (i = 1; i <= counterforRequiredQstns; i++) {
            qstnId = '#moduleReqQstns_' + i;
            var value = $(qstnId).val();
            if (typeof value != "undefined") {
                if (value !== '' && !isNaN(value)) {
                    totalModuleQstns = totalModuleQstns + parseInt(value);

                    // Get category id from array
                    categoryNameId = '#category_' + i;
                    categoryName = $(categoryNameId).val();
                    categoryId = moduleIds[categoryName];

                    // If category does not exist then check module name
                    if (typeof categoryId === 'undefined') {
                        moduleNameId = '#module_' + i;
                        moduleName = $(moduleNameId).val();
                        moduleId = moduleIds[moduleName];
                        moduleNoOfQstns.push({
                            id: moduleId,
                            noOfQstns: parseInt(value),
                            isCategory: "1"
                        });
                        //moduleNoOfQstns.id =moduleId;
                        //moduleNoOfQstns.noOfQstns =parseInt(value);
                    } else {
                        //  moduleNoOfQstns.id =categoryId;
                        //  moduleNoOfQstns.noOfQstns =parseInt(value);
                        moduleNoOfQstns.push({
                            id: categoryId,
                            noOfQstns: parseInt(value),
                            isCategory: "0"
                        });
                        //  moduleNoOfQstns[categoryId]=parseInt(value);
                    }



                    /*
                    $.each(moduleNoOfQstns, function(key, value) {
                      if(value===)
                          //alert(key)
                    });*/
                } else
                    totalModuleQstns = totalModuleQstns + parseInt(0);
            }
        }

        //alert(moduleNoOfQstns.length);
        if ($("#noOfQstnsText").val() != totalModuleQstns) {
            allFieldsOk = false;
            fieldsValue += "* Total Number of Questions '" + $("#noOfQstnsText").val() + "' in Test should be Equal to sum of all module Questions '" + totalModuleQstns + "' \n";
        }


        // Check Total Marks :
        // var moduleId = "";
        var totalModuleMarks = parseInt(0, 10);
        //var row1 = $('#moduleReqQstns_1').val();
        for (i = 1; i <= counterforRequiredQstns; i++) {
            qstnMarksId = '#moduleMarks_' + i;
            var marksValue = $(qstnMarksId).val();
            if (typeof marksValue != "undefined") {
                if (marksValue !== "" && !isNaN(marksValue))
                    totalModuleMarks = totalModuleMarks + parseInt(marksValue);
                else
                    totalModuleMarks = totalModuleMarks + parseInt(0);
            }
        }
        if ($("#totalMarksText").val() != totalModuleMarks) {
            allFieldsOk = false;
            fieldsValue += "* Total Marks '" + $("#totalMarksText").val() + "' in Test should be Equal to sum of all module Marks '" + totalModuleMarks + "' \n";
        }



        if (allFieldsOk === false) {
            $('#alertMessage').val(fieldsValue);
            $('#alertModal').modal('show');
        }
        return allFieldsOk;
    }


    $("#createExamButton").click(function() {
        if (checkMandatoryFields()) {
            //subDetails
            var qpCode = $("#qpcodeText").val();
            // examDetails
            var examName = $("#examNameText").val();
            var noOfQstns = $("#noOfQstnsText").val();
            var examdesc = $("#examInstArea").val();
            var startDate = $("#startDate").find("input").val();
            var endDate = $("#endDate").find("input").val();
            var decResult = $('#decResultRadio input:radio:checked').val();
            var batchId = $("#selGroupDropdown").val();
            var negMarking = $('#negMarkingRadio input:radio:checked').val();
            var randomQstn = $('#radomQstnRadio input:radio:checked').val();
            var totalMarks = $("#totalMarksText").val();
            // alert('test');
            $.ajax({
                type: 'POST',
                url: '/assessment/php/manageExams.php',
                data: {
                    action: "create",
                    subId: subId,
                    exName: examName,
                    noOfQstns: noOfQstns,
                    examDesc: examdesc,
                    examDur: examDurationValue,
                    atmptCount: attemptCountValue,
                    startDate: startDate,
                    endDate: endDate,
                    decResult: decResult,
                    batchId: batchId,
                    negMarking: negMarking,
                    randomQstn: randomQstn,
                    totalMarks: totalMarks,
                    pp: passingPercentValue,
                    noOfModuleQstsArr: moduleNoOfQstns
                },
                dataType: 'json', //since you wait for json
                success: function(data) {
                    if (typeof data.error === 'undefined') {
                        // Success so call function to process the form
                        console.log('SUCCESS: ' + data.success);
                        //  $('#error_msg').addClass('in');
                        //    $('#error_msg strong').text("Success! " + data.success);
                        $('#successMessageText').text(data.success);
                        $('#successMessage').modal('show');
                        /* Get from database using jax request*/
                        //subjectTable.ajax.reload();
                    } else {
                        // Handle errors here
                        console.log('ERRORS should be in alert: ' + data.error);
                        //  $('#error_msg').addClass('in');
                        //  $('#error_msg strong').text("Error! " + data.error);
                        $('#errorMessageText').text(data.error);
                        $('#errorMessage').modal('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                    $('#errorMessageText').text("Error! Invalid response from server" + errorThrown);
                    $('#errorMessage').modal('show');
                    //$('#error_msg strong').text("Error! Invalid response from server" + errorThrown);
                },
            });
        }
    });

    // Display batch details on Hovering.
    /*
    $(".modal").on("hidden.bs.modal", function(){
      $("#batchDetailBody").html("");
    });
    */

    // Function to display students table when click on table Row.
    function showStudentsList() {
        var studentsTable = $('#showStdntsTable').DataTable({
            "oLanguage": {
                "sEmptyTable": '<strong> No Students Found in selected Batch !!!</strong>'
            },
            // serverSide: true,
            initComplete: function() {
                studentsTable.buttons().container()
                    .appendTo('#showStdntsTable_wrapper .col-sm-6:eq(0)');
            },

            buttons: ['copy', 'excel', 'pdf'],
            "ajax": {
                'url': '/assessment/php/manageAttendence.php',
                'type': 'POST',
                'data': {
                    action: 'getStudentList',
                    batch_id: batchId
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                        json.data = [];
                    } else {
                        //  $('#error_msg').addClass('hide');
                    }
                    return json.data;
                },
            },
            "destroy": true,
        });
    }



    function getBatchInformationForDialog(batch_id) {
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageAttendence.php',
            data: {
                action: "getBatchInfo",
                batch_id: batch_id
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                    $.each(json, function(i, option) {
                        $('#batch_text').val(option[0][0]);
                        $('#exam_date_text').val(option[0][2]);
                        $('#no_students_text').val(option[0][5]);
                        $('#center_text').val(option[0][3]);
                        $('#training_text').val(option[0][4]);
                        $('#status_text').val(option[0][6]);
                    });
                }
                // control.replaceWith(control.val('').clone(true));
        });
    }

    // load Exams table.
    showExams();

    function showExams() {
        var subjectId = $("#qpcodeText").val();
        var examTable = $('#examTable').DataTable({
            "ajax": {
                'type': 'POST',
                'url': '/assessment/php/manageExams.php',
                'data': {
                    get: 'exams',
                    subid: subjectId
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                        //$('#qstns').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Questions Found for selected Subject in Database.</strong></div>');
                        json.data = [];
                    } else {
                        //  $('#error_msg').addClass('hide');
                    }

                    return json.data;
                },
            },
            /* "scrollY": "200px",
        "paging": false, */
            'columnDefs': [{
                "targets": 1,
                "bVisible": false,
                "searchable": false
            }, {
                "targets": 8,
                "data": null,
                "defaultContent": "<button id=\"openQuestion\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Edit</button>  <button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-danger btn-xs\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>"
            }, ],
            "destroy": true,
        });
    }



});
