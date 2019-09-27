// $('.approve-btn').click(function(){
//   location.reload();
// })
// $('.approve-btn').click(function(){
//   // alert();
// var $this = $(this);
// $this.toggleClass('active');
// if($this.hasClass('active')){
//    $this.text('Approve');
// } else {
//    $this.text('Disapprove');
// }
// });
$('.sendmail tr').click(function(){

  var subject =  $(this).find('.subject').text();
  var content= $(this).find('a').text();
  $('.modal-body h4').text(subject);
  $('.modal-body p').text(content)
});

$('input:radio[name="association"]').change(
    function(){
        var a = null;

        if (this.checked && this.value == 'visa') {

          $("#visa-auto").removeClass('hidden').show();
          $("#package-auto").addClass('hidden').hide();
            a = "visa";
          $('.preloader').removeClass('package').addClass('visa');
          // console.log("visa");

        }
        if (this.checked && this.value == 'package') {
          $("#package-auto").removeClass('hidden').show();
          $("#visa-auto").addClass('hidden').hide();
          // console.log("pack");
          $('.preloader').removeClass('visa').addClass('package');

        }
        if (this.checked && this.value == 'no') {
          $("#package-auto").addClass('hidden').hide();
          $("#visa-auto").addClass('hidden').hide();

          // $('.preloader').removeClass('visa').addClass('package');

        }

    });



  $(".plot").click(function() {
    $(".plot").toggle(
     $('.plot').html('<i class="fa fa-pause" aria-hidden="true"></i>')
      //$('.plot').html('<i class="fa fa-play" aria-hidden="true"></i>')
   );
     //$('.plot').html('<i class="fa fa-play" aria-hidden="true"></i>'));
});

$("#select_img").click(function() {
    $(".img_view").hide();
    $(".drop_view").removeClass('hidden').show();
});


function delete_drivers() {
  alert();
  location.reload();
}
function changeStatus(id) {
					console.log(id);
					var url = BaseurlAll+"/device/change_status";
					var csrfToken = $('meta[name="csrf-token"]').attr("content");
					//console.log($('meta[name="csrf-token"]').attr("content"));
					$.ajax({
					 url:url,
					 data:  {'id':id,"_csrf": yii.getCsrfToken()},
					 cache: false,
					 type:'POST',
					 //dataType: 'json',
					 success: function(res) {
						 if(res == 1) {
							 $('#approve-'+id).removeClass(' label label-success').addClass('label label-danger').text('Unban');

					 }
					 else {
						 	 $('#approve-'+id).removeClass('label label-danger').addClass('label label-success').text('Ban');

					 }

					 },
	           });
	}
       function changeCompStatus(id) {
  					var url = BaseurlAll+"/company/change_status";
  					var csrfToken = $('meta[name="csrf-token"]').attr("content");
  					//console.log($('meta[name="csrf-token"]').attr("content"));
  					$.ajax({
  					 url:url,
  					 data:  {'id':id,"_csrf": yii.getCsrfToken()},
  					 cache: false,
  					 type:'POST',
  					 //dataType: 'json',
  					 success: function(res) {
  						 if(res == 1) {
  							 $('#approve-'+id).removeClass(' label label-success').addClass('label label-danger').text('Unban');

  					 }
  					 else {
  						 	 $('#approve-'+id).removeClass('label label-danger').addClass('label label-success').text('Ban');

  					 }

  					 },
  	           });
  	}

    function changeReviewStatus(id) {
      console.log(id);
         var url = BaseurlAll+"/reviews/change_status";
         var csrfToken = $('meta[name="csrf-token"]').attr("content");
         //console.log($('meta[name="csrf-token"]').attr("content"));
         $.ajax({
          url:url,
          data:  {'id':id,"_csrf": yii.getCsrfToken()},
          cache: false,
          type:'POST',
          //dataType: 'json',
          success: function(res) {
            if(res == 1) {
              $('#approve-'+id).removeClass('btn-success').addClass('btn-danger').text('Unban');

          }
          else {
              $('#approve-'+id).removeClass('btn-danger').addClass('btn-success').text('Ban');

          }

          },
            });
 }

 function changeAlbumStatus(id) {
      var url = BaseurlAll+"/gallery/change_status";
      var csrfToken = $('meta[name="csrf-token"]').attr("content");
      //console.log($('meta[name="csrf-token"]').attr("content"));
      $.ajax({
       url:url,
       data:  {'id':id,"_csrf": yii.getCsrfToken()},
       cache: false,
       type:'POST',
       //dataType: 'json',
       success: function(res) {
         if(res == 1) {
           $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Unban');

       }
       else {
           $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Ban');

       }

       },
         });
}

