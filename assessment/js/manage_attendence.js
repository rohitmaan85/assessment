  $(document).ready(function() {
      var sscValue = "";
      var subId = "";
      var batch_id = "";
      var row_batch_id = "";
      var center_id = "";
      var training_part = "";
      var status = "";

      showBatchDetails("", "", "", "", "", "");

      $("td.myrow").mouseenter(function() {
          $(this).attr("Question", $(this).html());
      });

      function showBatchDetails(sscValue, subId, batch_id, center_id, training_part, status) {
          quesstionsTable = $('#qstns').DataTable({
              "oLanguage": {
                  "sEmptyTable": '<strong>No Batch Information Available for selected SSC\JobRole  !</strong>'
              },
              // serverSide: true,
              initComplete: function() {
                  quesstionsTable.buttons().container()
                      .appendTo('#qstns_wrapper .col-sm-6:eq(0)');
              },

              buttons: ['copy', 'excel', 'pdf', 'colvis'],
              "ajax": {
                  'url': '/assessment/php/manageAttendence.php',
                  'type': 'POST',
                  'data': {
                      action: 'getBatchDetails',
                      subId: subId,
                      batch_id: batch_id,
                      center_id: center_id,
                      training_part: training_part,
                      status: status,
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
              'columnDefs': [{
                      "targets": 3,
                      "data": null,
                      "defaultContent": "<button id=\"openDetails\"  type=\"button\" class=\"btn btn-info btn-sm\" ><span class=\"glyphicon glyphicon-asterisk\"></span>Show Batch Details</button>  <button id=\"displayStudentsButton\"  type=\"button\" class=\"btn btn-danger btn-sm\" ><span class=\"glyphicon glyphicon-remove\"></span>  Show Students List</button>"
                  },

              ],
              "destroy": true,
          });
      }


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

      function showCategory() {
          $.ajax({
              url: '/assessment/php/manageCategory.php',
              data: {
                  get: "categories",
                  subId: subId
              },
              dataType: 'json', //since you wait for json
              success: function(data) {
                  $('#cat-dropdown-menu').children().remove();
                  $('#createModuleModalButton').prop('disabled', true);
                  //now when you received json, render options
                  var counter = 0;
                  $.each(data, function(i, option) {
                      var rendered_option = '<li><a href="#">' + option + '</a></li>';
                      $(rendered_option).appendTo('#cat-dropdown-menu');

                  });
              }
          });
      }


      function loadModules() {
          $.ajax({
              url: '/assessment/php/manageCategory.php',
              data: {
                  get: "modules",
                  subId: subId,
                  category: category
              },
              dataType: 'json', //since you wait for json
              success: function(data) {
                  //showBatchDetails();
                  // Clear dropdown
                  //$('#catButton').html(selText + '<span class="caret"></span>');
                  $('#module-dropdown-menu').children().remove();
                  //now when you received json, render options
                  var counter = 0;
                  $.each(data, function(i, option) {
                      var rendered_option = '<li><a href="#">' + option + '</a></li>';
                      $(rendered_option).appendTo('#module-dropdown-menu');
                  });
              }
          });
      }

      $("#sscdropdownButton").click(function() {
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
      });


      $('#ssc-dropdown-menu').on('click', 'li a', function() {
          sscValue = $(this).text();
          var selText = $(this).text();
          showBatchDetails(sscValue, "", "", "");
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
          $('#manageAttndnceForm').removeClass('hide');
          subId = $(this).attr('id');
          showCategory();
          showBatchDetails(sscValue, subId, "", "");
          $('#jobroledropdownButton').html($(this).text() + '<span class="caret"></span>');
          // Reset Cateory
          $('#catButton').html("-Select Category-" + '<span class="caret"></span>');
          // Reset Module
          $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
          $('#createModuleModalButton').prop('disabled', true);
      });

      // Click on Category DropDown Button.
      $("#catButton").click(function() {

      });


      $('#cat-dropdown-menu').on('click', 'li a', function() {
          var selText = $(this).text();
          category = $(this).text();
          // Refresh Questions
          showBatchDetails(sscValue, subId, category, "");
          // Reset Module
          $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
          $('#createModuleModalButton').prop('disabled', false);

          $('#catButton').html(selText + '<span class="caret"></span>');
          // Reset Button Value
          $('#moduleButton').html("-Select Module-" + '<span class="caret"></span>');
          loadModules($(this).text());
      });


      $('#module-dropdown-menu').on('click', 'li a', function() {
          module = $(this).text();
          var selText = $(this).text();
          $('#moduleButton').html(selText + '<span class="caret"></span>');
          showBatchDetails(sscValue, subId, category, module);
      });


      $('#moduleModal').on('show.bs.modal', function() {
          $("#selected_category").val(category);
      });

      $('#qstns tbody').on( 'click', '#openDetails', function () {
        var data = $('#qstns').DataTable().row($(this).parents('tr')).data();
        row_batch_id = data[0];
        getBatchInformationForDialog();
        $('#displayBatchDetailsModal').modal('show');
      } );

      $('#qstns tbody').on( 'click', '#displayStudentsButton', function () {
        var data = $('#qstns').DataTable().row($(this).parents('tr')).data();
        row_batch_id = data[0];
        $('#displayStudentsModal').modal('show');
        showStudentsList();
      } );


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
                      batch_id: row_batch_id
                  },
                  'dataSrc': function(json) {
                      if (!json.data) {
                          json.data = [];
                      } else {
                          $('#error_msg').addClass('hide');
                      }
                      return json.data;
                  },
              },
              "destroy": true,
          });
      }



      function getBatchInformationForDialog(){
            $.ajax({
                type: 'POST',
                url: '/assessment/php/manageAttendence.php',
                data: {
                    action: "getBatchInfo",
                    batch_id: row_batch_id
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
  });
