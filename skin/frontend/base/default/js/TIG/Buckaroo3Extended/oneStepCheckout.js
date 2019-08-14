oldFirstname = '';
oldLastname = '';
oldEmail = '';
oldGender = '';
oldDay = '';
oldMonth = '';
oldYear = '';
oldPhone = '';
originalAddress = jQuery_144('#billing-address-select option:selected').val();
changedAddress = false;
jQuery_144("#billing\\:firstname").change(
    function() {
        firstname = jQuery_144(this).val();

        if (
            !jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerfirstname').val()
            || jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerfirstname').val() == oldFirstname
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerfirstname').val(firstname);
            sendData(jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerfirstname'));
        }

        if (
            !jQuery_144('#buckaroo3extended_payperemail_BPE_Customerfirstname').val()
            || jQuery_144('#buckaroo3extended_payperemail_BPE_Customerfirstname').val() == oldFirstname
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_payperemail_BPE_Customerfirstname').val(firstname);
            sendData(jQuery_144('#buckaroo3extended_payperemail_BPE_Customerfirstname'));
        }

        jQuery_144('#buckaroo3extended_paymentguarantee_BPE_Customername').html(
            firstname + ' ' + jQuery_144("#billing\\:lastname").val()
        );

        jQuery_144('#buckaroo3extended_transfer_BPE_Customername').html(
            firstname + ' ' + jQuery_144("#billing\\:lastname").val()
        );

        jQuery_144('#buckaroo3extended_directdebit_account_owner').val(
            firstname + ' ' + jQuery_144("#billing\\:lastname").val()
        );

        jQuery_144('#buckaroo3extended_empayment_BPE_Accountholder').val(
            firstname + ' ' + jQuery_144("#billing\\:lastname").val()
        );

        oldFirstname = firstname;
    }
);
jQuery_144("#billing\\:lastname").change(
    function() {
        lastname = jQuery_144(this).val();

        if (
            !jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerlastname').val()
            || jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerlastname').val() == oldLastname
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerlastname').val(lastname);
            sendData(jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customerlastname'));
        }

        if (
            !jQuery_144('#buckaroo3extended_payperemail_BPE_Customerlastname').val()
            || jQuery_144('#buckaroo3extended_payperemail_BPE_Customerlastname').val() == oldLastname
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_payperemail_BPE_Customerlastname').val(lastname);
            sendData(jQuery_144('#buckaroo3extended_payperemail_BPE_Customerlastname'));
        }

        jQuery_144('#buckaroo3extended_paymentguarantee_BPE_Customername').html(
            jQuery_144("#billing\\:firstname").val() + ' ' + lastname
        );

        jQuery_144('#buckaroo3extended_transfer_BPE_Customername').html(
            jQuery_144("#billing\\:firstname").val() + ' ' + lastname
        );

        jQuery_144('#buckaroo3extended_directdebit_account_owner').val(
            jQuery_144("#billing\\:firstname").val() + ' ' + lastname
        );

        jQuery_144('#buckaroo3extended_empayment_BPE_Accountholder').val(
            jQuery_144("#billing\\:firstname").val() + ' ' + lastname
        );

        oldLastname = lastname;
    }
);
jQuery_144("#billing\\:email").change(
    function() {
        email = jQuery_144(this).val();

        if (
            !jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customermail').val()
            || jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customermail').val() == oldEmail
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customermail').val(email);
            sendData(jQuery_144('#buckaroo3extended_onlinegiro_BPE_Customermail'));
        }

        if (
            !jQuery_144('#buckaroo3extended_transfer_BPE_Customermail').val()
            || jQuery_144('#buckaroo3extended_transfer_BPE_Customermail').val() == oldEmail
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_transfer_BPE_Customermail').val(email);
            sendData(jQuery_144('#buckaroo3extended_transfer_BPE_Customermail'));
        }

        if (
            !jQuery_144('#buckaroo3extended_payperemail_BPE_Customermail').val()
            || jQuery_144('#buckaroo3extended_payperemail_BPE_Customermail').val() == oldEmail
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_payperemail_BPE_Customermail').val(email);
            sendData(jQuery_144('#buckaroo3extended_payperemail_BPE_Customermail'));
        }

        oldEmail = email;
    }
);
jQuery_144("#billing\\:telephone").change(
    function() {
        phone = jQuery_144(this).val();

        if (
            !jQuery_144('#buckaroo3extended_paymentguarantee_BPE_Customerphone').val()
            || jQuery_144('#buckaroo3extended_paymentguarantee_BPE_Customerphone').val() == oldPhone
            || changedAddress
        ) {
            jQuery_144('#buckaroo3extended_paymentguarantee_BPE_Customerphone').val(phone);
            sendData(jQuery_144('#buckaroo3extended_paymentguarantee_BPE_Customerphone'));
        }

        oldPhone = phone;
    }
);
jQuery_144("#billing\\:gender").change(
	function() {
		gender = jQuery_144("#billing\\:gender option:selected").val();

		if (
			!jQuery_144("#buckaroo3extended_paymentguarantee_BPE_Customergender option:selected").val()
			|| jQuery_144("#buckaroo3extended_paymentguarantee_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
            jQuery_144("#buckaroo3extended_paymentguarantee_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}

		if (
			!jQuery_144("#buckaroo3extended_onlinegiro_BPE_Customergender option:selected").val()
			|| jQuery_144("#buckaroo3extended_onlinegiro_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
            jQuery_144("#buckaroo3extended_onlinegiro_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}

		if (
			!jQuery_144("#buckaroo3extended_transfer_BPE_Customergender option:selected").val()
			|| jQuery_144("#buckaroo3extended_transfer_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
            jQuery_144("#buckaroo3extended_transfer_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}

		if (
			!jQuery_144("#buckaroo3extended_payperemail_BPE_Customergender option:selected").val()
			|| jQuery_144("#buckaroo3extended_payperemail_BPE_Customergender option:selected").val() == oldGender
            || changedAddress
        ) {
            jQuery_144("#buckaroo3extended_payperemail_BPE_Customergender option[value='" + gender + "']").attr('selected', 'selected');
		}

		oldGender = gender;
	}
);
jQuery_144("#billing\\:day").change(
	function() {
		day = jQuery_144(this).val();

        if (
            !jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day").val()
            || jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day").val() == oldDay
            || changedAddress
        ) {
            jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day").val(day);
            sendData(jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:day"));
        }

        if (
            !jQuery_144('#overschrijving\\:payment\\:day').val()
            || jQuery_144('#overschrijving\\:payment\\:day').val() == oldDay
            || changedAddress
        ) {
            jQuery_144('#overschrijving\\:payment\\:day').val(day);
        	sendData(jQuery_144('#overschrijving\\:payment\\:day'));
        }

		oldDay = day;
	}
);
jQuery_144("#billing\\:month").change(
	function() {
		month = jQuery_144(this).val();

        if (
            !jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month").val()
            || jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month").val() == oldMonth
            || changedAddress
        ) {
            jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month").val(month);
            sendData(jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:month"));
        }

        if (
            !jQuery_144('#overschrijving\\:payment\\:month').val()
            || jQuery_144('#overschrijving\\:payment\\:month').val() == oldMonth
            || changedAddress
        ) {
            jQuery_144('#overschrijving\\:payment\\:month').val(month);
        	sendData(jQuery_144('#overschrijving\\:payment\\:month'));
        }

		oldMonth = month;
	}
);
jQuery_144("#billing\\:year").change(
	function() {
		year = jQuery_144(this).val();

        if (
            !jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year").val()
            || jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year").val() == oldYear
            || changedAddress
        ) {
            jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year").val(year);
            sendData(jQuery_144("#container_payment_method_buckaroo3extended_paymentguarantee #payment\\:year"));
        }

        if (
            !jQuery_144('#overschrijving\\:payment\\:year').val()
            || jQuery_144('#overschrijving\\:payment\\:year').val() == oldYear
            || changedAddress
        ) {
            jQuery_144('#overschrijving\\:payment\\:year').val(year);
        	sendData(jQuery_144('#overschrijving\\:payment\\:year'));
        }

		oldYear = year;
	}
);

jQuery_144('#billing-address-select').change(
    function() {
        if (!jQuery_144('#billing-address-select option:selected').val()) {
            changedAddress = true;
        } else if (jQuery_144('#billing-address-select option:selected').val() == originalAddress) {
            changedAddress = false;
        }
    }
);