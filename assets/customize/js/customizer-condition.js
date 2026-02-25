(function ($) {

    window.listzen_compare = function (oldValue, operator, newValue) {

        if (typeof oldValue === 'boolean') {
            oldValue = (oldValue === true) ? 1 : 0;
        }
        if (typeof newValue === 'boolean') {
            newValue = (newValue === true) ? 1 : 0;
        }

        switch (operator) {
            case "==":
                return oldValue == newValue;
            case "===":
                return oldValue === newValue;
            case "!=":
                return oldValue != newValue;
            case "!==":
                return oldValue !== newValue;
            default:
                return true;
        }
    }

    wp.customize.bind('ready', function () {

        var controllsItem = Object.keys(listzenCustomizeObj);

        if (controllsItem.length) {

            $.each(listzenCustomizeObj, function (conditionalItem, args) {

                var baseItem = args[0];
                var operator = args[1] ?? '==';
                var checkVal = args[2] ?? '1';

                wp.customize(baseItem, function (setting) {
                    setting.bind(function (newValue) {
                        if (listzen_compare(newValue, operator, checkVal)) {
                            $('#customize-control-' + conditionalItem).slideDown(200);
                        } else {
                            $('#customize-control-' + conditionalItem).slideUp(200);
                        }
                    });
                });

                wp.customize.control.each(function (control) {
                    if (baseItem == control.id) {
                        if (typeof control.setting !== 'undefined') {
                            if (!listzen_compare(control.setting.get(), operator, checkVal)) {
                                $('#customize-control-' + conditionalItem).hide();
                            }
                        }
                    }
                });

            });

        }


    })

})(jQuery);
