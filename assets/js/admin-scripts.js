let base_url = window.location.origin;

$(document).ready(function(){

	console.log('loading Valhalla-Lanes admin javascript');
	let content_wrapper = $('.content');
	// console.log('base url: ' + base_url);

	// Sidebar Section - This checks what button was clicked
	$('.sidebar__item').on('click', function (e) {

		let classes = $(this).attr('class');
		let loadSection = classes.split(" ");
		$('.sidebar__item').removeClass('selected');
		$('.sidebar__item.' + loadSection[1]).addClass('selected');
		// console.log(loadSection[1] + ' section clicked');
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

	// to logout a user
	$(".logout_user").click(function(e) {
		e.preventDefault();
		let confirmation = confirm("Are you sure you want to close you session?");
		if(confirmation){
			window.location.href = base_url + $(this).attr('href');
		}
	});

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
						'id' : menu_section_id,
						'is_enabled' : is_enabled
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
						'<div class="news__visibility"><span class="news__update-status"></span><input id="news-chbx-' + response.message.id + '" type="checkbox" name="news__checkbox" value="' + response.message.id + '" ' + checkbox + '></div>' +
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
			});
		});

		commonLayerBinder['news']();

	},

	others : function(){
		console.log('others scripts binded');
	},

	promotions : function(){
		console.log('promotions scripts binded');
	},

	ranking : function(){
		console.log('ranking scripts binded');

		// this bit controls the clicks on the tab
		$('.admin__rankings .tab').click(function(){
			$('.tab').removeClass('active');
			let tab_name = $(this).text().toLowerCase();
			// console.log('tab title is: ' + tab_name);
			$(this).addClass('active');
			$(".tab-content").removeClass('active');
			$(".tab-content.ranking-" + tab_name).addClass('active');
		});

		// this bit controls when the create form gets submitted
		$('form.admin_ranking-create').submit(function(e){
			e.preventDefault();
			console.log('submitting new ranking');
			// let theForm = $('form.admin_ranking-create').serialize();
			// console.log('serialization: ' + theForm);
			let formdata = new FormData($('form.admin_ranking-create').get(0));
			console.log(formdata);

			$.ajax({
				method: "POST",
				url: base_url + "/ranking/create",
				data: formdata,
				processData: false,
				contentType: false,
				dataType: "json"
			}).done(function (response) {
				console.log('response from server: ' + response);
				// i need to add the newly created ranking to the list of existing ones and bind the script so it can be edited, otherwise the page requires a refresh
				if(response.status === 'success'){
					// message of success
					$(".ranking-server-response").html("Successfuly Created").css('color','green');
					$(".ranking-server-response").fadeOut(3000, function(){
						$(this).html('');
					});

					let status_button = "Activate";
					let rank_status = "Inactive";
					let rank_end_date = "End Date not defined";
					let future_status = 1;
					if(response.message.is_enabled === 1){
						status_button = "Deactivate";
						rank_status = "Active";
						future_status = 0;
					}
					if(response.message.end_date !== null){
						rank_end_date = response.message.end_date;
					}

					// the created ranks are always inactive, they have to be activated from the "edit" tab
					let newRank = '<div class="ranking-wrapper item inactive" data-rank-id="' + response.message.id + '">' +
						'<div class="rank-title item">' + response.message.title + '</div>' +
						'<div class="rank-tops item">' + response.message.tops + '</div>' +
						'<div class="rank-start item">' + response.message.start_date + '</div>' +
						'<div class="rank-end item">' + rank_end_date + '</div>' +
						'<div class="rank-author item">' + response.message.author + '</div>' +
						'<div class="rank-status item">' + rank_status + '</div>' +
						'<div class="rank-actions item"><button id="rank-' + response.message.id + '-stat" type="button" class="status-modifier active" future-rank-stat="1">' + status_button + '</button><button class="rank-delete" value="' + response.message.id + '">Delete</button></div>' +
						'</div>';

					if(!$(".ranking-wrapper").hasClass("head")){
						$(".ranking-wrapper").html('');
						$(".ranking-wrapper").addClass("head");
						// add head fields
						let head_fields = '<div class="rank-title">Title</div>' +
							'<div class="rank-tops">Tops</div>' +
							'<div class="rank-start">Start Date</div>' +
							'<div class="rank-end">End Date</div>' +
							'<div class="rank-author">Author</div>' +
							'<div class="rank-status">Status</div>' +
							'<div class="rank-actions">Actions</div>';
						$(".ranking-wrapper").html(head_fields);
					}
					// this inserts the newly created rank right after the head from the table so the newest is always at the top
					$(newRank).insertAfter(".ranking-wrapper.head");

					commonLayerBinder['ranking']();
				}
			});

		});

		commonLayerBinder['ranking']();
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
	},

	users : function(){
		console.log('users scripts binded');
		specializedLayerBinder.users["updateUsersNumber"]();

		// this bit controls the clicks on the tab
		$('.admin__users .tab').click(function(){
			$('.tab').removeClass('active');
			let tab_name = $(this).text().toLowerCase();
			// console.log('tab title is: ' + tab_name);
			$(this).addClass('active');
			$(".tab-content").removeClass('active');
			$(".tab-content.users-" + tab_name).addClass('active');
		});

		// this bit controls when the create form gets submitted
		$('form.admin_users-create').submit(function(e){
			e.preventDefault();
			let current_number_of_users = specializedLayerBinder.users['getExistingUsers']();
			// let current_number_of_users = $(".existing-users.listing > .users-wrapper.item").length;
			console.log('current number of users: ' + current_number_of_users);
			console.log('submitting new user');
			let formdata = new FormData($('form.admin_users-create').get(0));
			console.log(formdata);
			$.ajax({
				method: "POST",
				url: base_url + "/users/saveuser",
				data: formdata,
				processData: false,
				contentType: false,
				dataType: "json"
			}).done(function (response) {
				console.log('response from server: ' + response);
				if(response.status === 'success'){
					console.log('user saved');

					let user_row_listing = '<div class="users-wrapper item" data-list-conciliator="' + response.message.id + '">' +
						'<div class="user-index">' + (current_number_of_users + 1) + '</div>' +
						'<div class="user-name">' + response.message.name + '</div>' +
						'<div class="user-lastname">' + response.message.lastname + '</div>' +
						'<div class="user-nickname">' + response.message.nickname + '</div>' +
						'</div>';

					let user_row_edit = '<div id="user-' + response.message.id + '" class="users-wrapper item">' +
						'<div class="user-index">' + (current_number_of_users + 1) + '</div>' +
						'<div class="user-name">' + response.message.name + '</div>' +
						'<div class="user-lastname">' + response.message.lastname + '</div>' +
						'<div class="user-nickname">' + response.message.nickname + '</div>' +
						'<div class="user-actions">' +
						'<button class="user-delete" type="button" value="' + response.message.id + '">Delete</button>' +
						'</div>' +
						'</div>';

					//check if it's the first user to be created or a list already exists
					if($(".users-wrapper.head").length === 0){
						let listing_head = '<div class="users-wrapper head">' +
							'<div class="user-index">&#35;</div>' +
							'<div class="user-name">Name</div>' +
							'<div class="user-lastname">Lastname</div>' +
							'<div class="user-nickname">Nickname</div>' +
							'</div>';
						let editing_head = '<div class="users-wrapper head">' +
							'<div class="user-index">&#35;</div>' +
							'<div class="user-name">Name</div>' +
							'<div class="user-lastname">Lastname</div>' +
							'<div class="user-nickname">Nickname</div>' +
							'<div class="user-actions">Actions</div>' +
							'</div>';

						$(".existing-users.listing").html('');
						$(".existing-users.edit").html('');
						$(".existing-users.listing").html(listing_head);
						$(".existing-users.edit").html(editing_head);
					}

					$(".existing-users.listing").append(user_row_listing);
					$(".existing-users.edit").append(user_row_edit);

					commonLayerBinder['users']();
					specializedLayerBinder.users["updateUsersNumber"]();
					specializedLayerBinder.users["cleanCreateForm"]();
				}
			});

		});

		commonLayerBinder['users']();
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

			console.log('visibility before ajax: ' + news_visibility);
			$.ajaxSetup({cache: false});
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

				$("#news-" + news_id + " .news__visibility span").html(server_response).css('color', font_color);
				$("#news-" + news_id + " .news__visibility span").fadeOut(1200, function(){
					$(this).html('').css('display', '');
					// this prevents the change in color on server error
					if(server_response === 'Updated'){
						if(news_visibility){
							$("#news-" + news_id).removeClass('disabled-news');
							$("#news-" + news_id).addClass('enabled-news');
						}else{
							$("#news-" + news_id).removeClass('enabled-news');
							$("#news-" + news_id).addClass('disabled-news');
						}
					}
				});
			}).fail(function(response){
				server_response = "Error connecting to server. Please try again later";
				font_color = 'black';

				$("#news-" + news_id + " .news__visibility span").html(server_response).css('color', font_color);
				$("#news-" + news_id + " .news__visibility span").fadeOut(2000, function() {
					$(this).html('').css('display', '');
					// this puts back the tick in case of error
					if (news_visibility === 0) {
						$("#news-chbx-" + news_id).prop("checked", true);

					} else {
						$("#news-chbx-" + news_id).prop("checked", false);
					}
				});
			});
			// .always(function(){
			// 	// this will execute no matter what
			// 	$("#news-" + news_id + " .news__visibility span").html(server_response).css('color', font_color);
			// 	$("#news-" + news_id + " .news__visibility span").fadeOut(1200, function(){
			// 		$(this).html('').css('display', '');
			// 		// this prevents the change in color on server error
			// 		if(server_response === 'Updated'){
			// 			if(news_visibility){
			// 				$("#news-" + news_id).removeClass('disabled-news');
			// 				$("#news-" + news_id).addClass('enabled-news');
			// 			}else{
			// 				$("#news-" + news_id).removeClass('enabled-news');
			// 				$("#news-" + news_id).addClass('disabled-news');
			// 			}
			// 		}
			// 	});
			// });
		});
	},

	ranking: function(){
		console.log('ranking second layer binder');

		// this bit is to manage the selection of the ranking to edit
		$(".ranking-wrapper.item").click(function () {
			let rank_id = $(this).data('rank-id');

			$.ajax({
				method: "GET",
				url: base_url + "/ranking/getranktoedit/" + rank_id,
				dataType: 'json'
			}).done(function(response){

				let name_dd_options;
				// this bit is to handle the title
				$(".subcontent.edit .ranking-title").val(response.message.title);

				// this bit is to handle start and end dates
				// datepicker deserves a bit of explanation. the first line defines how the format is in the datepicker widget. the second line sets the new date. this is where it gets tricky, specially with the parsing. when parsin a data, the string must have the exact format defined, otherwise will throw errors everywhere. so if the date string is 2020-05-30 12:30:12, the hours minutes and seconds must be removed since datepicker parser doesn't support time, only year, month and date, plus you will see that when adding the hours, minutes and seconds to ("hh:ii:ss") the format definition when parsing a date, will tell you "Uncaught Unexpected literal at position 11" or another number, depending on how many shit you added that is not supported
				$(".subcontent.edit .ranking-start").datepicker({dateFormat: "yy-mm-dd"});
				$(".subcontent.edit .ranking-start").datepicker("setDate", $.datepicker.parseDate("yy-mm-dd", response.message.start_date.substring(0, response.message.start_date.indexOf(' '))));

				if(response.message.end_date !== null){
					$(".subcontent.edit .ranking-end").datepicker({dateFormat: "yy-mm-dd"});
					$(".subcontent.edit .ranking-end").datepicker("setDate", $.datepicker.parseDate("yy-mm-dd", response.message.end_date.substring(0, response.message.end_date.indexOf(' '))));
				}else{
					// if there is not an end date defined, reset the datepicker
					$(".subcontent.edit .ranking-end").datepicker("setDate", null);
				}

				// this bit is to handle the select part
				$(".subcontent.edit .ranking-tops option:selected").removeAttr("selected");
				$(".subcontent.edit .ranking-tops option[value='" + response.message.tops + "']").attr('selected', 'selected');

				// this bit is to handle the players
				if(response.message.players === null){
					// this first bit is when the players haven't been assigned yet
					$(".ranking-players").empty();

					for(let i = 0; i < response.message.tops; i++){
						let player_details = '<label for="player-' + (1 + i) + '">Player ' + (1 + i) + ':</label>' +
							'<div class="player-' + (1 + i) + ' details">' +
							'<label for="user-name">Name:</label>' +
							'<select class="user-name" name="user-name"> + name_dd_options + </select>' +
							// '<input type="text" name="player-name" placeholder="Player\'s ' + (1 + i) + ' name" required>' +
							// '<label for="player-lastname">Lastname:</label>' +
							// '<input type="text" name="player-lastname" placeholder="Player\'s ' + (1 + i) + ' lastname" required>' +
							// '<label for="player-nickname">Nickname:</label>' +
							// '<input type="text" name="player-nickname" placeholder="Player\' ' + (1 + i) + ' nickname">' +
							// '<label for="player-score">Score:</label>' +
							// '<input type="number" name="player-score" placeholder="Player\'s ' + (1 + i) + ' score" required>' +
							'</div>';

						$(".ranking-players").append(player_details);
					}
					$.ajax({
						method: "POST",
						url: base_url + "/users/dropdowns",
						data: {
							field: 'name'
						},
						dataType: "json",
					}).done(function(response) {
						console.log('dd: ' + response);
						if (response.status === 'success') {
							let dd_options = '<option>Select a user name</option>';
							$.each(response.message, function (key, value) {
								// console.log('key: ' + key + '- value: ' + value);
								dd_options += '<option value="' + value + '">' + value + '</option>';
							});
							$(".user-name").html(dd_options);
						}
					});
				}else{
					// this second bit is when the player were assigned and the admin wants to do some editing
					$(".ranking-players").empty();

					for(let i = 0; i < Object.keys(response.message.players).length; i++){
						let player_details = '<label for="player-' + (1 + i) + '">Player ' + (1 + i) + ':</label>' +
							'<div class="player-' + (1 + i) + ' details">' +
							'<input type="hidden" name="player-id" value="' + response.message.players[i].id + '">' +
							'<label for="player-name">Name:</label>' +
							'<input type="text" name="player-name" value="' + response.message.players[i].name + '" required>' +
							'<label for="player-lastname">Lastname:</label>' +
							'<input type="text" name="player-lastname" value="' + response.message.players[i].lastname + '" required>' +
							'<label for="player-nickname">Nickname:</label>' +
							'<input type="text" name="player-nickname" value="' + response.message.players[i].nickname + '">' +
							'<label for="player-score">Score:</label>' +
							'<input type="number" name="player-score" value="' + response.message.players[i].player_score + '" required>' +
							'</div>';

						$(".ranking-players").append(player_details);
					}

					// in this particular case i also need to complete the rest of the data/dropdowns

				}
				// then complete the dropdown
				// $.ajax({
				// 	method: "POST",
				// 	url: base_url + "/users/dropdowns",
				// 	data: {
				// 		field: 'name'
				// 	},
				// 	dataType: "json",
				// }).done(function(response) {
				// 	console.log('dd: ' + response);
				// 	if (response.status === 'success') {
				// 		let dd_options = '<option>Select a user name</option>';
				// 		$.each(response.message, function (key, value) {
				// 			// console.log('key: ' + key + '- value: ' + value);
				// 			dd_options += '<option value="' + value + '">' + value + '</option>';
				// 		});
				// 		$(".user-name").val(dd_options);
				// 	}
				// });
				// i will have to bind two different options depending on if there are users selected or if they need to be selected
				// this is when you select the user for the first time
				thirdLayerBinder['ranking']();

			});

		// this last bit is top leave out the buttons from the clicking
		}).children().not(".rank-actions.item, .status-modifier, .rank-delete");

		// this bit is to manage the modification of the status of a ranking
		$(".status-modifier").click(function(e){
			// this prevents clicking the parent div
			e.stopPropagation();
			let rank_id = $(this).closest(".ranking-wrapper.item").data('rank-id');
			let status_value = $(this).attr("future-rank-stat");
			console.log('status modificer clicked for rank ' + rank_id);
			// console.log('status value: ' + status_value)
			let server_response;

			$.ajax({
				url: base_url + "/ranking/undaterankingstatus",
				method: "POST",
				data: {
					rank_id: rank_id,
					status: status_value
				},
				dataType: "json"
			}).done(function(response){
				console.log('status update response: ' + response);
				if(response.status === 'success'){
					server_response = 'Updated';
				}else{
					server_response = "Error updating. Please refresh the page";
				}
			}).fail(function(response){
				server_response = "Error connecting to server. Please refresh the page";
			}).always(function(){
				// this will execute no matter what
				let parent_ranking_container = $('*[data-rank-id="' + rank_id + '"]');
				let rank_status_button = $("#rank-" + rank_id + "-stat");
				if(status_value === "1"){
					// update row color
					parent_ranking_container.removeClass('inactive');
					parent_ranking_container.addClass('active');
					parent_ranking_container.children(".rank-status").html('Active');
					// update status button color, text and value
					rank_status_button.removeClass('active');
					rank_status_button.addClass('inactive');
					rank_status_button.attr("future-rank-stat", 0);
					rank_status_button.html("Deactivate");
				}else{
					// update row color
					parent_ranking_container.removeClass('active');
					parent_ranking_container.addClass('inactive');
					parent_ranking_container.children(".rank-status").html('Inactive');
					// update status button color, text and value
					rank_status_button.removeClass('inactive');
					rank_status_button.addClass('active');
					rank_status_button.attr("future-rank-stat", 1);
					rank_status_button.html("Activate");
				}

			});

		});

		// this bit is to manage the deletion of a ranking
		$(".rank-delete").click(function(e){
			// this prevents clicking the parent div
			e.stopPropagation();
			let rank_id = $(this).closest(".ranking-wrapper.item").data('rank-id');

			console.log('rank delete clicked for rank ' + rank_id);
			// request confirmation since this can't be undone
			let confirmation = confirm("This operation cannot be undone.\nAre you sure you want to delete this ranking?");

		});

	},

	users: function(){
		console.log('users second layer binder');
		$(".user-delete").click(function(e){
			e.preventDefault();
			// specializedLayerBinder.users['reasignIndex']();
			console.log('deleting user');
			let user_id = $(this).val();
			console.log('user id: ' + user_id);
			let confirmarion = confirm("This operation cannot be undone.\nAre you sure you want to delete this user?");
			if(confirmarion){
				$.ajax({
					method: "POST",
					url: base_url + "/users/delete",
					data: {
						user_id: user_id
					},
					dataType: "json"
				}).done(function(response){
					console.log('delete user server response: ' + response);
					if(response.status === 'success'){
						$("#user-" + user_id).remove();
						$("*[data-list-conciliator='" + user_id + "']").remove();
						specializedLayerBinder.users["updateUsersNumber"]();
						specializedLayerBinder.users["reasignIndex"]();
					}
				});
			}

		});
	}
};

