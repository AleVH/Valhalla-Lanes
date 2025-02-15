let base_url = window.location.origin;

$(document).ready(function(){

	let currentSection = (window.location.pathname).split("/");
	// load current section
	binder[currentSection[2]]();

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

	// $(".logout_user").click(function(e) {
	// 	e.preventDefault();
	// 	let confirmation = confirm("Are you sure you want to close you session?");
	// 	if(confirmation){
	// 		window.location.href = base_url + $(this).attr('href');
	// 	}
	// });

	$(".header__logout").click(function(e) {
		e.preventDefault();
		let confirmation = confirm("Are you sure you want to close you session?");
		if(confirmation){
			window.location.href = base_url + $(this).find(".logout_user").attr('href');
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

	gallery : async function(){
		console.log('gallery scripts binded');
		$(".gallery_action").click( async function(){
			let image_id = $(this).closest("[data-image-id]").data("image-id");
			let image_activation = await specializedLayerBinder.gallery['toggleImageDisplay'](image_id, 1);

			if(image_activation.status === 'success'){
				$(".sidebar__item.gallery").trigger('click');
			}
		});

		$(".gallery_show").click( async function(){
			let image_id = $(this).closest("[data-image-id]").data("image-id");
			let image_deactivation = await specializedLayerBinder.gallery['toggleImageDisplay'](image_id, 0);

			if(image_deactivation.status === 'success'){
				$(".sidebar__item.gallery").trigger('click');
			}
		});

		// this bit is to manage gallery image order with drag and drop
		$(".gallery_images__wrapper").sortable({
			items: ".uploaded_image",
			revert: true,
			tolerance: "pointer",
			opacity: 0.5,
			stop: (event, ui) => {
				specializedLayerBinder.gallery['reviewGalleryImagePositions']();
			}
		});
		$(".gallery_images__wrapper").disableSelection();

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

		if(promotest != 'undefined'){
			console.log('lo lee!!!!');
			console.log('y dice: ' + promotest);
		}else{
			console.log('la mierda esta no funca');
		}

		// this bit controls the clicks on the tab
		$('.admin__promotions .tab').click(function(){
			$('.tab').removeClass('active');
			let tab_name = $(this).text().toLowerCase();
			// console.log('tab title is: ' + tab_name);
			$(this).addClass('active');
			$(".tab-content").removeClass('active');
			$(".tab-content.promotions-" + tab_name).addClass('active');
		});

		// this controls the limit in characters in the title and the message
		$(".promo-title").on("keyup", function(){
			console.log('title characters: ' +  this.value.length);
			if(this.value.length > 0){
				if(this.value.length > 19){
					$(".promo-title-counter").html(30 - this.value.length);
					if(this.value.length > 24){
						$(".promo-title-counter").css("color", "#FF0000");
					}else{
						$(".promo-title-counter").css("color", "");
					}
				}else{
					$(".promo-title-counter").html("");
				}
			}else{
				$(".promo-title-counter").html("");
			}
		});

		$(".promo-text").on("keyup", function(){
			console.log('message characters: ' + this.value.length);
			console.log('message: ' + this.value);
			if(this.value.length > 0){
				$(".promotions__promo-preview .example").html(this.value);
				if(this.value.length > 89){
					$(".promo-text-counter").html(100 - this.value.length);
					if(this.value.length > 94){
						$(".promo-text-counter").css("color", "#FF0000");
					}else{
						$(".promo-text-counter").css("color", "");
					}
				}else{
					$(".promo-text-counter").html("");
				}
			}else{
				$(".promo-text-counter").html("");
				$(".promotions__promo-preview .example").html("Example text");
			}
		});

		// this bit is to check the value selected and apply it to the example text, so you can see in the admin, before publishing, how it looks like
		$(".promo-format_color").on("change", function(){
			$(".promotions__promo-preview .example").css("color", this.value);
		});

		$(".promo-format_font-size").on("change", function(){
			$(".promotions__promo-preview .example").css("font-size", this.value + "px");
		});

		$(".promo-format_speed").on("change", function(){
			let marquee_speed = 33;
			$(".promotions__promo-preview .example").css("animation-duration", (marquee_speed - this.value) + "s");
		})

		// this controls the form submit
		$('form.admin_promotions').submit(function(e){
			e.preventDefault();
			let formdata = new FormData($(this).get(0));
			console.log(formdata.get("promo-title")); // this works

			$.ajax({
				method: "POST",
				url: base_url + "/promotions/create",
				data: formdata,
				processData: false,
				contentType: false,
				dataType: "json"
			}).then((response) => {
				console.log(response);
			});
		});

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

			$.ajax({
				method: "POST",
				url: base_url + "/ranking/edit",
				data: formdata,
				processData: false,
				contentType: false,
				dataType: "json"
			}).done(function (response) {
				console.log(response);
				if(response.status === 'success'){
					// re-click on the ranking to refresh the data
					$(".ranking-wrapper.item[data-rank-id='" + response.message.id + "']").trigger('click');
				}
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

		// this was to add more files - assuming you could only select one per time - but it's not used anymore. im just keeping it to remember how to do it :P
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
					$(".sidebar__item.upload").trigger('click');
				}else{
					console.log('error response');
					// this is for when something is wrong with the credentials
					// $('.admin__login.error').html(response.message).css({'display' : 'block', 'opacity' : '1', 'color' : 'red'});
					// $('.admin__login.error').fadeOut(3200, function(){
					// 	$(this).html('&nbsp;').css('display', '');
					// });

					// trigger click event to load section (click on the sidebar "Upload" button)
				}
			});
		});

		$(".actions_button.delete").click(async function(){
			// get the image id for the button clicked
			let image_id = $(this).closest("[data-image-id]").data("image-id");
			let image_filename = $(this).parent().siblings('div.image_name').text()
			let confirmation = confirm("This operation cannot be undone.\nAre you sure you want to delete this image?");

			if(confirmation){
				console.log('deleted!');
				console.log('data id: ' + image_id);
				console.log('image name: ' + image_filename);
				let deletion_response = await specializedLayerBinder.upload['deleteImage'](image_id, image_filename);

				if(deletion_response.status === 'success'){
					$(".sidebar__item.upload").trigger('click');
				}
				// TO-DO needs to handle errors...
			}
		});

		$(".actions_button.publish").click(async function (){
			console.log("publish button clicked");
			// get the image id for the button clicked
			let image_id = $(this).closest("[data-image-id]").data("image-id");
			// get current publish status
			let image_publish_status = 1;
			if($(this).hasClass("published")){
				image_publish_status = 0;
			}
			let toggle_publish_status = await specializedLayerBinder.upload['togglePublishStatus'](image_id, image_publish_status);
			if(toggle_publish_status.status === 'success'){
				if(image_publish_status){
					$(this).addClass("published").removeClass("unpublished");
				}else{
					$(this).addClass("unpublished").removeClass("published");
				}

			}
		});

		$(".actions_button.rename").click(function(){
			console.log('rename!');
			$(this).closest(".uploaded_image_card").toggleClass("display_edit_form");
		});

		$(".image_filename_edit .filename_edit_cancel").click(function(){
			console.log('cancel rename!');
			$(this).closest(".uploaded_image_card").toggleClass("display_edit_form");
		});

		$(".image_filename_edit").submit(async function(e){
			e.preventDefault();
			let image_id = $(this).find(".new_filename_image_id").val();
			let new_filename = $(this).find(".image_new_filename").val();
			// console.log("id: " + image_id);
			// console.log("new name: " + new_filename);
			let renaming = await specializedLayerBinder.upload['renameImage'](image_id, new_filename);

			if(renaming.status === 'success'){
				$(".sidebar__item.upload").trigger('click');
			}else{
				if(renaming.message !== 'ERROR'){
					$(".error-msgs").html(renaming.message).css("color", "red");
					$(".error-msgs").fadeOut(3000, function(){
						$(this).html("").css("display", "");
					});
				}
			}

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

			$(".ranking-wrapper.item").click( async function () {

				let rank_id = $(this).data('rank-id');
				let rank_to_edit = await specializedLayerBinder.ranking['getRankToEditPromise'](rank_id);

				// this bit is to handle the rank id
				$(".subcontent.edit .ranking-id").val(rank_id);
				// this bit is to handle the title
				$(".subcontent.edit .ranking-title").val(rank_to_edit.message.title);

				// this bit is to handle start and end dates
				// datepicker deserves a bit of explanation. the first line defines how the format is in the datepicker widget. the second line sets the new date. this is where it gets tricky, specially with the parsing. when parsin a data, the string must have the exact format defined, otherwise will throw errors everywhere. so if the date string is 2020-05-30 12:30:12, the hours minutes and seconds must be removed since datepicker parser doesn't support time, only year, month and date, plus you will see that when adding the hours, minutes and seconds to ("hh:ii:ss") the format definition when parsing a date, will tell you "Uncaught Unexpected literal at position 11" or another number, depending on how many shit you added that is not supported
				$(".subcontent.edit .ranking-start").datepicker({dateFormat: "yy-mm-dd"});
				$(".subcontent.edit .ranking-start").datepicker("setDate", $.datepicker.parseDate("yy-mm-dd", rank_to_edit.message.start_date.substring(0, rank_to_edit.message.start_date.indexOf(' '))));

				if(rank_to_edit.message.end_date !== null){
					$(".subcontent.edit .ranking-end").datepicker({dateFormat: "yy-mm-dd"});
					$(".subcontent.edit .ranking-end").datepicker("setDate", $.datepicker.parseDate("yy-mm-dd", rank_to_edit.message.end_date.substring(0, rank_to_edit.message.end_date.indexOf(' '))));
				}else{
					// if there is not an end date defined, reset the datepicker
					$(".subcontent.edit .ranking-end").datepicker("setDate", null);
				}

				// this bit is to handle the select part
				$(".subcontent.edit .ranking-tops option:selected").prop("selected", false);
				$(".subcontent.edit .ranking-tops option[value='" + rank_to_edit.message.tops + "']").prop('selected', true);

				// clean players list if any
				$(".ranking-players").empty();

				let user_name_values = await specializedLayerBinder.ranking['getUsersNamesPromise']();

				// this bit is to handle the players
				if(rank_to_edit.message.players === null){

					// this first bit is when the players haven't been assigned yet
					for(let i = 0; i < rank_to_edit.message.tops; i++){
						let player_number = i + 1;
						let player_details = specializedLayerBinder.ranking['getPlayerDetails'](player_number);
						$(".ranking-players").append(player_details);
						let user_name_dd = specializedLayerBinder.ranking['buildUserNameDropDown'](user_name_values.message);
						$(".player-" + player_number + ".details > .user-name").html(user_name_dd);
					}

				}else{

					// this is to help the backend side to know if to update or insert or both
					let update_switch = '<input type="hidden" class="rank_result_update" name="rank_result_update" value="1">';
					$(".ranking-players").append(update_switch);

					// this second bit is when the player were assigned and the admin wants to do some editing
					for(let i = 0; i < Object.keys(rank_to_edit.message.players).length; i++) {
						let player_number = i + 1;
						let player_details = specializedLayerBinder.ranking['getPlayerDetails'](player_number, rank_to_edit.message.players[i].rank_result_id);

						$(".ranking-players").append(player_details);

						// name selection
						let user_name_dd = specializedLayerBinder.ranking['buildUserNameDropDown'](user_name_values.message, rank_to_edit.message.players[i].name);
						$(".player-" + player_number + ".details > .user-name").html(user_name_dd);


						// lastname selection
						let user_lastname_values = await specializedLayerBinder.ranking['getUsersLastnamesPromise'](rank_to_edit.message.players[i].name);
						let user_lastname_dd = specializedLayerBinder.ranking['buildUserLastnameDropDown'](user_lastname_values.message, rank_to_edit.message.players[i].lastname);
						$(".player-" + player_number).find(" > .user-lastname").html(user_lastname_dd);
						specializedLayerBinder.ranking['hideShowUserSections']("player-" + player_number, 'show', 1);

						// show the display name options only if there is a nickname
						if(!$.isEmptyObject(rank_to_edit.message.players[i].nickname)){
							// nickname selection
							let user_nickname_values = await specializedLayerBinder.ranking['getUsersNicknamesPromise'](rank_to_edit.message.players[i].name, rank_to_edit.message.players[i].lastname);
							let user_nickname_dd = specializedLayerBinder.ranking['buildUserNicknameDropDown'](user_nickname_values.message, rank_to_edit.message.players[i].player_name_display);
							$(".player-" + player_number).find(".user-nickname").html(user_nickname_dd);
							specializedLayerBinder.ranking['hideShowUserSections']("player-" + player_number, 'show', 2);

							if(rank_to_edit.message.players[i].player_name_display !== 'NAME'){
								specializedLayerBinder.ranking['hideShowUserSections']("player-" + player_number, 'show', 4);
								specializedLayerBinder.ranking['userDisplayNameOptions']();
							}
							fourthLayerBinder.ranking();

						}

						// set player score
						specializedLayerBinder.ranking['setPlayerScore']("player-" + player_number, rank_to_edit.message.players[i].player_score);
						specializedLayerBinder.ranking['hideShowUserSections']("player-" + player_number, 'show', 3);

						// set player name display options
						specializedLayerBinder.ranking['forceRadioCheck']("player-" + player_number, rank_to_edit.message.players[i].player_name_display);
						specializedLayerBinder.ranking['playerNameOptions']("player-" + player_number, rank_to_edit.message.players[i].player_name_display);

						// then activate all the features
						thirdLayerBinder.ranking();

					}

				}
				// i will have to bind two different options depending on if there are users selected or if they need to be selected
				// this is when you select the user for the first time
				secondLayerBinder.ranking['rankingUserName']();
				secondLayerBinder.ranking['rankingTops']();

				// this last bit is to leave out the buttons from the clicking
			}).children().not(".rank-actions.item, .status-modifier, .rank-delete");

			// this bit is to manage the modification of the status of a ranking
			$(".status-modifier").click(function(e){
				// this prevents clicking the parent div
				e.stopPropagation();
				let rank_id = $(this).closest(".ranking-wrapper.item").data('rank-id');
				let status_value = $(this).attr("future-rank-stat");
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
			$(".user-name").change( async function(){
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
					let user_lastname_values = await specializedLayerBinder.ranking['getUsersLastnamesPromise'](user_name);
					let user_lastname_dd = specializedLayerBinder.ranking['buildUserLastnameDropDown'](user_lastname_values.message);
					$("." + player_index).find(" > .user-lastname").html(user_lastname_dd);

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
		$(".user-lastname").change(async function(){
			let player_index = specializedLayerBinder.ranking['findClosestIndex'](this);
			let user_lastname = $(this).val();
			let user_name = $(this).prev().prev("select.user-name").find(":selected").val(); // with only 1 "prev()" it would check the label instead of the select
			if(user_lastname !== ''){
				let user_nickname_values = await specializedLayerBinder.ranking['getUsersNicknamesPromise'](user_name, user_lastname);

				if(!$.isEmptyObject(user_nickname_values.message)){ // if there are no nicknames, there's no point in doing the dropdown
					let user_nickname_dd = specializedLayerBinder.ranking['buildUserNicknameDropDown'](user_nickname_values.message);

					$("." + player_index).find(".user-nickname").html(user_nickname_dd);
					specializedLayerBinder.ranking['hideShowUserSections'](player_index, 'show', 2);
					fourthLayerBinder['ranking']();
				}else{
					$("." + player_index).find(".user-nickname").html('');
					$("." + player_index).find(' > label.hide-detail[for="user-nickname"]').css('display', 'none');
					$("." + player_index).find(' > select.user-nickname').css('display', 'none').prop('disabled', 'disabled');
				}
				if(user_nickname_values.message !== null){
					specializedLayerBinder.ranking['playerNameOptions'](player_index, user_nickname_values.message);
				}else{
					specializedLayerBinder.ranking['playerNameOptions'](player_index);
				}
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
	gallery:{
		reviewGalleryImagePositions: async function(){
			// blur the screen
			$(".gallery_images__wrapper").addClass("disabled");
			// get all images displayed in the gallery
			let gallery_images = $(".gallery_images__wrapper").children(".uploaded_image");
			// let update_obj = {};
			let update_arr = [];
			$.each(gallery_images, function(index){
				// I need the id and position of each image
				// update_obj[(index + 1)] = $(this).data("image-id"); // option with object
				// option with mix of array an object
				update_arr.push({
					id: $(this).data("image-id"),
					image_order: (index + 1)
				});

			});
			// let update_position_reponse = await specializedLayerBinder.gallery['updateGalleryImagePosition'](update_obj);
			let update_position_reponse = await specializedLayerBinder.gallery['updateGalleryImagePosition'](update_arr);
			if(update_position_reponse.status === 'success'){
				console.log('update positions ok');
				// unblur the screen
				$(".gallery_images__wrapper").removeClass("disabled");
				$(".sidebar__item.gallery").trigger('click');
			}else{
				console.log('error while updating positions');
			}

		},
		updateGalleryImagePosition: async function(images_position){
			return new Promise( (resolve, reject) => {
				$.ajax({
					method: "POST",
					url: base_url + "/gallery/updateimagepositions",
					data: {
						new_positions: images_position,
					},
					dataType: "json"
				}).then( (response) => {
					resolve(response);
				})
			});
		},
		toggleImageDisplay: async function(image_id, image_gallery_status){
			return new Promise((resolve, reject) => {
				$.ajax({
					method: "POST",
					url: base_url + "/gallery/toggleimagedisplay",
					data: {
						id: image_id,
						status: image_gallery_status
					},
					dataType: "json"
				}).then((response) => {
					resolve(response);
				});
			})
		}
	},

	ranking: {
		getRankToEditPromise: async function(rank_id){
			return new Promise(function(resolve, reject){
				$.ajax({
					method: "GET",
					url: base_url + "/ranking/getranktoedit/" + rank_id,
					dataType: 'json'
				}).then((response) => {
					resolve(response);
				});
			});
		},
		getUsersNamesPromise: async function(){
			return new Promise(function(resolve, reject){
				$.ajax({
					method: "POST",
					url: base_url + "/users/dropdowns",
					data: {
						field: "name"
					},
					dataType: "json"
				}).then(function(response){
					resolve(response);
				});
			});
		},
		getUsersLastnamesPromise: async function(user_name){
			return new Promise(function (resolve, reject){
				$.ajax({
					method: "POST",
					url: base_url + "/users/dropdowns",
					data: {
						field: 'lastname',
						control_field: 'name',
						control_value: user_name
					},
					dataType: "json"
				}).then(function(response){
					resolve(response);
				})
			});
		},
		getUsersNicknamesPromise: async function(user_name, user_lastname){
			return new Promise(function(resolve, reject){
				let control_field = ['name', 'lastname'];
				let control_value = [user_name, user_lastname];
				$.ajax({
					method: "POST",
					url: base_url + "/users/dropdowns",
					data: {
						field: 'nickname',
						control_field: control_field,
						control_value: control_value
					},
					dataType: "json"
				}).then(function(response){
					resolve(response);
				});
			});
		},
		buildUserNameDropDown: function(dd_values, selected_option = null){
			let dd_options = '<option value="">Select a user name</option>';
			$.each(dd_values, function (key, value) {
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
			return dd_options;
		},
		buildUserLastnameDropDown: function(dd_values, selected_option = null){
			let dd_options = '<option value="">Select a user lastname</option>';
			$.each(dd_values, function (key, value) {
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
			return dd_options;
		},
		buildUserNicknameDropDown: function(dd_values, selected_option = null){
			let dd_options = '<option value="">Select a user nickname</option>';
			$.each(dd_values, function(key,value){
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
			return dd_options;
		},
		getPlayerDetails: function(player_number, rank_result_row_id = null){
			let rank_result_id = '';
			if(rank_result_row_id !== null){
				rank_result_id = '<input type="hidden" class="rank_result_row" name="rank_result_row-player' + player_number + '" value="' + rank_result_row_id + '">';
			}
			let player_details = '<label for="player-' + player_number + '">Player ' + player_number + ': <span class="player-' + player_number + ' display-name"></span></label>' +
				'<div class="player-' + player_number + ' details">' +
				rank_result_id +
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
				'<input type="radio" name="user-name-display_player' + player_number + '" value="name" selected>' +
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
		addOrRemovePlayers: async function(new_tops){
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
					let user_name_values = await specializedLayerBinder.ranking['getUsersNamesPromise']();
					let user_name_dd = specializedLayerBinder.ranking['buildUserNameDropDown'](user_name_values.message);
					$(".player-" + current_players_number + " > .user-name").html(user_name_dd);

					// bind with the function that controls the use of that bit
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
				case 'COMBINED':case 'combined':
					player_name = user_name + ' "' + user_nickname + '" ' + user_lastname;
					break;
				case 'NICKNAME':case 'nickname':
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
			$("." + player_node).find("input[type=radio][value='" + radio_value.toLowerCase() + "']").prop('checked', true);
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

	upload: {
		deleteImage: async function(image_id, image_filename){
			return new Promise((resolve, reject) => {
				$.ajax({
					method: "POST",
					url: base_url + "/upload/delete",
					data: {
						id: image_id,
						filename: image_filename
					},
					dataType: "json"
				}).then((response) => {
					resolve(response);
				})
			});
		},
		togglePublishStatus: async function(image_id, image_publish_status){
			return new Promise((resolve, reject) => {
				$.ajax({
					method: "POST",
					url: base_url + "/upload/togglepublish",
					data: {
						id: image_id,
						publish_status: image_publish_status
					},
					dataType: "json"
				}).then((response) => {
					resolve(response);
				});
			});
		},
		renameImage: async function(image_id, image_new_filename){
			return new Promise((resolve, reject) => {
				$.ajax({
					method: "POST",
					url: base_url + "/upload/rename",
					data: {
						id: image_id,
						new_filename: image_new_filename
					},
					dataType: "json"
				}).then((response) => {
					resolve(response);
				})
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
