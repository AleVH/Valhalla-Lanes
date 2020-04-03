let base_url = window.location.origin;

$(document).ready(function(){

	console.log('loading Valhalla-Lanes admin javascript');
	let content_wrapper = $('.content');
	console.log('base url: ' + base_url);

	// Sidebar Section - This checks what button was clicked
	$('.sidebar__item').on('click', function (e) {
		let classes = $(this).attr('class');
		let loadSection = classes.split(" ");
		$('.sidebar__item').removeClass('selected');
		$('.sidebar__item.' + loadSection[1]).addClass('selected');
		console.log(loadSection[1] + ' section clicked');
		e.preventDefault();
		$.ajax({
			method: "POST",
			url: base_url + "/admin/" + loadSection[1],
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
			// this is to bind the the section with the correct scripts
			binder[loadSection[1]]();
		});
	});

	// //clicks behaviour
	// // Upload Section
	// $('form.admin_files').submit(function(e){
	// 	console.log('upload file clicked');
	// 	e.preventDefault();
	// 	e.stopPropagation();
	// 	// $.ajax({
	// 	// 		method : "POST",
	// 	// 		url : base_url + "/CodeIgniter-3.1.11-test/index.php/upload/doupload",
	// 	// 		dataType : "json"
	// 	// 	}
	// 	// ).done(function (response) {
	// 	// 	if(response.status === 'success'){
	// 	// 		console.log('success response');
	// 	// 		window.location.replace(response.message);
	// 	// 	}else{
	// 	// 		console.log('error response');
	// 	// 		// this is for when something is wrong with the credentials
	// 	// 		// $('.admin__login.error').html(response.message).css({'display' : 'block', 'opacity' : '1', 'color' : 'red'});
	// 	// 		// $('.admin__login.error').fadeOut(3200, function(){
	// 	// 		// 	$(this).html('&nbsp;').css('display', '');
	// 	// 		// });
	// 	// 	}
	// 	// });
	// });

});

