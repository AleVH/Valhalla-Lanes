$(document).ready(function(){
   console.log('loading Valhalla-Lanes javascript');
	let base_url = window.location.origin;

	// menu animation
   $(".menu").click(function () {
   		// this.classList.toggle("revealX"); // this will flip the menu tab so you can see the options, better if this happens without delay
	   this.classList.add("revealX");// this does the same as the line above but without the toggle, so if you click again on it, doesn't go back to "menu"
	   let items  = $(".menu__navigation > div");
	   for(let i = 0; i < items.length; i++){
		   (function(i){
			   setTimeout(function(){
				   $(".menu__navigation > div").eq(i).addClass("revealY");
			   }, 500 * i);
		   }(i));
	   }

   });

   // menu items
	$(".menu__item").click(function(e){
		// e.preventDefault();
		// e.stopPropagation();
		// console.log("clicked an item");
		// console.log("Data: " + $(this).data('link'));
		let section = "/" + $(this).data('link');
		$.ajax({
			method: "POST",
			url: base_url + section,
			dataType: "json"
		}).done(function(response){
			if(response.status === "success"){
				console.log("success");
				$(".body__wrapper").html(response.message);
			}else{
				console.log("error");
				$(".body__wrapper").html("ERROR" + response.message);
			}
		});
	});

   	// admin login
	$("#admin__login").on("submit", function (e) {
		// console.log("ajax routine");
		e.preventDefault();
		let user = $("#user").val();
		let password = $("#password").val();
		// console.log('user: ' + user);
		// console.log('pw: ' + password);
		$.ajax({
				method : "POST",
				url : base_url + "/admin/login",
				data : {
					user : user,
					password : password
				},
				dataType : "json"
			}
		).done(function (response) {
			if(response.status === 'success'){
				// console.log('success');
				// console.log('resp: ' + response.message);
				window.location.replace(response.message);
			}else{
				// console.log('error');
				// this is for when something is wrong with the credentials
				$('.admin__login.error').html(response.message).css({'display' : 'block', 'opacity' : '1', 'color' : 'red'});
				$('.admin__login.error').fadeOut(3200, function(){
					$(this).html('&nbsp;').css('display', '');
				});
			}
		});
	});
});
