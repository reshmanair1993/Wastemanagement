

       function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cate_img').attr('src', e.target.result);

            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function assignPjaxLoaders() {
      var spinner = null;
      var opts = {
  lines: 5 // The number of lines to draw
, length: 2 // The length of each line
, width: 19 // The line thickness
, radius: 11 // The radius of the inner circle
, scale: 0.75 // Scales overall size of the spinner
, corners: 0.3 // Corner roundness (0..1)
, color: 'rgb(57,0,121)' // #rgb or #rrggbb or array of colors
, opacity: 0.15 // Opacity of the lines
, rotate: 42 // The rotation offset
, direction: 1 // 1: clockwise, -1: counterclockwise
, speed: 0.5 // Rounds per second
, trail: 81 // Afterglow percentage
, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
, zIndex: 2e9 // The z-index (defaults to 2000000000)
, className: 'spinner' // The CSS class to assign to the spinner
, top: '50%' // Top position relative to parent
, left: '50%' // Left position relative to parent
, shadow: false // Whether to render a shadow
, hwaccel: true // Whether to use hardware acceleration
, position: 'absolute' // Element positioning
};
      $(document).on('pjax:send', function(event) {
        var container = event.target;
        var container = $(container);
        var loaderSelector = container.attr('data-loader');
        if(loaderSelector)
          loader = $(loaderSelector);
        else
          loader = container.find('.loading');
        container.hide();
        loader.removeClass('hidden').show();

        var target = document.body;
        spinner = new Spinner(opts).spin(target);
      });
      $(document).on('pjax:complete', function(event) {
        var container = event.target;
        var container = $(container);
        var loaderSelector = container.attr('data-loader');
        if(loaderSelector)
          loader = $(loaderSelector);
        else
          loader = container.find('.loading');
        container.show();
        loader.hide();

        spinner.stop();
      });
    }
