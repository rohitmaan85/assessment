$(document).ready(function() {
  var examname = "rohit_1234";


  $('#exportExamAsPDF').click(function() {
    window.open("./php/exportExamQuestion.php?examname="+examname);
  });

});
