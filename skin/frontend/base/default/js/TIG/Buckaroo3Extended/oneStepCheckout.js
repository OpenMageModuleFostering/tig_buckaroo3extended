oldFirstname = '';
oldLastname = '';
oldEmail = '';
oldGender = '';
oldDay = '';
oldMonth = '';
oldYear = '';
oldPhone = '';
originalAddress = jQuery('#billing-address-select option:selected').val();
changedAddress = false;
jQuery("#billing\\:firstname").change(
    function() {
        firstname = jQuery(this).val();
        
        if (
            !jQuery('#buckaroo3extended_onlinegiro_BPE_Customerfirstname').val() 
            || jQuery('#buckaroo3extended_onlinegiro_BPE_Customerfirstname').val() == oldFirstname
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_onlinegiro_BPE_Customerfirstname').val(firstname);
            sendData(jQuery('#buckaroo3extended_onlinegiro_BPE_Customerfirstname'));
        }
        
        if (
            !jQuery('#buckaroo3extended_payperemail_BPE_Customerfirstname').val() 
            || jQuery('#buckaroo3extended_payperemail_BPE_Customerfirstname').val() == oldFirstname
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_payperemail_BPE_Customerfirstname').val(firstname);
            sendData(jQuery('#buckaroo3extended_payperemail_BPE_Customerfirstname'));
        }
        
        jQuery('#buckaroo3extended_paymentguarantee_BPE_Customername').html(
            firstname + ' ' + jQuery("#billing\\:lastname").val()
        );
        
        jQuery('#buckaroo3extended_transfer_BPE_Customername').html(
            firstname + ' ' + jQuery("#billing\\:lastname").val()
        );
        
        jQuery('#buckaroo3extended_directdebit_account_owner').val(
            firstname + ' ' + jQuery("#billing\\:lastname").val()
        );
        
        jQuery('#buckaroo3extended_empayment_BPE_Accountholder').val(
            firstname + ' ' + jQuery("#billing\\:lastname").val()
        );
        
        oldFirstname = firstname;
    }
);
jQuery("#billing\\:lastname").change(
    function() {
        lastname = jQuery(this).val();
        
        if (
            !jQuery('#buckaroo3extended_onlinegiro_BPE_Customerlastname').val() 
            || jQuery('#buckaroo3extended_onlinegiro_BPE_Customerlastname').val() == oldLastname
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_onlinegiro_BPE_Customerlastname').val(lastname);
            sendData(jQuery('#buckaroo3extended_onlinegiro_BPE_Customerlastname'));
        }
        
        if (
            !jQuery('#buckaroo3extended_payperemail_BPE_Customerlastname').val() 
            || jQuery('#buckaroo3extended_payperemail_BPE_Customerlastname').val() == oldLastname
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_payperemail_BPE_Customerlastname').val(lastname);
            sendData(jQuery('#buckaroo3extended_payperemail_BPE_Customerlastname'));
        }
        
        jQuery('#buckaroo3extended_paymentguarantee_BPE_Customername').html(
            jQuery("#billing\\:firstname").val() + ' ' + lastname
        );
        
        jQuery('#buckaroo3extended_transfer_BPE_Customername').html(
            jQuery("#billing\\:firstname").val() + ' ' + lastname
        );
        
        jQuery('#buckaroo3extended_directdebit_account_owner').val(
            jQuery("#billing\\:firstname").val() + ' ' + lastname
        );
        
        jQuery('#buckaroo3extended_empayment_BPE_Accountholder').val(
            jQuery("#billing\\:firstname").val() + ' ' + lastname
        );
        
        oldLastname = lastname;
    }
);
jQuery("#billing\\:email").change(
    function() {
        email = jQuery(this).val();
        
        if (
            !jQuery('#buckaroo3extended_onlinegiro_BPE_Customermail').val() 
            || jQuery('#buckaroo3extended_onlinegiro_BPE_Customermail').val() == oldEmail
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_onlinegiro_BPE_Customermail').val(email);
            sendData(jQuery('#buckaroo3extended_onlinegiro_BPE_Customermail'));
        }
        
        if (
            !jQuery('#buckaroo3extended_transfer_BPE_Customermail').val() 
            || jQuery('#buckaroo3extended_transfer_BPE_Customermail').val() == oldEmail
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_transfer_BPE_Customermail').val(email);
            sendData(jQuery('#buckaroo3extended_transfer_BPE_Customermail'));
        }
        
        if (
            !jQuery('#buckaroo3extended_payperemail_BPE_Customermail').val() 
            || jQuery('#buckaroo3extended_payperemail_BPE_Customermail').val() == oldEmail
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_payperemail_BPE_Customermail').val(email);
            sendData(jQuery('#buckaroo3extended_payperemail_BPE_Customermail'));
        }
        
        oldEmail = email;
    }
);
jQuery("#billing\\:telephone").change(
    function() {
        phone = jQuery(this).val();
        
        if (
            !jQuery('#buckaroo3extended_paymentguarantee_BPE_Customerphone').val() 
            || jQuery('#buckaroo3extended_paymentguarantee_BPE_Customerphone').val() == oldPhone
            || changedAddress
        ) {
            jQuery('#buckaroo3extended_paymentguarantee_BPE_Customerphone').val(phone);
            sendData(jQuery('#buckaroo3extended_paymentguarantee_BPE_Customerphone'));
        }
        
        oldPhone = phone;
    }
);
jQuery("#billing\\:gender").change(
	function() {
		gender = jQuery("#billing\\:gender option:selected").val();
		
		if (
			!jQuery("#buckaroo3extended_paymentguarantee_BPE_Customergender option:selected").val()
			|| jQuery("#buckaroo3extended_paymentguarantee_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
			jQuery("#buckaroo3extended_paymentguarantee_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}
		
		if (
			!jQuery("#buckaroo3extended_onlinegiro_BPE_Customergender option:selected").val()
			|| jQuery("#buckaroo3extended_onlinegiro_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
			jQuery("#buckaroo3extended_onlinegiro_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}
		
		if (
			!jQuery("#buckaroo3extended_transfer_BPE_Customergender option:selected").val()
			|| jQuery("#buckaroo3extended_transfer_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
			jQuery("#buckaroo3extended_transfer_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}
		
		if (
			!jQuery("#buckaroo3extended_payperemail_BPE_Customergender option:selected").val()
			|| jQuery("#buckaroo3extended_payperemail_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
			jQuery("#buckaroo3extended_payperemail_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}
		
		oldGender = gender;
	}
);
jQuery("#billing\\:day").change(
	function() {
		day = jQuery(this).val();
        
        if (
            !jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day").val() 
            || jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day").val() == oldDay
            || changedAddress
        ) {
            jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day").val(day);
            sendData(jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day"));
        }
        
        if (
            !jQuery('#overschrijving\\:payment\\:day').val() 
            || jQuery('#overschrijving\\:payment\\:day').val() == oldDay
            || changedAddress
        ) {
        	jQuery('#overschrijving\\:payment\\:day').val(day);
        	sendData(jQuery('#overschrijving\\:payment\\:day'));
        }
		
		oldDay = day;
	}
);
jQuery("#billing\\:month").change(
	function() {
		month = jQuery(this).val();
        
        if (
            !jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month").val() 
            || jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month").val() == oldMonth
            || changedAddress
        ) {
            jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month").val(month);
            sendData(jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month"));
        }
        
        if (
            !jQuery('#overschrijving\\:payment\\:month').val() 
            || jQuery('#overschrijving\\:payment\\:month').val() == oldMonth
            || changedAddress
        ) {
        	jQuery('#overschrijving\\:payment\\:month').val(month);
        	sendData(jQuery('#overschrijving\\:payment\\:month'));
        }
		
		oldMonth = month;
	}
);
jQuery("#billing\\:year").change(
	function() {
		year = jQuery(this).val();
        
        if (
            !jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year").val() 
            || jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year").val() == oldYear
            || changedAddress
        ) {
            jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year").val(year);
            sendData(jQuery("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year"));
        }
        
        if (
            !jQuery('#overschrijving\\:payment\\:year').val() 
            || jQuery('#overschrijving\\:payment\\:year').val() == oldYear
            || changedAddress
        ) {
        	jQuery('#overschrijving\\:payment\\:year').val(year);
        	sendData(jQuery('#overschrijving\\:payment\\:year'));
        }
		
		oldYear = year;
	}
);

jQuery('#billing-address-select').change(
    function() {
        if (!jQuery('#billing-address-select option:selected').val()) {
            changedAddress = true;
        } else if (jQuery('#billing-address-select option:selected').val() == originalAddress) {
            changedAddress = false;
        }
    }
);