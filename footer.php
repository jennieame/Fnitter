<!-- Sidfot och JS / JQuery /-->

<div id="footer">
	<a href="#menu">Till Toppen!</a>
</div>

<a href="#" id="smoothup" alt="Back to top"></a>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>

//--- Visa eller dölj kommentarer ---//
$(document).ready(function() {
  $('.comments').hide();

  	$('body').on('click', '.hide', function() {
			$(this).parent().find('.comments').hide();
			$(this).addClass('show').removeClass('hide').text('Visa kommentarer');
		});

  	$('body').on('click', '.show', function() {
  		$(this).parent().find('.comments').show();
			$(this).addClass('hide').removeClass('show').text('Dölj kommentarer');
		});
  });

//---Visa eller Dölj Redigering-text ----//
$(document).ready(function() {
  $('.editPost').hide();

     $('body').on('click', '.showEdit', function() {
        $(this).parent().find('.editPost').show();
        $(this).addClass('hideEdit').removeClass('showEdit');
     });

     $('body').on('click', '.hideEdit', function() {
        $(this).parent().find('.editPost').hide();
        $(this).addClass('showEdit').removeClass('hideEdit');
     });
  });

//---Visa och dölj följare ( fnittrare.php )---/
$(document).ready(function(){
  $(".showUsers").click(function(){
    $("#allaFnittrare").hide();
    $(".showUsers").hide();
    $(".hideUsers").show();
  });

  $(".hideUsers").click(function(){
    $("#allaFnittrare").show();
     $(".hideUsers").hide();
    $(".showUsers").show();
  });
});

//--- Back to top /\ ---//
$(document).ready(function(){

  $(window).scroll(function(){
    if($(this).scrollTop()){
      $('#smoothup').fadeIn();
    }
    else{
      $('#smoothup').fadeOut();
    }
  });

});

</script>