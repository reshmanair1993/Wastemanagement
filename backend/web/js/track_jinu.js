
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
               text: "You willnot be able to undo this action!",
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

    function deleteMainItem(url,redirectUrl,onFinished) {
      console.log(url);
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

$(document).ready(function() {
   assignDropify();
   assignPjaxLoaders();
});
