$(function(){
	$(window).load(function(){
		$('.preloader').show().delay(2000).fadeIn(function(){$(this).addClass('pull')});
	});
	$('[data-toggle="modal"]').click(function(){
		var el = $(this).data('target');

		if($('.overlay').hasClass('active')){
			document.getElementById("video").pause();
			$('.overlay').removeClass('active').hide();
		} else {
			$(el).show().addClass('active in');
		}
		setTimeout(function() { $(".inputbox[name=username]").focus(); }, 400);  
	});

	$('[data-toggle="modal-video"]').click(function(){
		var el = $(this).data('target');

		if($('.overlay').hasClass('active')){
			$('.overlay').removeClass('active').hide();
		} else {
			$(el).show().addClass('active in');
			document.getElementById("video").play();
		}
		setTimeout(function() { $(".inputbox[name=username]").focus(); }, 400);  
	});

	$('.overlay').click(function(){
		$(this).removeClass('active');
		document.getElementById("video").pause();
	});
	$('.overlay .box').click(function(e){
		e.stopPropagation();     
	});
	$('.overlay .close').click(function(){
		$(this).parents('.overlay').removeClass('active');
		document.getElementById("video").pause();
	});
});