function initMap() {

   var uluru = {lat: 10.267789, lng: 76.354632};
   var map = new google.maps.Map(document.getElementById('map'), {
     zoom: 16,
     center: uluru,
     autoZoom: true
   });
   var marker = new google.maps.Marker({
     position: uluru,
     map: map
   });
 }

$(document).ready(function(){
    $('.img-zoom').magnificPopup({type:'image'});
         // Clock pickers
         $('#single-input').clockpicker({
             placement: 'bottom'
             , align: 'left'
             , autoclose: true
             , 'default': 'now'
         });
         $('.clockpicker').clockpicker({
             donetext: 'Done'
         , }).find('input').change(function () {
             console.log(this.value);
         });
         $('#check-minutes').click(function (e) {
             // Have to stop propagation here
             e.stopPropagation();
             input.clockpicker('show').clockpicker('toggleView', 'minutes');
         });
         if (/mobile/i.test(navigator.userAgent)) {
             $('input').prop('readOnly', true);
         }
         // Date Picker
         jQuery('.mydatepicker, #datepicker').datepicker();
         jQuery('#datepicker-autoclose').datepicker({
             autoclose: true
             , todayHighlight: true
         });
         jQuery('#date-range').datepicker({
             toggleActive: true
         });
         jQuery('#datepicker-inline').datepicker({
             todayHighlight: true
         });
         // Daterange picker
         $('.input-daterange-datepicker').daterangepicker({
             buttonClasses: ['btn', 'btn-sm']
             , applyClass: 'btn-danger'
             , cancelClass: 'btn-inverse'
         });
         $('.input-daterange-timepicker').daterangepicker({
             timePicker: true
             , format: 'MM/DD/YYYY h:mm A'
             , timePickerIncrement: 30
             , timePicker12Hour: true
             , timePickerSeconds: false
             , buttonClasses: ['btn', 'btn-sm']
             , applyClass: 'btn-danger'
             , cancelClass: 'btn-inverse'
         });
         $('.input-limit-datepicker').daterangepicker({
             format: 'MM/DD/YYYY'
             , minDate: '06/01/2015'
             , maxDate: '06/30/2015'
             , buttonClasses: ['btn', 'btn-sm']
             , applyClass: 'btn-danger'
             , cancelClass: 'btn-inverse'
             , dateLimit: {
                 days: 6
             }
         });
         var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
         $('.js-switch').each(function () {
             new Switchery($(this)[0], $(this).data());
         });


         $('.mydatepicker, #datepicker').datepicker();
         $('#datepicker-autoclose').datepicker({
             autoclose: true
             , todayHighlight: true
         });
         $('#datepicker-autoclose2').datepicker({
             autoclose: true
             , todayHighlight: true
         });
         $('#date-range').datepicker({
             toggleActive: true
         });
         $('#datepicker-inline').datepicker({
             todayHighlight: true
         });

         $('input[type="range"]').change(function () {
           var val = ($(this).val() - $(this).attr('min')) / ($(this).attr('max') - $(this).attr('min'));

           $(this).css('background-image',
                       '-webkit-gradient(linear, left top, right top, '
                       + 'color-stop(' + val + ', #4A90E2), '
                       + 'color-stop(' + val + ', #DDDDDD)'
                       + ')'
                       );
         });

         //vertical slider
         var range = $('.input-range'),
          value = $('.range-value');

          value.html(range.attr('value'));

          range.on('input', function(){
              value.html(this.value);
          });
});