function changeAgentStatus(id) {
     var url = BaseurlAll+"/agents/change_status";
     var csrfToken = $('meta[name="csrf-token"]').attr("content");
     //console.log($('meta[name="csrf-token"]').attr("content"));
     $.ajax({
      url:url,
      data:  {'id':id,"_csrf": yii.getCsrfToken()},
      cache: false,
      type:'POST',
      //dataType: 'json',
      success: function(res) {
        if(res == 1) {
          $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Unban');

      }
      else {
          $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Ban');

      }

      },
        });
}

function changeUserStatus(id) {
     var url = BaseurlAll+"/users/change_status";
     var csrfToken = $('meta[name="csrf-token"]').attr("content");
     //console.log($('meta[name="csrf-token"]').attr("content"));
     $.ajax({
      url:url,
      data:  {'id':id,"_csrf": yii.getCsrfToken()},
      cache: false,
      type:'POST',
      //dataType: 'json',
      success: function(res) {
        if(res == 1) {
          $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Unban');

      }
      else {
          $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Ban');

      }

      },
        });
}

function changeBookingStatus(id) {
     var url = BaseurlAll+"/bookings/change_status";
     var csrfToken = $('meta[name="csrf-token"]').attr("content");
     //console.log($('meta[name="csrf-token"]').attr("content"));
     $.ajax({
      url:url,
      data:  {'id':id,"_csrf": yii.getCsrfToken()},
      cache: false,
      type:'POST',
      //dataType: 'json',
      success: function(res) {
        if(res == 1) {
          $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Disapprove');

      }
      else {
          $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Approve');

      }

      },
        });
}

function changeCancellationStatus(id) {
     var url = BaseurlAll+"/bookings/change-cancellation-status";
     var csrfToken = $('meta[name="csrf-token"]').attr("content");
     //console.log($('meta[name="csrf-token"]').attr("content"));
     $.ajax({
      url:url,
      data:  {'id':id,"_csrf": yii.getCsrfToken()},
      cache: false,
      type:'POST',
      //dataType: 'json',
      success: function(res) {
        if(res == 1) {
          $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Disapprove');

      }
      else {
          $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Approve');

      }

      },
        });
}
 function changeReviewStatusTour(id) {
   console.log(id);
      var url = BaseurlAll+"/reviews/change_status";
      var csrfToken = $('meta[name="csrf-token"]').attr("content");
      //console.log($('meta[name="csrf-token"]').attr("content"));
      $.ajax({
       url:url,
       data:  {'id':id,"_csrf": yii.getCsrfToken()},
       cache: false,
       type:'POST',
       //dataType: 'json',
       success: function(res) {
         if(res == 1) {
           $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Unban');

       }
       else {
           $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Ban');

       }

       },
         });
}