let thirdLayerBinder = {
	ranking: function(){
		$(".user-name").change(function(){
			let user_name = $(this).val();
			console.log('user name selected: ' + user_name);
			$.ajax({
				method: "POST",
				url: base_url + "/users/dropdowns",
				data: {
					field: 'lastname',
					user_name: user_name
				},
				dataType: 'json',
				async: false
			})
		})
	}

}

let specializedLayerBinder = {

	ranking: {
		constructPlayerNameDropdown: function(){
			let name_dd;
			// $.ajax({
			// 	method: "POST",
			// 	url: base_url + "/users/dropdowns",
			// 	data: {
			// 		field: 'name'
			// 	},
			// 	dataType: "json",
			// 	// async: false
			// }).done(function(response){
			// 	// console.log('dd: ' + response);
			// 	if(response.status === 'success'){
			// 		let dd_options = '<option>Select a user name</option>';
			// 		$.each(response.message, function(key, value){
			// 			// console.log('key: ' + key + '- value: ' + value);
			// 			dd_options += '<option value="' + value + '">' + value + '</option>';
			// 		});
			//
			// 		name_dd = '<select class="user-name" name="user-name">' + dd_options + '</select>';
			//
			// 	}
			// 	return name_dd;
			// });
			return $.ajax({
				method: "POST",
				url: base_url + "/users/dropdowns",
				data: {
					field: 'name'
				},
				dataType: "json",
				// async: false
				success: function (response) {
					if(response.status === 'success'){
						let dd_options = '<option>Select a user name</option>';
						$.each(response.message, function(key, value){
							// console.log('key: ' + key + '- value: ' + value);
							dd_options += '<option value="' + value + '">' + value + '</option>';
						});

						name_dd = '<select class="user-name" name="user-name">' + dd_options + '</select>';

					}
					return name_dd;
				}
			});


		},
		constructPlayerLastnameDropdown: function(){

		},
		constructPlayerNicknameDropdown: function(){

		},
		userNameSelectClick: function(){
			$(".user-name").change(function(){
				console.log('just checking 3 ...');
			});
		}
	},

	users: {
		cleanCreateForm: function(){
			$(".admin_users-create input[type=text]").val('');
		},
		getExistingUsers: function(){
			let current_number_of_users = $(".existing-users.edit > .users-wrapper.item").length;
			return current_number_of_users;
		},
		updateUsersNumber: function(){
			$(".current-existing-users").html(this.getExistingUsers());
		},
		reasignIndex: function(){
			let indexes_to_update = $(".existing-users.edit > .users-wrapper.item > .user-index");
			console.log('indexes ', indexes_to_update.length);
			let new_index = 1;
			if(indexes_to_update.length > 0){
				$(indexes_to_update).each(function(){
					console.log("loop " + new_index);
					$(this).html(new_index);
					new_index++;
				});
			}else{
				$(".existing-users.edit").text("There are no users saved");
				$(".existing-users.listing").text("There are no users saved");
			}

		}

	}

}

function testfunction(data) {

}
