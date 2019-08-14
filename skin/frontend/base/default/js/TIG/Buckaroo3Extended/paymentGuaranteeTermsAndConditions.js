document.observe('click', function(event, element) {
	if (element = event.findElement('#paymentguarantee_terms_and_conditions_link')) {
	  	$('paymentguarantee_terms_and_conditions').setStyle({
			display: 'block'
		});
		event.stop();
	}
	
	if (element = event.findElement('#paymentguarantee_terms_and_conditions_close')) {
	  	$('paymentguarantee_terms_and_conditions').setStyle({
			display: 'none'
		});
		event.stop();
	}
});

