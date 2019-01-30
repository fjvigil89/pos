<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>Virtual Keyboard</title>

	<meta charset="UTF-8">
    <!-- jQuery (required) & jQuery UI + theme (optional) -->
	<link href="<?php echo base_url();?>js/keyboard_1.25.11/docs/css/jquery-ui.min.css" rel="stylesheet">
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/docs/js/jquery.min.js"></script>
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/docs/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/docs/js/jquery-migrate-1.2.1.min.js"></script>

	<!-- keyboard widget css & script (required) -->
	<link href="<?php echo base_url();?>js/keyboard_1.25.11/css/keyboard.css" rel="stylesheet">
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/js/jquery.keyboard.js"></script>
   

	<!-- keyboard extensions (optional) -->
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/js/jquery.mousewheel.js"></script>
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/js/jquery.keyboard.extension-typing.js"></script>
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/js/jquery.keyboard.extension-autocomplete.js"></script>
	<script src="<?php echo base_url();?>js/keyboard_1.25.11/js/jquery.keyboard.extension-caret.js"></script>
</head>

<body>
    
	<input id="text" type="text" placeholder=" Enter something...">
		
    <script>
        var availableTags = [
            "ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure",
            "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript",
            "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme" 
        ];

        $('#text').keyboard({ layout: 'international' }).autocomplete({
                source: '<?php echo site_url("sales/item_search"); ?>',
				delay: 300,
				autoFocus: false,
				minLength: 1,
				select: function(event, ui)
				{
					event.preventDefault();
					$( "#item" ).val(ui.item.value);
					$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit, success: itemScannedSuccess});
				}
            }).addAutocomplete({
            position : {
                of : null,        // when null, element will default to kb.$keyboard
                my : 'right top', // 'center top', (position under keyboard)
                at : 'left top',  // 'center bottom',
                collision: 'flip'
            }
        }).addTyping();
    </script>
</body>
</html>