/*menu handler*/

$(window).load(function() {
    $(".loader").fadeOut("slow");
});

$(function() {

    $(document).ready(function() {

			// generate PDF
        $('#cmd').click(function() {
            /*
            var doc = new jsPDF();
            doc.text(20, 20, 'Hello world!');
            doc.text(20, 30, 'This is client-side Javascript, pumping out a PDF.');
            doc.addPage();
            doc.text(20, 20, 'Do you like that?');
						

            var specialElementHandlers =
                function(element, renderer) {
                    return true;
                };
            var doc = new jsPDF();
            doc.fromHTML($('#target').html(), 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });
            // Output as Data URI
            //doc.output('datauri');
            doc.output('save', 'filename.pdf');*/

        });

        /*
                $('#cmd').click(function() {
                    var specialElementHandlers =
                        function(element, renderer) {
                            return true;
                        };
                    var doc = new jsPDF();
                    doc.fromHTML($('#target').html(), 15, 15, {
                        'width': 170,
                        'elementHandlers': specialElementHandlers
                    });
                  	doc.output('dataurlnewwindow');
                });
        				*/

        /* Activate data table with JS code*/

        /* Get from database using jax request*/




        /*
        $('#courses').DataTable({
            "ajax": '/assessment/php/data.json',
            "columns": [
                { "data": "fname" },
                { "data": "lname" },
                { "data": "pos" },
                { "data": "office" },
                { "data": "age" },
                { "data": "sdate" },
                { "data": "sal" },
                { "data": "ext" },
                { "data": "email" }
            ]
        });
        */

        /* Working Code widhout column name*/
        /*
        $('#courses').DataTable({
            "ajax": '/assessment/php/arrays.txt',
        });
        */

    });



    /*
        $(document).ready(function() {
            $('#courses').DataTable();
        });




        	  $('.nav li a').each(function(){

        	  var activePage = "";
        	  var currentPage = "";
        		if (activePage == currentPage) {
              $(this).parent().addClass('active');
            }
          });*/

    $(".nav a").on("click", function() {
        $(".nav").find(".active").removeClass("active");
        $(this).parent().addClass("active");
    });

    /*
        $('.navbar-toggle').click(function() {
            $('.navbar-nav').toggleClass('slide-in');
            $('.side-body').toggleClass('body-slide-in');
            $('#search').removeClass('in').addClass('collapse').slideUp(200);

            /// uncomment code for absolute positioning tweek see top comment in css
           //  $('.absolute-wrapper').toggleClass('slide-in');

        });


        // Remove menu for searching
        $('#search-trigger').click(function() {
            $('.navbar-nav').removeClass('slide-in');
            $('.side-body').removeClass('body-slide-in');

            /// uncomment code for absolute positioning tweek see top comment in css
            $('.absolute-wrapper').removeClass('slide-in');

        });
    */

    // Remove active for all items.
    $('.page-sidebar-menu li').removeClass('active');

    // highlight submenu item
    $('li a[href="' + this.location.pathname + '"]').parent().addClass('active');

    // Highlight parent menu item.
    $('ul a[href="' + this.location.pathname + '"]').parents('li').addClass('active');

});
