$(document).ready(function() {
  var examname = $('#getExamName').val();


  $('#exportExamAsPDF').click(function() {
    window.open("./php/exportExamQuestion.php?examname="+examname);
  });

  $('#exportEncryptedExam').click(function() {
    window.open("./php/exportEncryptedExam.php?examname="+examname);
  });

});
