$(document).ready(function() {

  var APIKEY = "";
  // Bind Enter Key to 'SSP Submit Button'
  $(document).keypress(function(e) {
      if (e.which == 13) {
          $("#loginButton").click();
      }
  });


    $("#loginButton").click(function() {
      if($('#login-username').val()!=="" && $('#login-password').val()!==""){
        $('#loginButton').html('Signing in...');
              checkAuthentication();
          }
    });

    $(function() {
       $(document).on('click', '#login-alert', function() {
           $(this).hide();
       });
    });



    $("#loginform").submit(function(e) {
        e.preventDefault();
    });


    function checkAuthentication(){
      var user_name = $('#login-username').val();
      var password  =  $('#login-password').val();

      var wherClause = '{"emp_code":"'+user_name+'","password":"'+password+'"}';

      $.ajax({
          type: 'POST',
          url: '/assessment/php/AuthenticateUser.php',
          'data': {
              action: 'login',
              login: user_name,
              pwd: password,
            },
          dataType: 'json', //since you wait for json
          success: function(json) {
              //now when you received json, render options
              var counter = 0;
              var name = "";
              var role= "";
              if (typeof json.error === 'undefined') {
                window.location.href = "index.php";
              }else{
                  //alert("Invalid Login");
                  $('#login-alert').css('display', 'inline-block');
                  $('#login-alert').addClass('in');
                  $('#login-alert strong').text("UnAuthorized Access : Invalid username or password !");

                  $('#errorMessageText').text("UnAuthorized Access : Invalid username or password !");
                  $('#errorMessage').modal('show');
                  $('#loginButton').html('Login');
                }

                  /*
              $.each(json.employees, function(i, option) {
                username=option.name;
                id=option.id;
                counter++;
              });
                if(counter===0){
                  // e.preventDefault();
                }else{
                  window.location.href = "fillSSP.php?id="+id;
                }
                */
          },
          'error': function(jqXHR, textStatus, errorThrown) {
              // Handle errors here
              console.log('ERRORS: ' + errorThrown);
              $('#login-alert').css('display', 'inline-block');
              $('#login-alert').addClass('in');
              $('#login-alert strong').text("Invalid response from Server , Please check arrow server is running !! Error : " + errorThrown);
              $('#errorMessageText').text("Invalid response from Server , Please check arrow server is running.!!");
              $('#errorMessage').modal('show');
              $('#loginButton').html('Login');
              //e.preventDefault();
          },
      });
    }
});
