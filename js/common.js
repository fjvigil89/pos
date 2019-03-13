function get_dimensions() 
{
	var dims = {width:0,height:0};
	
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    dims.width = window.innerWidth;
    dims.height = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    dims.width = document.documentElement.clientWidth;
    dims.height = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    dims.width = document.body.clientWidth;
    dims.height = document.body.clientHeight;
  }
  
  return dims;
}
/*
function gritter(title,text,classname,image,sticky){
	$.gritter.add({
		title:	title,
		text:	text,
		image: image,
		sticky: sticky,
		class_name: classname
	});
}*/

toastr.options = {
  	"closeButton": true,
  	"debug": false,
  	"newestOnTop": false,
  	"progressBar": true,
  	"positionClass": "toast-top-right",
  	"preventDuplicates": false,
  	"onclick": null,
  	"showDuration": "300",
  	"hideDuration": "1000",
  	"timeOut": "5000",
  	"extendedTimeOut": "1000",
  	"hideEasing": "linear",
  	"showMethod": "slideDown"
}

function giftcard_swipe_field($field)
{
	$field.keyup(function()
	{
		var cur_val = $(this).val();
		
		//Remove starting %
		if (cur_val.substring(0,1) == '%')
		{
			cur_val = cur_val.substring(1);
		}
		
		//remove ending ?
		if(cur_val.substring(cur_val.length - 1) == '?')
		{
			cur_val = cur_val.substring(0,cur_val.length - 1);
		}
		
		$(this).val(cur_val);
	});
	
}

$(document).keydown(function(event) 
{
	if (event.keyCode == 113)
	{
		window.location = SITE_URL + "/sales";
	}
});

$(document).ready(function()
{
	$(document).on('click', 'a[data-toggle="modal"]', function(event)
	{
		$('#myModal').html('');
		$('#myModal').load($(this).attr('href'));
	});
	
	$('.show_more_taxes').click(function()
	{
		//disable cumulative
		$(this).parent().prev().find('.cumulative').prop('disabled', true);
		$(this).parent().prev().find('.cumulative').prop('checked', false);
		$(this).parent().next().show();
		$(this).remove();
	});

	//Prevent cumulative on load of taxes
	$(".more_taxes_container:visible").each(function(index,el)
	{
		$(this).prev().prev().find('.cumulative').prop('disabled', true);
		$(this).prev().prev().find('.cumulative').prop('checked', false);
		
	});

	//Table sales suspended
	$('#dTable').DataTable({
		"language": {
            "url": "css/datatables/Spanish.json"
        },

	    "aoColumnDefs": [
          	{ 'bSortable': false, 'aTargets': [ 5,6,7,8,9 ] }
       ]
	});

	//Table receivings suspended
	$('#dTable_receivings').DataTable({
		"language": {
            "url": "css/datatables/Spanish.json"
        },

	    "aoColumnDefs": [
          	{ 'bSortable': false, 'aTargets': [ 4,5,6,7] }
       ]
	});

	//Table sales quotes
	$('#dTable_quotes').DataTable({
		"language": {
            "url": "css/datatables/Spanish.json"
        },

	    "aoColumnDefs": [
          	{ 'bSortable': false, 'aTargets': [ 3,4,5,6 ] }
       ]
	});
});

//Autocomplete on ipad/phone	
$(document).on('touchstart', "ul.ui-autocomplete.ui-menu li a", function(e)
{
    $(this).addClass('autocomplete-touch-start');
    $(this).removeClass('autocomplete-touch-end');			
});	

$(document).on('touchend', "ul.ui-autocomplete.ui-menu li a", function(e)
{
    $(this).addClass('autocomplete-touch-end');
    $(this).removeClass('autocomplete-touch-start');
});	
