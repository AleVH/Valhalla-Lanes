$(document).ready(function(){
	console.log('loading Valhalla-Lanes admin javascript');

	let content_wrapper = $('.content');
	let base_url = window.location.origin;

	// Metrics Section
	$('.sidebar__item.metrics').on('click', function (e) {
		console.log('metrics section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/dashboard",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Gallery Section
	$('.sidebar__item.gallery').on('click', function (e) {
		console.log('gallery section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/gallery",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Rules Section
	$('.sidebar__item.rules').on('click', function (e) {
		console.log('rules section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/rules",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Ranking Section
	$('.sidebar__item.rankings').on('click', function (e) {
		console.log('rankings section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/rankings",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Merchandise Section
	$('.sidebar__item.merchandise').on('click', function(e){
		console.log('merchandise section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/merchandise",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Upload Section
	$('.sidebar__item.upload').on('click', function(e){
		console.log('upload section clicked');
		e.preventDefault();
		$.ajax({
				method : "GET",
				url : base_url + "/CodeIgniter-3.1.11-test/index.php/admin/upload",
				dataType : "json"
			}
		).done(function (response) {
			console.log('response: ' + response);
			content_wrapper.html(response);
			// $('.content').html(response);
			bindUploadSectionScripts(); // this is crucial to make the javascript work
			if(response.status === 'success'){
				console.log('success response');
				// window.location.replace(response.message);
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

	// Promotions Section
	$('.sidebar__item.promotions').on('click', function (e) {
		console.log('promotions section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/promotions",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Bookings Section
	$('.sidebar__item.bookings').on('click', function (e) {
		console.log('bookings section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/bookings",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Social Media Setion
	$('.sidebar__item.social_media').on('click', function (e) {
		console.log('social media section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/socialmedia",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Events Section
	$('.sidebar__item.events').on('click', function (e) {
		console.log('events section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/events",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	// Others Section
	$('.sidebar__item.others').on('click', function (e) {
		console.log('others section clicked');
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: base_url + "/CodeIgniter-3.1.11-test/index.php/admin/others",
			dataType: "json"
		}).done(function(response){
			content_wrapper.html(response);
		});
	});

	//clicks behaviour
	// Upload Section
	$('form.admin_files').submit(function(e){
		console.log('upload file clicked');
		e.preventDefault();
		e.stopPropagation();
		// $.ajax({
		// 		method : "POST",
		// 		url : base_url + "/CodeIgniter-3.1.11-test/index.php/upload/doupload",
		// 		dataType : "json"
		// 	}
		// ).done(function (response) {
		// 	if(response.status === 'success'){
		// 		console.log('success response');
		// 		window.location.replace(response.message);
		// 	}else{
		// 		console.log('error response');
		// 		// this is for when something is wrong with the credentials
		// 		// $('.admin__login.error').html(response.message).css({'display' : 'block', 'opacity' : '1', 'color' : 'red'});
		// 		// $('.admin__login.error').fadeOut(3200, function(){
		// 		// 	$(this).html('&nbsp;').css('display', '');
		// 		// });
		// 	}
		// });
	});


});

let bindUploadSectionScripts = function(){

	let content_wrapper = $('.content');
	let base_url = window.location.origin;

	// Miscellaneous
	$('.admin_files .add_more').on('click', function(e){
		console.log('add more files clicked');
		// e.stopPropagation();
		e.preventDefault();
		$(this).before("<input type='file' name='files[]' multiple/>");
	});

	console.log('new js');
	$('form.admin_files').submit(function(e){
		console.log('upload file clicked');
		e.preventDefault();
		// e.stopPropagation();

		// create the formdata element
		let formdata = new FormData();
		// get all the files attached
		let totalfiles = $("input[name^='files']")[0].files.length;

		for (let index = 0; index < totalfiles; index++) {
			formdata.append("files[]", $("input[name^='files']")[0].files[index]);
		}

		console.log('formdata: ', totalfiles);
		$.ajax({
				method : "POST",
				url : base_url + "/CodeIgniter-3.1.11-test/index.php/upload/doupload",
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