function changeVisaStatus(id) {
  console.log(id);
     var url = BaseurlAll+"/visas/change-status";
     var csrfToken = $('meta[name="csrf-token"]').attr("content");
     //console.log($('meta[name="csrf-token"]').attr("content"));
     $.ajax({
      url:url,
      data:  {'id':id,"_csrf": yii.getCsrfToken()},
      cache: false,
      type:'POST',
      //dataType: 'json',
      success: function(res) {
        if(res == 1) {
          $('#approved-'+id).removeClass('btn-danger').addClass('btn-success').text('Unpublish');

      }
      else {
          $('#approved-'+id).removeClass('btn-success').addClass('btn-danger').text('Publish');

      }

      },
        });
}

 function changeVehicleStatus(id) {
   // console.log(id);
      var url = BaseurlAll+"/vehicles/change_status";
      var csrfToken = $('meta[name="csrf-token"]').attr("content");
      //console.log($('meta[name="csrf-token"]').attr("content"));
      $.ajax({
       url:url,
       data:  {'id':id,"_csrf": yii.getCsrfToken()},
       cache: false,
       type:'POST',
       //dataType: 'json',
       success: function(res) {
         if(res == 1) {
           $('#approve-'+id).removeClass(' btn-success').addClass('btn-danger').text('Unban');

       }
       else {
           $('#approve-'+id).removeClass('btn-danger').addClass('btn-success').text('Ban');

       }
       },
         });
}



    function deleteListItem(cntSel,url,page,onFinished) {
       $(cntSel).fadeOut();
       if(url) {
         $.get(url,function(response) {

           (onFinished)();
        });
      }
    }
    // function deleteItem(settings) { // used in dashboard sections
    //   alert(settings);
    //   var pjaxCnt =  settings.pjaxContainer;
    //   var url = settings.url?settings.url:null;
    //   var page = settings.page?settings.page:null;
    //   var onFinished = settings.onFinished?settings.onFinished:null;
    //   //$(pjaxCnt).fadeOut();
    //   if(url) {
    //     $.get(url,function(response) {
    //       dismissAllPopovers();
    //       window.setTimeout(function() {
    //         refreshPjaxListView(pjaxCnt,page);
    //       },500);
    //       if(onFinished)
    //       (onFinished)();
    //    });
    //
    //   }
    // }
    function ConfirmDelete(fn_name) {

      swal({
        title: "Are you sure?",
               text: "You will not be able to undo this action!",
               type: "warning",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: "Yes, delete it!",
               cancelButtonText: "No, cancel please!",
               closeOnConfirm: true,
               closeOnCancel: true,
                timer: 1000,
         }, function(isConfirm){
          if (isConfirm) {
              fn_name();
          } else {
            //  swal(settings.noActionButtonText);
          }
      });

    }

    function deleteItem(url,pjaxCnt,page,onFinished) {
      console.log(url);
      var pjaxCnt = pjaxCnt;
      var url = url;
      var page =page;
      if(url) {
        $.get(url,function(response) {
          dismissAllPopovers();
          window.setTimeout(function() {
            refreshPjaxListView(pjaxCnt,page);
          },500);
          if(onFinished)
          (onFinished)();
       });

      }
    }

    function deletePageItem(url,pjaxCnt,onFinished) {
      console.log(url);
      var pjaxCnt = pjaxCnt;
      var url = url;
      var page =page;
      if(url) {
        $.get(url,function(response) {
          dismissAllPopovers();
          window.setTimeout(function() {
            refreshPjaxListView(pjaxCnt,page);
          },500);
          if(onFinished)
          (onFinished)();
       });

      }
    }

    function deleteMainItem(url,redirectUrl,onFinished) {
      // console.log(url);
      var url = url;
      var redirectUrl = redirectUrl;
      if(url) {
        $.get(url,function(response) {
          // dismissAllPopovers();
          window.setTimeout(function() {
            window.location.replace(redirectUrl);
          },500);
       });

      }
    }

    function refreshPjaxListView(pjaxSel,page) {
      var options = { replace:false,timeout:50000,push:false };
      if(page) {
        var firstPageSel  = $('.pagination li [data-page="0"]');
        if(firstPageSel) {
          var url = firstPageSel.attr('href');
          if(url) {
            url = url.replace('page=1','page='+page);
            options.url = url;
          }
        }
      }
      $.pjax.reload(pjaxSel,options );
    }

    function dismissAllPopovers(that) {
       if(!that) {
        $('[data-toggle=popover]').popover('hide');
      } else {
        $('[data-toggle=popover]').not(that).popover('hide');
      }
      $('.popover').hide();//not needed..but sometimes the other code wont work

    }


    function assignPjaxLoaders() {
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

  });
}

function assignDropify()
{
  // Basic
  // console.log("sdfs");
  $('.dropify').dropify();
  // Translated
  $('.dropify-fr').dropify({
      messages: {
          default: 'Glissez-déposez un fichier ici ou cliquez'
          , replace: 'Glissez-déposez un fichier ou cliquez pour remplacer'
          , remove: 'Supprimer'
          , error: 'Désolé, le fichier trop volumineux'
      }
  });
  // Used events
  var drEvent = $('#input-file-events').dropify();
  drEvent.on('dropify.beforeClear', function (event, element) {

  });
  drEvent.on('dropify.afterClear', function (event, element) {
      alert('File deleted');
  });
  drEvent.on('dropify.errors', function (event, element) {
      console.log('Has Errors');
  });
  var drDestroy = $('#input-file-to-destroy').dropify();
  drDestroy = drDestroy.data('dropify')
  $('#toggleDropify').on('click', function (e) {
      e.preventDefault();
      if (drDestroy.isDropified()) {
          drDestroy.destroy();
      }
      else {
          drDestroy.init();
      }
  })

}
function setTgInputs() {

  $('.tg-input-hld .bootstrap-tagsinput input').focus( function() {
     document.activeElement.blur();
  });
}
$(document).ready(function() {
  var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            // $('#booking-end_date').click(function(ev) {
            //             ev.preventDefault();
            //             console.log("<?php echo Url::base(); ?>/tbapp/updatestatus")
            //
            //             $.ajax({
            //                 type: 'GET',
            //                 dataType: 'JSON',
            //                 url: '<?php echo Yii::app()->createUrl("bookings/cancellation-date"); ?>',
            //                 success:function(data){
            //                   alert(data);
            //                 //    if(data !==  null)
            //                     {
            //                    //     $('#Text_group').val(data);
            //                     //    $('#text-form').submit();
            //                          alert("not Error occured!!!.");
            //                     }
            //                 },
            //                 error: function() {
            //                     alert("Error occured!!!.");
            //                 },
            //             });
            //
            //             return false;
            //         });

   assignDropify();
   assignPjaxLoaders();
   setTgInputs();
});
