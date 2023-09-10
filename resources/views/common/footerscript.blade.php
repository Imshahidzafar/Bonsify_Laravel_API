<!-- WIDGETS -->
<script type="text/javascript" src="{{asset('assets/bootstrap/js/bootstrap.js')}}"></script>
<!-- Bootstrap Dropdown -->
<!-- <script type="text/javascript" src="{{asset('assets/widgets/dropdown/dropdown.js')}}"></script> -->
<!-- Bootstrap Tooltip -->
<!-- <script type="text/javascript" src="{{asset('assets/widgets/tooltip/tooltip.js')}}"></script> -->
<!-- Bootstrap Popover -->
<!-- <script type="text/javascript" src="{{asset('assets/widgets/popover/popover.js')}}"></script> -->
<!-- Bootstrap Progress Bar -->
<script type="text/javascript" src="{{asset('assets/widgets/progressbar/progressbar.js')}}"></script>
<!-- Bootstrap Buttons -->
<!-- <script type="text/javascript" src="{{asset('assets/widgets/button/button.js')}}"></script> -->
<!-- Bootstrap Collapse -->
<!-- <script type="text/javascript" src="{{asset('assets/widgets/collapse/collapse.js')}}"></script> -->
<!-- Superclick -->
<script type="text/javascript" src="{{asset('assets/widgets/superclick/superclick.js')}}"></script>
<!-- Input switch alternate -->
<script type="text/javascript" src="{{asset('assets/widgets/input-switch/inputswitch-alt.js')}}"></script>
<!-- Slim scroll --> 
<script type="text/javascript" src="{{asset('assets/widgets/slimscroll/slimscroll.js')}}"></script>
<!-- Slidebars -->
<script type="text/javascript" src="{{asset('assets/widgets/slidebars/slidebars.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/slidebars/slidebars-demo.js')}}"></script>
<!-- PieGage -->
<script type="text/javascript" src="{{asset('assets/widgets/charts/piegage/piegage.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/piegage/piegage-demo.js')}}"></script>
<!-- Screenfull -->
<script type="text/javascript" src="{{asset('assets/widgets/screenfull/screenfull.js')}}"></script>
<!-- Content box -->
<script type="text/javascript" src="{{asset('assets/widgets/content-box/contentbox.js')}}"></script>
<!-- Overlay -->
<script type="text/javascript" src="{{asset('assets/widgets/overlay/overlay.js')}}"></script>
<!-- datatable -->
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<!-- Widgets init for demo -->
<script type="text/javascript" src="{{asset('assets/js-init/widgets-init.js')}}"></script>
<!-- Theme layout -->
<script type="text/javascript" src="{{asset('assets/themes/admin/layout.js')}}"></script>
<!-- Theme switcher -->
<script type="text/javascript" src="{{asset('assets/widgets/theme-switcher/themeswitcher.js')}}"></script>
<!-- Sparklines charts -->
<script type="text/javascript" src="{{asset('assets/widgets/charts/sparklines/sparklines.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/sparklines/sparklines-demo.js')}}"></script>
<!-- Flot charts
<script type="text/javascript" src="{{asset('assets/widgets/charts/flot/flot.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/flot/flot-resize.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/flot/flot-stack.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/flot/flot-pie.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/flot/flot-tooltip.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/flot/flot-demo-1.js')}}"></script>
 -->
<!-- PieGage charts -->

<script type="text/javascript" src="{{asset('assets/widgets/charts/piegage/piegage.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/widgets/charts/piegage/piegage-demo.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"> </script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"> </script>
<script>
	CKEDITOR.replaceClass = 'ckeditor';
	CKEDITOR.config.width = '75%';
	CKEDITOR.config.allowedContent = true;
</script>
<script>
	$(document).ready( function () {
    $('#myTable').DataTable({

		responsive: true
	});
	

} )
$(document).ready(function() {
    var table = $('#example').DataTable( {
        responsive: true
    } );
    new $.fn.dataTable.FixedHeader( table );
} );

$('#myModal').modal('toggle')

$(".make_car_modal").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let make_name = $('#real_make_name_'+modal_car_id).val();
 let make_image = $('#real_make_image_'+modal_car_id).attr('src');
 let make_status = $('#real_make_status_'+modal_car_id).val();
 
 $('#make_id').val(modal_car_id);
 $('#make_name').val(make_name);
 $('#make_img').attr('src',make_image);
 if(make_status=="Active"){
	$('#make_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#make_status option[value="Inactive"]').prop('selected', true)
 }



 

});
$(".make_car_modal_model").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let make_name = $('#real_model_name_'+modal_car_id).val();
 let make_image = $('#real_model_image_'+modal_car_id).attr('src');
 let make_status = $('#real_model_status_'+modal_car_id).val();

 $('#model_id').val(modal_car_id);
 $('#model_name').val(make_name);
 $('#model_img').attr('src',make_image);
 if(make_status=="Active"){
	$('#model_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#model_status option[value="Inactive"]').prop('selected', true)
 }



 

});

