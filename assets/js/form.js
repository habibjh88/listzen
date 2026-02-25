/**
 * This file is required for food-menu "Froup Title Tiled" (radio) button
 */
jQuery(document).ready(function($) {

    jQuery(document).on('change', 'input.ant-radio-input[type="radio"]', function () {
        var radioItem = jQuery(this);
        if (radioItem.is(':checked') && 'yes' === radioItem.val()) {
            // Add a class to the parent element (adjust selector as needed)
            jQuery(this).closest('.rtcl-fb-repeater-item').addClass('rtcl-has-group-title');
        } else {
            jQuery(this).closest('.rtcl-fb-repeater-item').removeClass('rtcl-has-group-title');

        }
    });

});


function processRepeaterItem(item) {
    if (!item.classList.contains('rtcl-has-group-title')) {
        item.classList.add('active');

        const isCheck = item.querySelector('input.ant-radio-input[type="radio"]:checked');
        if (isCheck && isCheck.value === 'yes') {
            item.classList.add('rtcl-has-group-title');
        }
    }
}

// 1. 🔁 Process existing items on page load
document.querySelectorAll('.rtcl-fb-repeater-item').forEach(processRepeaterItem);

// 2. 👀 Handle dynamically added items via MutationObserver
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
            if (node.nodeType === 1) {
                const items = node.matches('.rtcl-fb-repeater-item')
                    ? [node]
                    : node.querySelectorAll('.rtcl-fb-repeater-item');

                items.forEach(processRepeaterItem);
            }
        });
    });
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});