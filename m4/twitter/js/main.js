
var User = {
	handle: '@someguy',
	img: 'images/brad.png'
}

var keyPressCount = 0;
var charsLeft = 140;


function renderTweet(u, message) {
	var source = $('#template-tweet').html();
	var template = Handlebars.compile(source);
	var product = {'title': User.handle, 'tweet': message, 'image': User.img}
	var compiled_product = template(product);
	return compiled_product;
}

function renderCompose() {
	return $('#template-compose').html();
}

function renderThread(u, message) {
	var source = $('#template-thread').html();
	var template = Handlebars.compile(source);
	var product = {'tweet': renderTweet(u, message), 'compose': renderCompose()}
	var compiled_product = template(product);
	return compiled_product;
}

$(document).ready(function () {

	$('body').on('click', 'textarea', function() {
		$(this).parents('form').addClass('expand');
	});


	$('.tweets').on('click', '.tweet', function() {
		$(this).parents('.thread').toggleClass('expand');
	});


	$('body').on('submit', 'form', function(event) {
		event.preventDefault();
		var newtweet = $(this).find('textarea').val();
		// don't have to have length > 0, since it will be true with any value
		if ($(this).parents('header').length) {
			$('.tweets').append(renderThread(User, newtweet));
		} else {
			$(this).parent().append(renderTweet(User, newtweet));
		}
		$(this).find('textarea').val('');
		$(this).removeClass('expand');
  	});

	$('body').on('keydown keyup', 'textarea', function(e){
		keyPressCount = $(this).val().length;
		console.log(keyPressCount);
		console.log($(this).val().length);
		$(this).siblings().find('span.count').text(charsLeft-keyPressCount);
	});

});
