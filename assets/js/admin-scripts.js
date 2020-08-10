let base_url = window.location.origin;

$(document).ready(function(){

	console.log('loading Valhalla-Lanes admin javascript');
	let content_wrapper = $('.content');

	// Sidebar Section - This checks what button was clicked
	$('.sidebar__item').on('click', function (e) {

		let classes = $(this).attr('class');
		let loadSection = classes.split(" ");
		$('.sidebar__item').removeClass('selected');
		$('.sidebar__item.' + loadSection[1]).addClass('selected');
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

		$('form.admin_ranking-edit').submit(function(e){
			e.preventDefault();
			let formdata = new FormData($('form.admin_ranking-edit').get(0));
			console.log(formdata);

			$.ajax({
				method: "POST",
				url: base_url + "/ranking/edit",
				data: formdata,
				processData: false,
				contentType: false,
				dataType: "json"
			}).done(function (response) {
				console.log(response);
			})
		})

		commonLayerBinder.ranking['editRanking']();
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
				if(response.status === 'error'){
					$(".create-user-server-response").html(response.message).css('color','red');
					$(".create-user-server-response").fadeOut(3000, function(){
						$(this).html('').css('display', '');
					});
				}
			}).fail(function(response){
				$(".create-user-server-response").html("Database Connection Error").css('color','red');
				$(".create-user-server-response").fadeOut(3000, function(){
					$(this).html('').css('display', '');
				});
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

	ranking: {
		editRanking: function(){
			// this bit is to manage the selection of the ranking to edit
			$(".ranking-wrapper.item").click(function () {
				let rank_id = $(this).data('rank-id');

				$.ajax({
					method: "GET",
					url: base_url + "/ranking/getranktoedit/" + rank_id,
					dataType: 'json'
				}).done(function(response){
					// this bit is to handle the rank id
					$(".subcontent.edit .ranking-id").val(rank_id);
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
					$(".subcontent.edit .ranking-tops option:selected").prop("selected", false);
					$(".subcontent.edit .ranking-tops option[value='" + response.message.tops + "']").prop('selected', true);

					// clean players list if any
					$(".ranking-players").empty();

					// this bit is to handle the players
					if(response.message.players === null){

						// this first bit is when the players haven't been assigned yet
						for(let i = 0; i < response.message.tops; i++){
							let player_number = i + 1;
							let player_details = specializedLayerBinder.ranking['getPlayerDetails'](player_number);
							$(".ranking-players").append(player_details);
						}
						specializedLayerBinder.ranking['buildUserNameDropDown'](".user-name");

					}else{

						// this second bit is when the player were assigned and the admin wants to do some editing
						for(let i = 0; i < Object.keys(response.message.players).length; i++) {
							let player_number = i + 1;
							let player_details = specializedLayerBinder.ranking['getPlayerDetails'](player_number);

							$(".ranking-players").append(player_details);

							specializedLayerBinder.ranking['buildUserNameDropDown'](".player-" + player_number + ".details > .user-name", response.message.players[i].name);
							specializedLayerBinder.ranking['buildUserLastnameDropDown'](response.message.players[i].name, "player-" + player_number, response.message.players[i].lastname);
							specializedLayerBinder.ranking['hideShowUserSections']("player-" + player_number, 'show', 1);
							specializedLayerBinder.ranking['buildUserNicknameDropDown'](response.message.players[i].name, response.message.players[i].lastname, "player-" + player_number, response.message.players[i].player_name_display);

							// show the display name options only if there is a nickname
							if(!$.isEmptyObject(response.message.players[i].nickname)){
								if(response.message.players[i].player_name_display !== 'NAME'){
									specializedLayerBinder.ranking['hideShowUserSections']("player-" + player_number, 'show', 4);
								}
								specializedLayerBinder.ranking['userDisplayNameOptions'](i+1);
							}
							specializedLayerBinder.ranking['forceRadioCheck']("player-" + player_number, response.message.players[i].player_name_display);
							specializedLayerBinder.ranking['setPlayerScore']("player-" + player_number, response.message.players[i].player_score);
						}

					}
					// specializedLayerBinder.ranking['checkForNameDisplayOptions']();
					// i will have to bind two different options depending on if there are users selected or if they need to be selected
					// this is when you select the user for the first time
					secondLayerBinder.ranking['rankingUserName']();
					secondLayerBinder.ranking['rankingTops']();
				});

				specializedLayerBinder.ranking['checkForNameDisplayOptions']();
				// this last bit is to leave out the buttons from the clicking
			}).children().not(".rank-actions.item, .status-modifier, .rank-delete");

			// this bit is to manage the modification of the status of a ranking
			$(".status-modifier").click(function(e){
				// this prevents clicking the parent div
				e.stopPropagation();
				let rank_id = $(this).closest(".ranking-wrapper.item").data('rank-id');
				let status_value = $(this).attr("future-rank-stat");
				console.log('status modifier clicked for rank ' + rank_id);
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
					console.log('status update response: ' + response.message);
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
	},

	users: function(){
		console.log('users second layer binder');
		$(".user-delete").click(function(e){
			e.preventDefault();
			let user_id = $(this).val();
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

let secondLayerBinder = {
	ranking: {
		rankingTops: function(){
			$(".ranking-tops").change(function(){
				let new_tops = $(this).val();
				specializedLayerBinder.ranking['addOrRemovePlayers'](new_tops);
			})
		},
		rankingUserName: function(){
			$(".user-name").change(function(){
			let user_name = $(this).val();
			// i'm keeping this here just to remember :P
			// let player_index = $.grep(player_details.attr('class').split(" "), function(key, value){
			// 	return "key: " + key + " - value: " + value;
			// });
			// this line is to then enable or disable dropdowns for this player and not all of them at the same time
			let player_index = specializedLayerBinder.ranking['findClosestIndex'](this); // return example: "player-1"
			// only show the other dropdown when a name is selected
			if(user_name !== ''){
				// first clear existent values
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'hide', 2);
				specializedLayerBinder.ranking['cleanPlayerName'](player_index);
				// then do the build for new ones
				specializedLayerBinder.ranking['buildUserLastnameDropDown'](user_name, player_index);
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'show', 1);
				thirdLayerBinder['ranking']();
			}else{
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'hide', 1);
			}
		});
		}
	}
};

let thirdLayerBinder = {
	ranking: function(){
		$(".user-lastname").change(function(){
			let player_index = specializedLayerBinder.ranking['findClosestIndex'](this);
			let user_lastname = $(this).val();
			let user_name = $(this).prev().prev("select.user-name").find(":selected").val(); // with only 1 "prev()" it would check the label instead of the select
			if(user_lastname !== ''){
				specializedLayerBinder.ranking['buildUserNicknameDropDown'](user_name, user_lastname, player_index);

				specializedLayerBinder.ranking['playerNameOptions'](player_index)
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'show', 3);
			}else{
				$("span." + player_index + ".display-name").html('');
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'hide', 2);
			}
		})
	}
};

let fourthLayerBinder = {
	ranking: function () {
		$(".user-nickname").change(function(){
			let player_index = specializedLayerBinder.ranking['findClosestIndex'](this);
			let player_number = specializedLayerBinder.ranking['getPlayerNumber'](player_index + '');
			let user_nickname = $(this).val();
			if(user_nickname !== ''){
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'show', 4);
				specializedLayerBinder.ranking['userDisplayNameOptions'](player_number);
			}else{
				specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'hide', 4);
				specializedLayerBinder.ranking['playerNameOptions'](player_index)
				specializedLayerBinder.ranking['resetRadioButtons'](player_index);
			}
		})
	}
};

let specializedLayerBinder = {
	ranking: {
		checkForNameDisplayOptions: function(){
			let default_players = $(".ranking-tops").val();
			console.log('tops: ' + default_players);
			let player_details = $(".ranking-players > .details");
			console.log('player details: ' + player_details);
			$.each(player_details, function(key, value){
				console.log('key: ' + key + ' - value: ' + value);
				let name_option = $(value).find(".input[type=radio]").val();
				console.log('name option selected: ' + name_option);
			});
			let lalala = [];
			for(let i=1; i <= default_players; i++){
				lalala.push($('.player-' + i).find(".input[name=user-name-display_player" + i + "]").val());
			}
			console.log(lalala);
		},
		getPlayerDetails: function(player_number){
			let player_details = '<label for="player-' + player_number + '">Player ' + player_number + ': <span class="player-' + player_number + ' display-name"></span></label>' +
				'<div class="player-' + player_number + ' details">' +
				'<label for="user-name">Name:</label>' +
				'<select class="user-name" name="user-name_player' + player_number + '" required></select>' +
				'<label class="hide-detail" for="user-lastname">Lastname:</label>' +
				'<select class="user-lastname hide-detail" name="user-lastname_player' + player_number + '" required><option>Select a user name to unlock</option></select>' +
				'<label class="hide-detail" for="user-nickname">Nickname:</label>' +
				'<select class="user-nickname hide-detail" name="user-nickname"><option>Select a user lastname to unlock</option></select>' +
				'<label class="hide-detail" for="user-score">Score:</label>' +
				'<input type="number" class="user-score hide-detail" name="user-score_player' + player_number + '" placeholder="Player\'s ' + player_number + ' score" required>' +
				'<label class="hide-detail" for="user-display">Display name options:</label>' +
				'<div class="user-display hide-detail">' +
				'<div class="display-option">' +
				'<input type="radio" name="user-name-display_player' + player_number + '" value="name">' +
				'<label for="name">Only name</label>' +
				'</div>' +
				'<div class="display-option">' +
				'<input type="radio" name="user-name-display_player' + player_number + '" value="combined">' +
				'<label for="combined">Combined</label>' +
				'</div>' +
				'<div class="display-option">' +
				'<input type="radio" name="user-name-display_player' + player_number + '" value="nickname">' +
				'<label for="nickname">Only nickname</label>' +
				'</div>' +
				'</div>' +
				'</div>';
			return player_details;
		},
		buildUserNicknameDropDown: function(user_name, user_lastname, selector, selected_option = null){
			let control_field = ['name', 'lastname'];
			let control_value = [user_name, user_lastname];
			let dd_options = '<option value="">Select a user nickname</option>';

			$.ajax({
				method: "POST",
				url: base_url + "/users/dropdowns",
				data: {
					field: 'nickname',
					control_field: control_field,
					control_value: control_value
				},
				dataType: 'json'
			}).done(function(response){
				if(response.status === 'success'){
					if(!$.isEmptyObject(response.message)){ // if there are no nicknames, there's no point in doing the dropdown
						$.each(response.message, function(key,value){
							if(selected_option !== null){
								let selected_indicator = '';
								if(selected_option !== 'NAME'){
									selected_indicator = 'selected';
								}
								dd_options += '<option value="' + value + '" ' + selected_indicator + '>' + value + '</option>';
							}else{
								dd_options += '<option value="' + value + '">' + value + '</option>';
							}
						});
						$("." + selector).find(".user-nickname").html(dd_options);
						specializedLayerBinder.ranking['hideShowUserSections'](selector, 'show', 2);
						fourthLayerBinder['ranking']();
					}else{
						$("." + selector).find(".user-nickname").html(dd_options);
						$("." + selector).find(' > label.hide-detail[for="user-nickname"]').css('display', 'none');
						$("." + selector).find(' > select.user-nickname').css('display', 'none').prop('disabled', 'disabled');
					}
					if(selected_option !== null){
						specializedLayerBinder.ranking['playerNameOptions'](selector, selected_option.toLowerCase());
					}else{
						specializedLayerBinder.ranking['playerNameOptions'](selector);
					}
					specializedLayerBinder.ranking['hideShowUserSections'](selector, 'show', 3);
				}
			});
		},
		buildUserLastnameDropDown: function(user_name, selector, selected_option = null){
			let dd_options = '<option value="">Select a user lastname</option>';

			$.ajax({
				method: "POST",
				url: base_url + "/users/dropdowns",
				data: {
					field: 'lastname',
					control_field: 'name',
					control_value: user_name
				},
				dataType: "json"
			}).done(function (response){
				if(response.status === 'success'){
					$.each(response.message, function(key,value){
						if(selected_option != null){
							let selected_indicator = '';
							if(value === selected_option){
								selected_indicator = 'selected';
							}
							dd_options += '<option value="' + value + '" ' + selected_indicator + '>' + value + '</option>';
						}else{
							dd_options += '<option value="' + value + '">' + value + '</option>';
						}
					});
					$("." + selector).find(" > .user-lastname").html(dd_options);
				}
			});
		},
		buildUserNameDropDown: function(selector, selected_option = null){
			let dd_options = '<option value="">Select a user name</option>';
			$.ajax({
				method: "POST",
				url: base_url + "/users/dropdowns",
				data: {
					field: 'name'
				},
				dataType: "json",
			}).done(function(response) {
				if (response.status === 'success') {
					// let dd_options = '<option value="">Select a user name</option>';
					$.each(response.message, function (key, value) {
						if(selected_option != null){
							let selected_indicator = '';
							if(value === selected_option){
								selected_indicator = 'selected';
							}
							dd_options += '<option value="' + value + '" ' + selected_indicator + '>' + value + '</option>';
						}else{
							dd_options += '<option value="' + value + '">' + value + '</option>';
						}
					});
					$(selector).html(dd_options);
				}
			});
		},
		addOrRemovePlayers: function(new_tops){
			let current_players_number = $(".ranking-players .details").length;
			if(new_tops > current_players_number){
				while (current_players_number < new_tops){
					++current_players_number;
					let player_details = '<label for="player-' + current_players_number + '">Player ' + current_players_number + ': <span class="player-' + current_players_number + ' display-name"></span></label>' +
						'<div class="player-' + current_players_number + ' details">' +
						'<label for="user-name">Name:</label>' +
						'<select class="user-name" name="user-name_player' + current_players_number + '" required></select>' +
						'<label class="hide-detail" for="user-lastname">Lastname:</label>' +
						'<select class="user-lastname hide-detail" name="user-lastname_player' + current_players_number + '" required><option>Select a user name to unlock</option></select>' +
						'<label class="hide-detail" for="user-nickname">Nickname:</label>' +
						'<select class="user-nickname hide-detail" name="user-nickname_player' + current_players_number + '"><option>Select a user lastname to unlock</option></select>' +
						'<label class="hide-detail" for="user-score">Score:</label>' +
						'<input type="number" class="user-score hide-detail" name="user-score_player' + current_players_number + '" placeholder="Player\'s ' + current_players_number + ' score" required>' +
						'<label class="hide-detail" for="user-display">Display name options:</label>' +
						'<div class="user-display hide-detail">' +
						'<div class="display-option">' +
						'<input type="radio" name="user-name-display_player' + current_players_number + '" value="name">' +
						'<label for="name">Only name</label>' +
						'</div>' +
						'<div class="display-option">' +
						'<input type="radio" name="user-name-display_player' + current_players_number + '" value="combined">' +
						'<label for="combined">Combined</label>' +
						'</div>' +
						'<div class="display-option">' +
						'<input type="radio" name="user-name-display_player' + current_players_number + '" value="nickname">' +
						'<label for="nickname">Only nickname</label>' +
						'</div>' +
						'</div>' +
						'</div>';

					$(".ranking-players").append(player_details);
					specializedLayerBinder.ranking['buildUserNameDropDown'](".player-" + current_players_number + " > .user-name");
					secondLayerBinder.ranking['rankingUserName']();
				}
			}else{
				while (current_players_number > new_tops){
					console.log('remove - current players: ' + current_players_number);
					$("label[for=player-" + current_players_number + "]").remove();
					$("div.player-" + current_players_number).remove();
					current_players_number--;
				}
			}
		},
		userDisplayNameOptions: function(player_number){
			$("input[name=user-name-display_player" + player_number + "]").click(function(){
			// $("input[type=radio]").click(function(){
				let player_index = specializedLayerBinder.ranking['findClosestIndex'](this);
				let user_display_name = $(this).val();
				switch (user_display_name) {
					case 'name':
						specializedLayerBinder.ranking['playerNameOptions'](player_index);
						break;
					case 'combined':
						specializedLayerBinder.ranking['playerNameOptions'](player_index, 'combined');
						break;
					case 'nickname':
						specializedLayerBinder.ranking['playerNameOptions'](player_index, 'nickname');
						break;
				}
			});
		},
		playerNameOptions: function(player_node, option = null){
			let user_name = $('.' + player_node).find('select.user-name').val();
			let user_lastname = $('.' + player_node).find('select.user-lastname').val();
			let user_nickname = $('.' + player_node).find('select.user-nickname').val();
			let player_name = user_name + " " + user_lastname;
			switch(option){
				case 'combined':
					player_name = user_name + ' "' + user_nickname + '" ' + user_lastname;
					break;
				case 'nickname':
					player_name = '"' + user_nickname + '"';
					break;
			}
			$("span." + player_node + ".display-name").html(player_name);
		},
		findClosestIndex: function(current_node){
			let player_details = $(current_node).closest("div.details");
			let player_index = player_details.attr('class').match(/player-\d+/);
			return player_index;
		},
		getPlayerNumber: function (player_index){
			let x = player_index.split('-')[1];
			return x;
		},
		resetRadioButtons: function(player_node){
			$("." + player_node).find(":radio").prop('checked', false);
		},
		forceRadioCheck: function(player_node, radio_value){
			$("." + player_node).find("input[type=radio][value=" + radio_value.toLowerCase() + "]").prop('checked', true);
		},
		setPlayerScore: function(player_node, value){
			$("." + player_node).find(" > .user-score").val(value);
		},
		resetNicknameSection: function(player_node){
			$("." + player_node).find(' > select.user-nickname').prop('disabled', false);
			$("." + player_node).find(' > label.hide-detail[for="user-nickname"]').css('display', '');
			$("." + player_node).find(' > select.user-nickname').css('display', '');
		},
		resetScoreSection: function(player_node){
			$("." + player_node).find(' > input.user-score').val('');
		},
		cleanPlayerName: function(player_node){
			$("span." + player_node + ".display-name").html('');
		},
		hideShowUserSections: function(player_node, show_hide, section){
			if(show_hide === 'hide'){
				if(section <= 4){
					$("." + player_node).find(' > label[for="user-display"]').addClass('hide-detail');
					$("." + player_node).find(' > div.user-display').addClass('hide-detail');
					specializedLayerBinder.ranking['resetRadioButtons'](player_node);
				}
				if(section <= 3){
					$("." + player_node).find(' > label[for="user-score"]').addClass('hide-detail');
					$("." + player_node).find(' > input.user-score').addClass('hide-detail');
					specializedLayerBinder.ranking['resetScoreSection'](player_node);
				}
				if(section <= 2){
					$("." + player_node).find(' > label[for="user-nickname"]').addClass('hide-detail');
					$("." + player_node).find(' > select.user-nickname').addClass('hide-detail');
					specializedLayerBinder.ranking['resetNicknameSection'](player_node);
					specializedLayerBinder.ranking['cleanPlayerName'](player_node);
				}
				if(section <= 1){
					$("." + player_node).find(' > label[for="user-lastname"]').addClass('hide-detail');
					$("." + player_node).find(' > select.user-lastname').addClass('hide-detail');
				}
			}

			if(show_hide === 'show') {
				switch (section) {
					case 1:
						$("." + player_node).find(' > label.hide-detail[for="user-lastname"]').removeClass('hide-detail');
						$("." + player_node).find(' > select.user-lastname').removeClass('hide-detail');
						break;
					case 2:
						$("." + player_node).find(' > label.hide-detail[for="user-nickname"]').removeClass('hide-detail');
						$("." + player_node).find(' > select.user-nickname').removeClass('hide-detail');
						break;
					case 3:
						$("." + player_node).find(' > label.hide-detail[for="user-score"]').removeClass('hide-detail');
						$("." + player_node).find(' > input.user-score').removeClass('hide-detail');
						break;
					case 4:
						$("." + player_node).find(' > label.hide-detail[for="user-display"]').removeClass('hide-detail');
						$("." + player_node).find(' > div.user-display').removeClass('hide-detail');
						break;
				}
			}
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
			let new_index = 1;
			if(indexes_to_update.length > 0){
				$(indexes_to_update).each(function(){
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
