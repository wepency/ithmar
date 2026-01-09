
function navigate(data, txt) {
    $("#pageContent").fadeOut(10);
    $("#pageContent").empty();

    $('#over').Wload({ text: 'Loading' })

   // $("#pageContent").empty();
    // $('.over').show();
   // alert(txt)
    $.ajax({
        type: 'GET',
        url: data,
        dataType:'html',
        xhrFields: {
        withCredentials: true
        },
         crossDomain: true,
        success: function (res, resText, xhr) {
          //alert( txt);
            $("#pageContent").fadeIn(2000);

            $('#txtLayoutHeader').text(txt);

            $("#pageContent").html(res);
            $("#over").hide();

          // loadingPlugin();
        },
        error: function () {

            console.log("Hello ")

        }
    });
}



function loadingPlugin() {

    $(".over .spinner").fadeOut(5000,
	function () {
	    $(this).parent().fadeOut(1000,
		function () {

		    $(this).remove();

		});
	});

}