let binder = {
	bookings : function(){
		console.log('bookings scripts binded');
	},
	events : function(){
		console.log('events scripts binded');
	},
	frontmenu : function(){
		console.log('front menu scripts binded');
		$(".front-menu__content li input").click(function(){
			let is_enabled = 0;
			let menu_section_id = $(this).val();
			let server_response, font_color;
			if ($(this).is(":checked")) {
				// it is checked
				is_enabled = 1;
			}
			$.ajax({
					method : "POST",
					url : base_url + "/menusections/updatestatus",
					data: {
						'id':menu_section_id,
						'is_enabled':is_enabled
					},
					dataType : "json"
				}
			).done(function (response) {
				if(response.status === 'success'){
					server_response = 'Updated';
					font_color = 'green';
				}else{
					server_response = "Error updating. Please refresh the page";
					font_color = 'red';
				}
			}).fail(function(response){
				server_response = "Error connecting to server. Please refresh the page";
				font_color = 'red';
			}).always(function(){
				// this will execute no matter what
				$("#menu-section-" + menu_section_id + " span").html(server_response).css('color', font_color);
				$("#menu-section-" + menu_section_id + " span").fadeOut(1200, function(){
					$(this).html('').css('display', '');
				});
			});
		});
	},
	frontsections : function(){
		console.log('front sections scripts binded');
	},
	gallery : function(){
		console.log('gallery scripts binded');
	},
	merchandise : function(){
		console.log('merchandise scripts binded');
	},
	metrics : function(){
		console.log('metrics scripts binded');
	},
	news : function(){
		console.log('news scripts binded');

		$('form.admin_news').submit(function(e){
			e.preventDefault();
			// e.stopPropagation();
			let title = $('form.admin_news input[name="news_title"]').val();
			let text = $('form.admin_news textarea[name="news_text"]').val();
			$.ajax({
				method: 'POST',
				url: base_url + '/news/savenews',
				data: {
					title: title,
					text: text
				},
				dataType: 'json'
			}).done(function(response){
				if(response.status === 'success'){
					console.log('success response');
					let visibility, checkbox;
					if(response.message.is_enabled === true){
						visibility = 'enabled-news';
						checkbox = 'checked';
					}else{
						visibility = "disabled-news";
						checkbox = '';
					};
					let newNews = '<div id="news-' + response.message.id + '" class="news__wrapper ' + visibility + '">'+
						'<div class="news__header">' +
						'<div class="news__header-firstline">' +
						'<div class="news__date">' + response.message.created + '</div>' +
						'<div class="news__title">' + response.message.news_title + '</div>' +
						'<div class="news__visibility"><span class="news__update-status"></span><input type="checkbox" name="news__checkbox" value="' + response.message.id + '" ' + checkbox + '></div>' +
						'</div>' +
						'<div class="news__header-secondline">' +
						'<div class="news__author">' + response.message.author + '</div>' +
						'</div>' +
						'</div>' +
						'<div class="news__text">' +
						'<p>' + response.message.news_text + '</p>' +
						'</div>' +
						'</div>';

					// append the new news to the beginning of the list
					$(".news__articles-all").prepend(newNews);

					// empty title and text area
					$('form.admin_news input[name="news_title"]').val('');
					$('form.admin_news textarea[name="news_text"]').val('');

					// bin the new news to its script
					commonLayerBinder['news']();

				}else{
					console.log('error response');
				}
			})
		});

		commonLayerBinder['news']();

	},
	others : function(){
		console.log('others scripts binded');
	},
	promotions : function(){
		console.log('promotions scripts binded');
	},
	rankings : function(){
		console.log('ranking scripts binded');
	},
	rules : function(){
		console.log('rules scripts binded');
	},
	socialmedia : function(){
		console.log('social media scripts binded');
	},
	upload : function(){
		console.log('upload scripts binded');
		let content_wrapper = $('.content');
		// let base_url = window.location.origin;

		// Miscellaneous
		$('.admin_files .add_more').on('click', function(e){
			console.log('add more files clicked');
			// e.stopPropagation();
			e.preventDefault();
			$(this).before("<input type='file' name='files[]' multiple/>");
		});

		$('form.admin_files').submit(function(e){
			console.log('upload file clicked');
			e.preventDefault();

			// create the formdata element
			let formdata = new FormData();
			// get all the files attached
			let totalfiles = $("input[name^='files']")[0].files.length;

			for (let index = 0; index < totalfiles; index++) {
				formdata.append("files[]", $("input[name^='files']")[0].files[index]);
			}
			formdata.append("upload",true);

			console.log('formdata: ', totalfiles);

			$.ajax({
					method : "POST",
					url : base_url + "/upload/doupload",
					data: formdata,
					processData: false,
					contentType: false,
					dataType : "json"
				}
			).done(function (response) {
				if(response.status === 'success'){
					console.log('success response');
					window.location.replace(response.message);
				}else{
					console.log('error response');
					// this is for when something is wrong with the credentials
					// $('.admin__login.error').html(response.message).css({'display' : 'block', 'opacity' : '1', 'color' : 'red'});
					// $('.admin__login.error').fadeOut(3200, function(){
					// 	$(this).html('&nbsp;').css('display', '');
					// });
				}
			});
		});
	}
};

let commonLayerBinder =  {
	news: function(){
		console.log('news second layer binder');
		$(".admin__news .news__articles .news__articles-all .news__wrapper input[name='news__checkbox']").click(function(){
			console.log('news checkbox clicked second binder');
			let news_id = $(this).val();
			let news_visibility = 0;
			let server_response, font_color;
			if($(this).is(":checked")){
				news_visibility = 1;
			}
			$.ajax({
				method: 'POST',
				url: base_url + '/news/updatenewsstatus',
				data: {
					id: news_id,
					is_enabled: news_visibility
				},
				dataType: 'json'
			}).done(function (response) {
				if(response.status === 'success'){
					server_response = 'Updated';
					font_color = 'black';
				}else{
					server_response = "Error updating. Please refresh the page";
					font_color = 'black';
				}
			}).fail(function(response){
				server_response = "Error connecting to server. Please refresh the page";
				font_color = 'black';
			}).always(function(){
				// this will execute no matter what
				$("#news-" + news_id + " .news__visibility span").html(server_response).css('color', font_color);
				$("#news-" + news_id + " .news__visibility span").fadeOut(1200, function(){
					$(this).html('').css('display', '');
					if(news_visibility){
						$("#news-" + news_id).removeClass('disabled-news');
						$("#news-" + news_id).addClass('enabled-news');
					}else{
						$("#news-" + news_id).removeClass('enabled-news');
						$("#news-" + news_id).addClass('disabled-news');
					}
				});
			});
		});
	}
};