$(".make_car_modal_variation").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let make_name = $('#real_variation_name_'+modal_car_id).val();
 let make_image = $('#real_variation_image_'+modal_car_id).attr('src');
 let make_status = $('#real_variation_status_'+modal_car_id).val();

 $('#variation_id').val(modal_car_id);
 $('#variation_name').val(make_name);
 $('#variation_img').attr('src',make_image);
 if(make_status=="Active"){
	$('#variation_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#variation_status option[value="Inactive"]').prop('selected', true)
 }

});

$(".make_car_color").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let make_name = $('#real_color_name_'+modal_car_id).val();
 let make_image = $('#real_color_image_'+modal_car_id).attr('src');
 let make_status = $('#real_color_status_'+modal_car_id).val();
 let make_hex = $('#real_color_hex_'+modal_car_id).val();

 $('#color_id').val(modal_car_id);
 $('#color_name').val(make_name);
 $('#color_hex').val(make_hex);
 $('#color_dyn').css({"backgroundColor" : make_hex});
 if(make_status=="Active"){
	$('#color_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#color_status option[value="Inactive"]').prop('selected', true)
 }

});

$(".make_car_year").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let make_name   = $('#real_year_name_'+modal_car_id).val();
 let make_status = $('#real_year_status_'+modal_car_id).val();

 $('#year_id').val(modal_car_id);
 $('#year_name').val(make_name);

 if(make_status=="Active"){
	$('#year_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#year_status option[value="Inactive"]').prop('selected', true)
 }

});


$(".payment").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let make_name   = $('#real_payment_name_'+modal_car_id).val();
 let make_status = $('#real_payment_status_'+modal_car_id).val();

 $('#payment_id').val(modal_car_id);
 $('#payment_name').val(make_name);

 if(make_status=="Active"){
	$('#payment_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#payment_status option[value="Inactive"]').prop('selected', true)
 }

});

$(".package").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let title         = $('#real_package_title_'+modal_car_id).val();
 let description   = $('#real_package_description_'+modal_car_id).val();
 let price         = $('#real_package_price_'+modal_car_id).val();
 let make_status   = $('#real_package_status_'+modal_car_id).val();
 let make_image    = $('#real_package_image_'+modal_car_id).attr('src');

 $('#package_id').val(modal_car_id);
 $('#package_title').val(title);
 $('#package_desc').val(description);
 $('#package_price').val(price);
 $('#package_img').attr('src',make_image);
 if(make_status=="Active"){
	$('#package_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#package_status option[value="Inactive"]').prop('selected', true)
 }

});

$(".system_settings").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let system_settings_id = valArray[2];

 let type       = $('#type_'+system_settings_id).val();
 let description   = $('#description_'+system_settings_id).val();
 
 $('#type').val(type);
 $('#description').val(description);
 

});



$(".statess").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let name         = $('#real_state_name_'+modal_car_id).val();
 let count   = $('#real_state_country_'+modal_car_id).val();
 let make_status   = $('#real_state_status_'+modal_car_id).val();
 
 $('#states_id').val(modal_car_id);
 $('#state_name').val(name);
 
 $('#state_count_id option[value="'+count+'"]').prop('selected', true)
 if(make_status=="1"){
	$('#state_status option[value="1"]').prop('selected', true)
 }
 else{
	$('#state_status option[value="0"]').prop('selected', true)
 }

});

$(".citiess").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];

 let name         = $('#real_city_name_'+modal_car_id).val();
 let state   = $('#real_state_city_'+modal_car_id).val();
 let make_status   = $('#real_city_status_'+modal_car_id).val();
 
 $('#citye_id').val(modal_car_id);
 $('#city_name').val(name);
 
 $('#city_id option[value="'+state+'"]').prop('selected', true)
 if(make_status=="1"){
	$('#city_status option[value="1"]').prop('selected', true)
 }
 else{
	$('#city_status option[value="0"]').prop('selected', true)
 }

});

$(".body_name").click(function() {
 let value = this.id;
 let valArray = value.split('_');
 let modal_car_id = valArray[2];
 let name         = $('#real_body_name_'+modal_car_id).val();
 let make_status   = $('#real_body_status_'+modal_car_id).val();
 $('#body_id').val(modal_car_id);
 $('#body_name').val(name);
 if(make_status=="Active"){
	$('#body_status option[value="Active"]').prop('selected', true)
 }
 else{
	$('#body_status option[value="Inactive"]').prop('selected', true)
 }

});
function show_color(value){
	console.log(value);
	$('#colors_dyn').css({"backgroundColor" : value});
}
function show_color_dyn(value){
	console.log(value);
	$('#color_dyn').css({"backgroundColor" : value});
}
</script>	
