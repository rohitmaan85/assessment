$(document).ready(function() {

    var sscValue = "";
    var subId = "";
    var category = "";
    var module = "";
    var quesstionsTable ="";
    var catId = "";
    var moduleId = "";

    showQuestions("","","","");

    $("td.myrow").mouseenter(function() {
        $(this).attr("Question", $(this).html());
    });

    showSSC();
    function showSSC(){
  //  $("#sscdropdownButton").click(function() {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: {
                get: "ssc"
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                // Clear dropdown
                $('#ssc-dropdown-menu').children().remove();
                //  $('#ssc-dropdown-menu').children().remove();
                $('#cat-dropdown-menu').children().remove();
                $('#module-dropdown-menu').children().remove();

                $('#createModuleModalButton').prop('disabled', true);
                //now when you received json, render options
                $.each(json, function(i, option) {
                    var rendered_option = '<li><a href="#">' + option + '</a></li>';
                    $(rendered_option).appendTo('#ssc-dropdown-menu');
                });
            }
        });
        // control.replaceWith(control.val('').clone(true));
    //});
  }

  $('#ssc-dropdown-menu').on('click', 'li a', function() {
      sscValue = $(this).text();
      var selText = $(this).text();
      showQuestions(sscValue,"","","");
        $('#manageQstnForm').addClass('hide');
      $('#sscdropdownButton').html(selText + '<span class="caret"></span>');
      //console.log($(this).text());
      //  $('#jobrole-dropdown-menu').remove();
      // Reset Button Value
      $('#jobroledropdownButton').html("Select Job Role" + '<span class="caret"></span>');
      // Reset Cateory
      $('#catButton').html("-Select Cateory-" + '<span class="caret"></span>');
      // Reset Module
      $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
      $('#cat-dropdown-menu').children().remove();
      $('#module-dropdown-menu').children().remove();

      $('#createModuleModalButton').prop('disabled', true);
      //    $('jobroledropdownButton').val("Select Job Role");
      loadJobRoles();
  });



  // Click on Jobrole DropDown Item.
  $('#jobrole-dropdown-menu').on('click', 'li a', function() {
      $('#manageQstnForm').removeClass('hide');
      subId = $(this).attr('id');
      showCategory();
      showQuestions(sscValue,subId,"","");
      $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
      // Reset Cateory
      $('#catButton').html("-Select Category-" + '<span class="caret"></span>');
      // Reset Module
      $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
      $('#createModuleModalButton').prop('disabled', true);
  });


  function loadJobRoles() {
      $.ajax({
          url: '/assessment/php/getSubjectDetails.php',
          data: {
              get: "jobroleWithQPCode",
              ssc: sscValue
          },
          dataType: 'json', //since you wait for json
          success: function(json) {
              // Clear dropdown
              $('#jobrole-dropdown-menu').children().remove();
              // Reset Cateory
              $('#catButton').html("-Select Category-" + '<span class="caret"></span>');
              // Reset Module
              $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');

              $('#createModuleModalButton').prop('disabled', true);
              //now when you received json, render options
              var counter = 0;
              $.each(json.job_role, function(i, option) {
                  var rendered_option = '<li><a href="#" id="' + json.qp_code[counter] + '">' + option + '  (' + json.qp_code[counter] + ' )</a></li>';
                  $(rendered_option).appendTo('#jobrole-dropdown-menu');
                  counter++;
              });
          }
      });
  }

  function showQuestions(sscValue,subId,category,module) {
           quesstionsTable = $('#qstns').DataTable({
             "oLanguage": {
             "sEmptyTable": '<strong>No Questions Available for this Subject \\ category \\ Module  !</strong>'
             },
           // serverSide: true,
           initComplete : function () {
            quesstionsTable.buttons().container()
                              .appendTo( '#qstns_wrapper .col-sm-6:eq(0)' );
          },

           buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
           "ajax": {
                'url': '/assessment/php/manageQuestions.php',
                'data': {
                    get: 'questions',
                    ssc:sscValue,
                    subId: subId,
                    category: catId,
                    module: moduleId,
                },
                'dataSrc': function(json) {
                    if (!json.data) {
                       //  $('#qstns').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Questions Found for selected Subject in Database.</strong></div>');
                        json.data = [];
                    } else {
                        $('#error_msg').addClass('hide');
                    }
                    return json.data;
                },
            },
            /* "scrollY": "200px",
            "paging": false, */
            'columnDefs': [ {
                    "targets": 6,
                    "data": null,
                    "defaultContent": "<button id=\"openQuestion\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Edit</button>  <button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-danger btn-xs\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>"
                },

            ],
            "destroy": true,
        });
    }




    function showCategory() {
        $.ajax({
            type: 'POST',
            url: '/assessment/php/manageCategory.php',
            data: {
                action: "getCategories",
                subId: subId
            },
            dataType: 'json', //since you wait for json
            success: function(json) {
                $('#cat-dropdown-menu').children().remove();
                $('#createModuleModalButton').prop('disabled', true);
                //now when you received json, render options
                var counter = 0;
                $.each(json.ids, function(i, cat_id) {
                  var rendered_option = '<li><a href="#" id="' + cat_id + '">' + json.category[counter] + '</a></li>';
                  $(rendered_option).appendTo('#cat-dropdown-menu');
                    counter++;
                });
            }
        });
    }


    function loadModules() {
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
                //showQuestions();
                // Clear dropdown
                //$('#catButton').html(selText + '<span class="caret"></span>');
                $('#module-dropdown-menu').children().remove();
                //now when you received json, render options
                var counter = 0;
                $.each(json.ids, function(i, mod_id) {
                  var rendered_option = '<li><a href="#" id="' + mod_id + '">' + json.module[counter] + '</a></li>';
                    $(rendered_option).appendTo('#module-dropdown-menu');
                      counter++;
                });
            }
        });
    }





    // Click on Category DropDown Button.
    $("#catButton").click(function() {

    });


    $('#cat-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
        category = $(this).text();
        catId = $(this).attr("id");
        // Refresh Questions
        showQuestions(sscValue,subId,category,"");
        // Reset Module
        $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
        $('#createModuleModalButton').prop('disabled', false);

        $('#catButton').html(selText + '<span class="caret"></span>');
        // Reset Button Value
        $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
        loadModules($(this).text());
    });



    $('#module-dropdown-menu').on('click', 'li a', function() {
        module =  $(this).text();
        moduleId = $(this).attr("id");
        var selText = $(this).text();
        $('#moduleButton').html(selText + '<span class="caret"></span>');
        showQuestions(sscValue,subId,category,module);
    });


    $('#newCatText').on('keyup', function(e) {
        if ($('#newCatText').val() === "")
            $('#createCat').prop('disabled', true);
        else {
            $('#createCat').prop('disabled', false);
        }
    });

    $('#newModuleText').on('keyup', function(e) {
        if ($('#newModuleText').val() === "")
            $('#createMod').prop('disabled', true);
        else {
            $('#createMod').prop('disabled', false);
        }
    });


    $("#createCat").click(function() {
        $.ajax({
            url: '/assessment/php/manageCategory.php',
            data: {
                action: "createCat",
                subId: subId,
                category: $('#newCatText').val()
            },
            dataType: 'json', //since you wait for json
            success: function(data) {
                alert(data.message);
                showCategory();
            },
            error: function(data) {
                // Handle errors here
                console.log('ERRORS: ' + data);
                //alert(data.message);
                // STOP LOADING SPINNER
            }
        });
    });



    $('#moduleModal').on('show.bs.modal', function() {
        $("#selected_category").val(category);
    });

    $("#createMod").click(function() {
        $.ajax({
            url: '/assessment/php/manageCategory.php',
            data: {
                action: "createCat",
                subId: subId,
                category: category,
                module: $('#newModuleText').val()
            },
            dataType: 'json', //since you wait for json
            success: function(data) {
                alert(data.message);

            },
            error: function(data) {
                // Handle errors here
                console.log('ERRORS: ' + data);
                // STOP LOADING SPINNER
            }
        });
    });






   $('#qstns tbody')
       .on( 'click', 'td', function () {
           var colIdx = quesstionsTable.cell(this).index().column;
          // if(colIdx==5){
          if(colIdx==5 || colIdx==6 || colIdx==7 || colIdx==8){
               var data = $('#qstns').DataTable().row($(this).parents('tr')).data();
               $('#qstnCompleteVal').val(data[colIdx]);
               $('#displayQstnModal').modal('show');
             }
          //  }
       } );

    $('#qstns tbody').on('click', 'button', function() {
        //console.log(testTable.row($(this).parents('tr')).data());
        var data = $('#qstns').DataTable().row($(this).parents('tr')).data();
        window.open("createQuestionPage.php?action=edit&qstnid=" + data[13]);
    });


});
