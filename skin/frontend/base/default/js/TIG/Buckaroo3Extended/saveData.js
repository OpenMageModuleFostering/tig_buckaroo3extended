jQuery_144('.buckaroo3extended_input').find('input,select').live('change', function() {
        sendData(jQuery_144(this));
    }
);

jQuery_144('#buckaroo3extended_directdebit_account_owner, #buckaroo3extended_directdebit_account_number').live('change', function() {
        sendData(jQuery_144(this));
    }
);