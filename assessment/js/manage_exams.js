$(document).ready(function() {

    var subId = "";
    var subjectName = "";
    var qpCode = "";
    var examDurationValue = "";
    var attemptCountValue = "";
    var passingPercentValue = "";

    //Create Module Section dynamically
    displayModuleSection();

    function displayModuleSection() {

        $.ajax({
            url: '/assessment/php/manageCategory.php',
            data: {
                get: "catNModules",
                subId: "AGR/Q4904"
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                //now when you received json, render options
                //var counter = 0;
                var moduleCompleteDiv = "";
                var moduleRowDiv = "";
                var startDiv = '<div class="col-xs-14"><hr></div>';
                var endDiv = '<div class="col-xs-14"><hr></div></div>';
                var counter = 1;
                $.each(json.data, function(i, option) {
                    // Iterate Modules if exists
                    if (option.modules !== null) {
                        $.each(option.modules, function(i, module) {
                            moduleRowDiv = "";
                            var categoryDiv = '<div class="form-group"><div class="col-xs-2"><input id="category_' + counter + '" class="form-control" type="text" 	value="' + option.category + '" disabled="true"></div>';
                            var moduleDiv = '<div class="col-xs-2"><input id="module_' + counter + '" class="form-control" type="text" value="' + module + '" disabled="true"></div>';
                            var moduleQstnsAvailDiv = ' <label for="qstnsAvailable"  class="col-xs-1">Available Qstns</label><div class="col-xs-1"><input type="text" id="moduleAvailQstns_' + counter + '" class="form-control" disabled="true"></div>';
                            var moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control"></div>';
                            var moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control"></div>';
                            var moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox"></div></div>';
                            moduleRowDiv = categoryDiv + moduleDiv + moduleQstnsAvailDiv + moduleMarksDiv + moduleRequiredQstnsDiv + moduleAdditionalDiv;
                        });
                        moduleCompleteDiv = moduleCompleteDiv + moduleRowDiv;
                    }else{
                      moduleRowDiv = "";
                      var categoryDiv = '<div class="form-group"><div class="col-xs-2"><input id="category_' + counter + '" class="form-control" type="text" 	value="' + option.category + '" disabled="true"></div>';
                      var moduleDiv = '<div class="col-xs-2"><input id="module_' + counter + '" class="form-control" type="text" value="No Module Found" disabled="true"></div>';
                      var moduleQstnsAvailDiv = ' <label for="qstnsAvailable"  class="col-xs-1">Available Qstns</label><div class="col-xs-1"><input type="text" id="moduleAvailQstns_' + counter + '" class="form-control" disabled="true"></div>';
                      var moduleMarksDiv = '<label for="marks"  class="col-xs-1">Module Marks</label><div class="col-xs-1"><input type="text" id="moduleMarks_' + counter + '" class="form-control"></div>';
                      var moduleRequiredQstnsDiv = '<label for="moduleQstns"  class="col-xs-1">Questions required in Exam</label><div class="col-xs-1"><input type="text" id="moduleReqQstns_' + counter + '" class="form-control"></div>';
                      var moduleAdditionalDiv = '<label for="addModule" class="col-xs-1">Additional</label><div class="col-xs-1"><input id="moduleAdditional-checkbox_' + counter + '" type="checkbox"></div></div>';
                      moduleRowDiv = categoryDiv + moduleDiv + moduleQstnsAvailDiv + moduleMarksDiv + moduleRequiredQstnsDiv + moduleAdditionalDiv;
                      moduleCompleteDiv = moduleCompleteDiv + moduleRowDiv;
                    }
                    counter++;
                });
                // Displat module Rows
                $('#showModuleDiv').html(startDiv + moduleCompleteDiv + endDiv);
            }
        });

    }

    $("#showModules-checkbox").change(function() {
        if (this.checked) {
            //  displayModuleSection();
            $('#showModuleDiv').removeClass('hide');
            //Do stuff
        } else {
            $('#showModuleDiv').addClass('hide');
        }
    });

    $(function() {
        $('#startDate').datetimepicker();
        $('#endDate').datetimepicker();
    });

    $("#sscButton").click(function() {
        var selText = $(this).text();
        loadJobRoles("");
    });

    $("#sscdropdownButton").click(function() {
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
        // control.replaceWith(control.val('').clone(true));
    });
    $('#ssctest-dropdown-menu').on('click', 'li a', function() {
        $('#jobroletest-dropdown-menu').children().remove();
        $('#jobroledropdownButton').html("-Select JobRole-" + '<span class="caret"></span>');
        sscValue = $(this).text();
        var selText = $(this).text();
        $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
        loadJobRoles(selText);
        $('#createExamForm').addClass('hide');
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
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        $('#createExamForm').removeClass('hide');
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
        if ($("#examNameText").val() === '') {
            alert("Please enter Exam Name !");
            return false;
        }
        if ($("#noOfQstnsText").val() === '') {
            alert("Please enter Number of Questions!");
            return false;
        }
        if ($("#noOfQstnsText").val() === '') {
            alert("Please enter Number of Questions!");
            return false;
        }

        if ($("#examInstArea").val() === '') {
            alert("Please enter Exam Instruction!");
            return false;
        }

        if (examDurationValue === '') {
            alert("Please enter Duration time !");
            return false;
        }
        if (attemptCountValue === '') {
            alert("Please enter Attempt Count !");
            return false;
        }

        var startDate = $("#startDate").find("input").val();
        if (startDate === '') {
            alert("Please enter Exam Start Date !");
            return false;
        }

        var endDate = $("#endDate").find("input").val();
        if (endDate === '') {
            alert("Please enter Exam End Date !");
            return false;
        }
        if ($("#selGroupDropdown").val() === 'None Selected') {
            alert("Please enter Exam Batch Id !");
            return false;
        }
        if (passingPercentValue === '') {
            alert("Please enter Passing Percent!");
            return false;
        }

        return true;
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
            var raf = $('#rafRadio input:radio:checked').val();
            // alert('test');
            $.ajax({
                url: '/assessment/php/manageExams.php',
                data: {
                    action: "create",
                    subjectName: subjectName,
                    qpCode: qpCode,
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
                    raf: raf,
                    pp: passingPercentValue
                },
                dataType: 'json', //since you wait for json
                success: function(data) {
                    if (typeof data.error === 'undefined') {
                        // Success so call function to process the form
                        console.log('SUCCESS: ' + data.success);
                        $('.alert-danger').removeClass('alert-danger').addClass('alert-success');
                        $('#error_msg').addClass('in');
                        $('#error_msg strong').text("Success! " + data.success);
                        /* Get from database using jax request*/
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
        }
    });


    // load Exams table.

    showExams();

    function showExams() {
        var subjectId = $("#qpcodeText").val();
        var examTable = $('#examTable').DataTable({
            "ajax": {
                'url': '/assessment/php/manageExams.php',
                'data': {
                    get: 'exams',
                    subid: subjectId
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                        $('#qstns').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Questions Found for selected Subject in Database.</strong></div>');
                        json.data = [];
                    } else {
                        $('#error_msg').addClass('hide');
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
