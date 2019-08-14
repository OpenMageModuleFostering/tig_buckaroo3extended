jQuery_1123('.buckaroo3extended_input').find('input,select').on('change', function() {
        sendData(jQuery_1123(this));
    }
);

jQuery_1123('#buckaroo3extended_directdebit_account_owner, #buckaroo3extended_directdebit_account_number').on('change', function() {
        sendData(jQuery_1123(this));
    }
);