$(document).ready(function() {


 $('#newCatText').on('keyup', function (e) {
    if($('#newCatText').val() === "")
        $('#createCat').prop('disabled', true);
    else {
        $('#createCat').prop('disabled', false);
        }
 });

 $('#newModuleText').on('keyup', function (e) {
    if($('#newModuleText').val() === "")
        $('#createMod').prop('disabled', true);
    else {
        $('#createMod').prop('disabled', false);
        }
 });

  showQuestions();
  var subId = "";
  function showQuestions(qpCode){
      var quesstionsTable = $('#qstns').DataTable({
          "ajax": {
            'url' : '/assessment/php/manageQuestions.php',
            'data': {
                    get: 'questions',
                    subId: qpCode
                  },
           'dataSrc': function (json) {
                          if(!json.data){
                                    $('#qstns').html('<div id=\"error_msg\"  class=\"alert alert-danger fade in\" style=\"position:relative"><strong>No Questions Found for selected Subject in Database.</strong></div>');
                                    json.data = [];
                                }
                                else{
                                      $('#error_msg').addClass('hide');
                                }

                          return json.data;
                    },

          },
           /* "scrollY": "200px",
          "paging": false, */
          'columnDefs': [
              {
                 "targets": 1,
                 "bVisible": false,
                 "searchable": false
             },
                {
                      "targets": 8,
                      "data": null,
                      "defaultContent": "<button id=\"openQuestion\"  type=\"button\" class=\"btn btn-info btn-xs\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Edit</button>  <button id=\"uploadFileButton\"  type=\"button\" class=\"btn btn-danger btn-xs\" ><span class=\"glyphicon glyphicon-remove\"></span>Delete</button>"
                  },

                ],
          "destroy" : true,
        });
    }

    var subjectId="";
    $('#qpcodeText').prop('disabled', true);
    $("#sscdropdownButton").click(function() {
        $.ajax({
                url: '/assessment/php/getSubjectDetails.php',
                data: { get: "ssc" },
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
    });


    $('#ssc-dropdown-menu').on('click', 'li a', function() {
        var selText = $(this).text();
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
            loadJobRoles($(this).text());
    });

    function loadJobRoles(sscValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: { get: "jobroleWithQPCode", ssc: sscValue },
            dataType: 'json', //since you wait for json
            success: function(json) {
                showQuestions();
                // Clear dropdown
                $('#jobrole-dropdown-menu').children().remove();
                // Reset Cateory
                $('#catButton').html("-Select Category-" + '<span class="caret"></span>');
                // Reset Module
                $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');

                $('#createModuleModalButton').prop('disabled', true);
                //now when you received json, render options
                var counter = 0 ;
                $.each(json.job_role, function(i, option) {
                          var rendered_option = '<li><a href="#" id="'+ json.qp_code[counter]+'">' + option + '  (' +json.qp_code[counter] +' )</a></li>';
                          $(rendered_option).appendTo('#jobrole-dropdown-menu');
                          counter++;
                });
            }
        });
    }

    $('#jobrole-dropdown-menu').on('click', 'li a', function() {
        $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
        //console.log($(this).text());
        //subjectId=json.qpcode;
        subId=$(this).attr('id');
        // Reset Cateory
        $('#catButton').html("-Select Category-" + '<span class="caret"></span>');
        // Reset Module
        $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
        $('#createModuleModalButton').prop('disabled', true);
        showQuestions($(this).attr('id'));
       //  loadQpCode($(this).text());
    });

  $("#catButton").click(function() {
        showCategory();
  });

  function showCategory(){
    $.ajax({
        url: '/assessment/php/manageCategory.php',
        data: { get: "categories", subId: subId },
        dataType: 'json', //since you wait for json
        success: function(data) {
            //showQuestions();
            // Clear dropdown
            //$('#catButton').html(selText + '<span class="caret"></span>');
            $('#cat-dropdown-menu').children().remove();
            $('#createModuleModalButton').prop('disabled', true);
            //now when you received json, render options
            var counter = 0 ;
            $.each(data, function(i, option) {
                      var rendered_option = '<li><a href="#">' + option + '</a></li>';
                      $(rendered_option).appendTo('#cat-dropdown-menu');

            });
        }
    });
  }

var category = "";
$('#cat-dropdown-menu').on('click', 'li a', function() {
           category=$(this).text();
            // Reset Module
            $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
            $('#createModuleModalButton').prop('disabled', false);
            var selText = $(this).text();
            $('#catButton').html(selText + '<span class="caret"></span>');
                    //console.log($(this).text());
              //  $('#jobrole-dropdown-menu').remove();
              // Reset Button Value
              $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
            //    $('jobroledropdownButton').val("Select Job Role");
              loadModules($(this).text());
  });

  function loadModules(category){
      $.ajax({
          url: '/assessment/php/manageCategory.php',
          data: { get: "modules", subId: subId ,category:category},
          dataType: 'json', //since you wait for json
          success: function(data) {
              //showQuestions();
              // Clear dropdown
              //$('#catButton').html(selText + '<span class="caret"></span>');
              $('#module-dropdown-menu').children().remove();
              //now when you received json, render options
              var counter = 0 ;
              $.each(data, function(i, option) {
                        var rendered_option = '<li><a href="#">' + option + '</a></li>';
                        $(rendered_option).appendTo('#module-dropdown-menu');

              });
          }
      });
    }

$('#module-dropdown-menu').on('click', 'li a', function() {
                var selText = $(this).text();
                $('#moduleButton').html(selText + '<span class="caret"></span>');
  });


$("#createCat").click(function() {
  $.ajax({
      url: '/assessment/php/manageCategory.php',
      data: { action: "create",subId:subId,category: $('#newCatText').val() },
      dataType: 'json', //since you wait for json
      success: function(data) {
              alert(data.message);
              showCategory();
      },
      error: function(data) {
          // Handle errors here
          console.log('ERRORS: ' + data);
          // STOP LOADING SPINNER
      }
  });
});



$('#moduleModal').on('show.bs.modal',function(){
    $("#selected_category").val(category);
});

$("#createMod").click(function() {
  $.ajax({
      url: '/assessment/php/manageCategory.php',
      data: { action: "create",subId:subId, category:category,module: $('#newModuleText').val() },
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


/*

    $("#moduleButton").click(function() {
        var selText = $(this).text();
        $('#passPercentButton').html(selText + '<span class="caret"></span>');
          for (i=30;i<=75;i=i+5){
            var rendered_option = '<li><a href="#">' + i + '</a></li>';
           $(rendered_option).appendTo('#module-dropdown-menu');
           //i=i+5;
        }
    });
    */




/*
    function loadQpCode(jobroleValue) {
        $.ajax({
            url: '/assessment/php/getSubjectDetails.php',
            data: { get: "qpcode", jobrole: jobroleValue },
            dataType: 'json', //since you wait for json
            success: function(json) {
                // Clear dropdown
                //$('#qpcodeText').remove();
                $('#qpcodeText').val(json.qpcode);
                subjectId=json.qpcode;
                showQuestions();
                //questionsTable.ajax.reload();
              //  questionsTable.ajax.url( '/assessment/php/manageQuestions.php?get=questionssubId='+ "test1" ).load();
            }
        });
    }

*/




    $('#qstns tbody').on('click', 'button', function() {
        //console.log(testTable.row($(this).parents('tr')).data());
        var data =  $('#qstns').DataTable().row($(this).parents('tr')).data();
        window.open("createQuestionPage.php?action=edit&id="+data[0]+"&subid="+data[2]+"&qstnid="+data[1]);
    });


});
