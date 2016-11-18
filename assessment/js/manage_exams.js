$(document).ready(function() {

  $(function () {
      $('#startDate').datetimepicker();
      $('#endDate').datetimepicker();
  });

  $("#subNameButton").click(function() {
      var selText = $(this).text();

      loadJobRoles("");
      /*
      $('#subNameButton').html(selText + '<span class="caret"></span>');
        for (i=1;i<=5;i++){
         var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#subname-dropdown-menu');
         //i=i+5;
      }*/
  });

  $('#subname-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      $('#subNameButton').html(selText + '<span class="caret"></span>');
      loadQpCode(selText);
  });

  function loadJobRoles(sscValue) {
      $.ajax({
          url: '/assessment/php/getSubjectDetails.php',
          data: { get: "jobrole", ssc: sscValue },
          dataType: 'json', //since you wait for json
          success: function(json) {
              //showQuestions();
              // Clear dropdown
              $('#subname-dropdown-menu').children().remove();
              //now when you received json, render options
              $.each(json, function(i, option) {
                  var rendered_option = '<li><a href="#">' + option + '</a></li>';
                  $(rendered_option).appendTo('#subname-dropdown-menu');
              });
          }
      });
  }


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
              //showQuestions();
              //questionsTable.ajax.reload();
            //  questionsTable.ajax.url( '/assessment/php/manageQuestions.php?get=questionssubId='+ "test1" ).load();
          }
      });
  }


  $("#examDurButton").click(function() {
      var selText = $(this).text();
      $('#examDurButton').html(selText + '<span class="caret"></span>');
        for (i=5;i<=100;i++){
         var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#examdur-dropdown-menu');
         i=i+4;
      }
  });

  $('#examdur-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      $('#examDurButton').html(selText + ' Minutes <span class="caret"></span>');
  });


  $("#passPercentButton").click(function() {
      var selText = $(this).text();
      $('#passPercentButton').html(selText + '<span class="caret"></span>');
        for (i=30;i<=75;i=i+5){
          var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#passpercent-dropdown-menu');
         //i=i+5;
      }
  });

  $('#passpercent-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      $('#passPercentButton').html(selText + '    % <span class="caret"></span>');
  });

  $("#atmptCountButton").click(function() {
      var selText = $(this).text();
      $('#atmptCountButton').html(selText + '<span class="caret"></span>');
        for (i=1;i<=5;i++){
         var rendered_option = '<li><a href="#">' + i + '</a></li>';
         $(rendered_option).appendTo('#atmptCount-dropdown-menu');
         //i=i+5;
      }
  });

  $('#atmptCount-dropdown-menu').on('click', 'li a', function() {
      var selText = $(this).text();
      $('#atmptCountButton').html(selText + ' Attempt<span class="caret"></span>');
  });

});
