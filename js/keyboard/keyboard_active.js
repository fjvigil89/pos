$(document).ready(function()
{	
	$('input[type=text], input[type=email], input[type=password], textarea').keyboard({
		autoAccept: true,
		alwaysOpen: false,
		openOn: 'focus',
		usePreview: false,
		enterNavigation : true,
					 		
		display: {
			'bksp'   :  "\u2190",
			'accept' : 'return',
			'default': 'ABC',
			'meta1'  : '.?123',
			'meta2'  : '#+='
		},

		layout: 'custom',
		css: {
	        // input & preview
	        input: 'form-control',
	        // keyboard container
	        container: 'center-block dropdown-menu', // jumbotron
	        // default state
	        buttonDefault: 'btn-custom-keyboard btn-default',
	        // hovered button
	        buttonHover: 'btn-success',
	        // Action keys (e.g. Accept, Cancel, Tab, etc);
	        // this replaces "actionClass" option
	        buttonAction: 'active',
	        // used when disabling the decimal button {dec}
	        // when a decimal exists in the input area
	        buttonDisabled: 'disabled'
	    },
		customLayout: {
			'default': [
			'q w e r t y u i o p {bksp}',
			'a s d f g h j k l ñ {enter}',
			'{s} z x c v b n m , . {s}',
			'{meta1} {space} {cancel} {accept}'
			],
			'shift': [
			'Q W E R T Y U I O P {bksp}',
			'A S D F G H J K L {enter}',
			'{s} Z X C V B N M / ? {s}',
			'{meta1} {space} {meta1} {accept}'
			],
			'meta1': [
			'1 2 3 4 5 6 7 8 9 0 {bksp}',
			'- / : ; ( ) \u20ac & @ {enter}',
			'{meta2} . , ? ! \' " {meta2}',
			'{default} {space} {default} {accept}'
			],
			'meta2': [
			'[ ] { } # % ^ * + = {bksp}',
			'_ \\ | &lt; &gt; $ \u00a3 {enter}',
			'{meta1} ~ . , ? ! \' " {meta1}',
			'{default} {space} {default} {accept}'
			]
		}		
	})
	// activate the typing extension
	.addTyping({
	    showTyping: true,
	    delay: 250
	});

	$.extend($.keyboard.keyaction, {
	  	enter : function(kb) {
		    // accept the content and close the keyboard
		    kb.accept();
		    // submit the form
	  	}
	});     
});