function listingLogo(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#list_img').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

  function showImagePreview(input,imgSelector) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(imgSelector).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }

  }
  function initTinymce() {
    tinymce.EditorManager.editors = []; //remove previous instances if any
    tinymce.init({
          selector: '.tinymce',
          plugins: 'image imagetools,code',
          mode : 'textareas',
          force_br_newlines : false,
          force_p_newlines : false,
          forced_root_block : '',
          toolbar: 'img-btn',
          height:'200',
          paste_data_images : true,
          setup: function(editor) {
            editor.on('init', function(args) {
              editor = args.target;
              editor.on('NodeChange', function(e) {
                if (e && e.element.nodeName.toLowerCase() == 'img') {
                  width = e.element.width;
                  height = e.element.height;
                  tinyMCE.DOM.setAttribs(e.element, {'width': null, 'height': null});
               }
             });
           });
           editor.addButton('img-btn', {
             text:"",
             icon: true,
             onclick: function(e) {
               if($(e.target).prop('tagName') == 'I'){
                 if($(e.target).parent().parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                   $(e.target).parent().parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image" style="display:none">');
                 }
                 $('#tinymce-uploader').trigger('clic');
                 $('#tinymce-uploader').change(function(){
                var input, file, fr, img;
                if (typeof window.FileReader !== 'function') {
                  write('The file API isnt supported on this browser yet.');
                  return;
                }
                input = document.getElementById('tinymce-uploader');
                if (!input) {  alert(2);
                  write('Um, couldnt find the imgfile element.');
                }
                else if (!input.files) {
                  write('This browser doesnt seem to support the files property of file inputs.');
                }
                else if (!input.files[0]) {
                  write('Please select a file before clicking Load');
                }
                else {
                  file = input.files[0];
                  fr = new FileReader();
                  fr.onload = createImage;
                  fr.readAsDataURL(file);
                } console.log(fr);

                function createImage() {
                  img = new Image();
                  img.src = fr.result;
                }
            })
          }
       }
    })
    }
  });
  }
	function makeSlug(str,selector){
		var trimmed = $.trim(str);
        str = str.toLowerCase();
        str = str.replace(/[^a-zA-Z0-9]+/g,'-');
        $(selector).val(str);
	}


 function initTimepickers() {
   $('.timepicker').timepicker();
 }
	$(document).ready(function(){
		//console.log('jquery is ');
		//console.log($);
    assignPjaxLoaders();
		$("#categories-name").keyup(function(){
           makeSlug(this.value,"#categories-slug");
        }).change(function(){
           makeSlug(this.value,"#categories-slug");
        });


		$("#primag").change(function(){
        readProdImg(this);
		 });
		 $("#categories-fk_category_image").change(function(){
        readURL(this);
		 });


		 	$("#listing-fk_listing_image").change(function(){
				listingLogo(this);
			});
		    $("#list_img").click(function(){
			$("#listing-fk_listing_image").click();
			});


			//user view image//



				//user view image//

			$("#tbperson-fk_person_image").change(function(){
				readUserImg(this);
			});
		    $("#user_img").click(function(){
			$("tbperson-fk_person_image").click();
			});
			/* $("#tbperson-fk_person_image").change(function(){
				readUserImg(this);

			}); */







	     $("#cvideo").change(function(){
         $("#video").attr("src", $("#cvideo").val());

		 });

		 	$("#contry").change(function(){
				getLocation(this.value,"#state");

	        });

			$("#state").change(function(){
				getDistrict(this.value,"#district");

	        });
			$("#district").change(function(){
				getCity(this.value,"#city");

	        });

		 $('.datepicker').daterangepicker({
             singleDatePicker: true,
             calender_style: "picker_4",
			  format: 'DD-MM-YYYY'
          }
		 );

     initTimepickers();





	$("#tblistingimage-fk_listing_image_image").on('change', function () {
    var countFiles = $(this)[0].files.length;

    var imgPath = $(this)[0].value;
    var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    var image_holder = $("#result");
    image_holder.empty();

    if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
        if (typeof (FileReader) != "undefined") {

            for (var i = 0; i < countFiles; i++) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $("<img/>", {
                        "src": e.target.result,
                            "class": "margin",
                             "style":"height:150px;width:200px;padding:12px;",
                    }).appendTo(image_holder);
                }

                image_holder.show();
                reader.readAsDataURL($(this)[0].files[i]);
            }

        } else {
            alert("It doesn't supports");
        }
    } else {
        alert("Select Only images");
    }
});

	});

	function getLocation(val,selector)
	{
	   var url = BaseurlAll+"/listings/getlocation";
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
	 $.ajax({
     url:url,
     data: {val:val},
     cache: false,
     type:'POST',
     //dataType: 'json',
     success: function(state) {
	 state = jQuery.parseJSON(state);

		 console.log(state);

	   var options=$('<select/>');

      $.each(state,function(value,text) {  console.log(text);  console.log(value);

      options.append('<option value=' + text.value + '>' + text.text + '</option>');
    });
     $('#state').html(options.html());
	 }
  });
	}


	function getDistrict(val,selector)
	{
		alert(val,selector);
	   var url = BaseurlAll+"/listings/getdistrict";
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
	 $.ajax({
     url:url,
     data: {val:val},
     cache: false,
     type:'POST',
     //dataType: 'json',
     success: function(state) {
	 state = jQuery.parseJSON(state);


	   var options=$('<select/>');

      $.each(state,function(value,text) {  console.log(text);  console.log(value);

      options.append('<option value=' + text.value + '>' + text.text + '</option>');
    });
     $('#district').html(options.html());
	 },
	 error:function(){
		 console.log(state);
	 }
  });
	}
	function getCity(val,selector)
	{
	   var url = BaseurlAll+"/listings/getcity";
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
	 $.ajax({
     url:url,
     data: {val:val},
     cache: false,
     type:'POST',
     //dataType: 'json',
     success: function(state) {
	 state = jQuery.parseJSON(state);

		 console.log(state);

	   var options=$('<select/>');

      $.each(state,function(value,text) {  console.log(text);  console.log(value);

      options.append('<option value=' + text.value + '>' + text.text + '</option>');
    });
     $('#city').html(options.html());
	 },
	 error:function(){
		 console.log(state);
	 }
  });
	}

	$("#service").click(function(e){
		e.preventDefault();
	});
	$("#product").click(function(e){
		e.preventDefault();
	});



	function addService(id)
	{
    var url = BaseurlAll+"/listings/addservice";
	console.log(url);
    var name = $("#sername").val();
	var discription = $("#serdescription").val();
	var price = $("#serprice").val();
	var listing_id = id;

	$('.res_service').html("");
	$.ajax({
     url:url,
     data: {'id':id,'name':name,'discription':discription,'price':price},
     cache: false,
     type:'POST',
     //dataType: 'json',
     success: function(res) {
		 console.log(res);
		 res = jQuery.parseJSON(res);
          var name = res.name;
		  var description= res.description;
		  var price= res.price;
			 var html = $('#res_service').html(); alert(html);
           html = res.response;
                                  $("#res_service").append(html);

	 }
  });


	}


	function addproduct(id)
	{
    var url = BaseurlAll+"/listings/addproduct";
    var name = $("#prname").val();
	var description = $("#prdescription").val();
	var price = $("#prprice").val();
	var listing_id = id;

	 var files = $('#primag').prop('files')[0]; console.log(files);
	 var file = null;
    if(files.length) {
		 file = files;
	}
	$('.res_products').html("");
	var formData = new FormData();

	formData.append('id',listing_id);
	formData.append('file', files);
	formData.append('name', name);
	formData.append('description',description);
	formData.append('price', price);
	$.ajax({
     url:url,
     processData: false,
     contentType: false,
     data:  formData,
     cache: false,
     type:'POST',
     //dataType: 'json',
     success: function(res) {
		 console.log(res);
		 res = jQuery.parseJSON(res);  console.log(res);
          var name = res.name; alert(name);
		  var description= res.description;
		  var price= res.price;
			 var html = $('#res_product').html();
           html = res.response;
            $("#res_product").append(html);


	 },
	 error:function(res){
		 console.log("df");
		 console.log(res);
	 }
  });
	}
	function readProdImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#prod_img').attr('src', e.target.result);

            }

            reader.readAsDataURL(input.files[0]);
        }
    }

	function readUserImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#user_img').attr('src', e.target.result);

            }

            reader.readAsDataURL(input.files[0]);
        }
    }

	$(".update-user").click(function(e){
		e.preventDefault();
	});
	/* function update_user(id) {
	var url = BaseurlAll+"/users/updateuser";
	console.log(url);
    var first_name = $("#first_name").val();
	var middle_name = $("#middle_name").val();
    var last_name = $("#last_name").val();
	var gender = $("#gender").val();
	var dob = $(".datepicker").val();
	var user_id = id;
	var csrfToken = $('meta[name="csrf-token"]').attr("content"); alert(csrfToken);
	$.ajax({
     url:url,
     data:  {'user_id':id,'first_name':first_name,'middle_name':middle_name,'last_name':last_name,'_csrf':csrfToken},
     cache: false,
     type:'POST',
     //dataType: 'json',
     success: function(res) {
		//alert(res);
		console.log(res);


	 },
	 error:function(res){
		 console.log("df");
		 console.log(res);
	 }
  });


	} */
	function update_user(id) {

					var url = BaseurlAll+"/users/updateuser";
					var first_name = $("#first_name").val();
					var middle_name = $("#middle_name").val();
					var last_name = $("#last_name").val();
					var gender = $("#gender").val();
					var dob = $(".datepicker").val();
					var user_id = id;
					var csrfToken = $('meta[name="csrf-token"]').attr("content"); //alert(csrfToken);
					$.ajax({
					 url:url,
					 data:  {'csrfToken':csrfToken,'user_id':id,'first_name':first_name,'middle_name':middle_name,'last_name':last_name,'gender':gender,'dob':dob},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
						alert(res);


					 },
					 error:function(res){
						 console.log("df");
						 console.log(res);
					 }
				  });


	}
	function verfiyAccount(id,accnt) {


		         var url = BaseurlAll+"/users/verify_account";
					var csrfToken = $('meta[name="csrf-token"]').attr("content");
					$.ajax({
					 url:url,
					 data:  {'id':id,'accnt':accnt},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
					 if(res == 1) {
						 $('.accnt-verified').html("<i class ='success fa fa-check-circle-o'></i>&nbsp;&nbsp;Verified account&nbsp;&nbsp");
						 $('.verify-acc').removeClass('btn-success').addClass('btn-danger');
						   $('.verify-acc').text('Unverify');
					 }
					 else {
						  $('.accnt-verified').html("<i class ='success fa fa-times-circle'></i>&nbsp;&nbsp;Unverified account&nbsp;&nbsp");
						   $('.verify-acc').removeClass('btn-danger').addClass('btn-success');
						    $('.verify-acc').text('Verify');
					 }

					 },
	           });
	}




	     function verifyPhone(id,phone) {

		          var url = BaseurlAll+"/users/verify_phone";
					var csrfToken = $('meta[name="csrf-token"]').attr("content");
					$.ajax({
					 url:url,
					 data:  {'id':id,'phone':phone},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
					 if(res == 1) {
						 $('.phone-verified').html("<i class ='success fa fa-check-circle-o'></i>&nbsp;&nbsp;Verified phone&nbsp;&nbsp");
						 $('.verify-phn').removeClass('btn-success').addClass('btn-danger');
						   $('.verify-phn').text('Unverify');
					 }
					 else {
						  $('.phone-verified').html("<i class ='success fa fa-times-circle'></i>&nbsp;&nbsp;Unverified phone&nbsp;&nbsp");
						  $('.verify-phn').removeClass('btn-danger').addClass('btn-success');
						    $('.verify-phn').text('Verify');
					 }

					 },
	           });
	      }

		  $(".change-password").click(function(e){
		   e.preventDefault();
	         });

		  /*function change_password(id) {



			  var url = BaseurlAll+"/users/changepassword";
					//var old_pass = $("#old_pass").val();
					var new_pass = $("#new_pass").val();
                   $.ajax({
					 url:url,
					 data:  {'id':id,'new_pass':new_pass},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
					 if(res == 1){
						 $('.show-msg').html('Password Updated Successfully');
						 $("#old_pass").val('');
                          $("#new_pass").val('');
					 }
					 else if(res == -1) {
						 $('.show-msg').html('Incorrect old password');
					 }
					 else {}
					 },
	           });
		  }
		  */



			$(".edit-review").click(function () {
			 var url = BaseurlAll+"/users/getreview";
			  var id = $(this).attr('data-id');
			   console.log(id);
			  $('#hidden_id').val(id);
			   $.ajax({
					 url:url,
					 data:  {'id':id},
					 cache: false,
					 type:'POST',
					dataType: 'json',
					 success: function(res) {

						$('#review_title').val(res.detailed_info);
						$('#review_rating').val(res.rating);
						$('#review_date').val(res.date);
					 },
	           });
		});
		   $(".update-review").click(function () {
			  var url = BaseurlAll+"/users/editreview";
			  var id = $('#hidden_id').val();
			  var review_title = $("#review_title").val();
			  var review_rating = $("#review_rating").val();
			   var review_date = $("#review_date").val();
              		 $.ajax({
					 url:url,
					 data:  {'id':id,'review_title':review_title,'review_rating':review_rating,'review_date':review_date},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
					location.reload();
					 },
					 error:function(res){
						 console.log(res);
					 }
	           });
 });

  $(".delete").click(function () {
  alert();
   var id = $(this).attr('data-id');
			   console.log(id);
			  $('#hidden_id').val(id);

  });
     $(".yes-delete").click(function () {
	  var url = BaseurlAll+"/users/deletereview";
	        var id = $('#hidden_id').val();
			   $.ajax({
					 url:url,
					 data:  {'id':id},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
					   location.reload();
					 },
	           });


	 });
	function activate_user(id) {
		 var url = BaseurlAll+"/users/activateuser";

			   $.ajax({
					 url:url,
					 data:  {'id':id},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {

					 if(res == 1) {
					     $('.activate-user').removeClass('btn-success').addClass('btn-danger');
						 $('.activate-user').html('<i class="fa fa-close">Deactivate</i> ');
					 }
					 else {
						  $('.activate-user').removeClass('btn-danger').addClass('btn-success');
						  $('.activate-user').html('<i class="fa fa-check-circle-o">Activate</i> ');
					 }
					 },
	           });



	}

  function hideAllCollapses() {
    $('.collapse.in').collapse('hide'); //hide all open collapses
  }
