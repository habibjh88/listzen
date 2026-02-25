jQuery(document).ready(function ($) {
    "use strict";


    $('.toggle-switch-label').on('click', function () {
        $(this).prev().trigger('change');
    })

    /**
     * Sortable Repeater Custom Control
     *
     * @since Roofix 1.0
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */

    // Update the values for all our input fields and initialise the sortable repeater
    $('.sortable_repeater_control').each(function () {
        var useAs = '';
        if ($(this).hasClass('as_sort')) {
            useAs = 'sort';
        }
        // If there is an existing customizer value, populate our rows
        var defaultValuesArray = $(this).find('.customize-control-sortable-repeater').val().split(',');
        var numRepeaterItems = defaultValuesArray.length;

        if (numRepeaterItems > 0) {
            // Add the first item to our existing input field
            $(this).find('.repeater-input').val(defaultValuesArray[0]);
            // Create a new row for each new value
            if (numRepeaterItems > 1) {
                var i;
                for (i = 1; i < numRepeaterItems; ++i) {
                    listzenAppendRow($(this), defaultValuesArray[i], useAs);
                }
            }
        }
    });

    // Make our Repeater fields sortable
    $(this).find('.sortable').sortable({
        axis: "y",
        update: function (event, ui) {
            listzenGetAllInputs($(this).parent());
        }
    });

    // Remove item starting from it's parent element
    $('.sortable').on('click', '.customize-control-sortable-repeater-delete', function (event) {
        event.preventDefault();
        var numItems = $(this).parent().parent().find('.repeater').length;

        if (numItems > 1) {
            $(this).parent().slideUp('fast', function () {
                var parentContainer = $(this).parent().parent();
                $(this).remove();
                listzenGetAllInputs(parentContainer);
            })
        } else {
            $(this).parent().find('.repeater-input').val('');
            listzenGetAllInputs($(this).parent().parent().parent());
        }
    });

    // Add new item
    $('.customize-control-sortable-repeater-add').click(function (event) {
        event.preventDefault();
        listzenAppendRow($(this).parent());
        listzenGetAllInputs($(this).parent());
    });

    // Refresh our hidden field if any fields change
    $('.sortable').change(function () {
        listzenGetAllInputs($(this).parent());
    })

    // Add https:// to the start of the URL if it doesn't have it
    /*
    $('.sortable').on('blur', '.repeater-input', function() {
        var url = $(this);
        var val = url.val();
        if(val && !val.match(/^.+:\/\/.*!/)) {
            // Important! Make sure to trigger change event so Customizer knows it has to save the field
            url.val('https://' + val).trigger('change');
        }
    });
    */

    // Append a new row to our list of elements
    function listzenAppendRow($element, defaultValue = '', useAs = '') {
        var newRow = '<div class="repeater" style="display:none"><input type="text" value="' + defaultValue + '" class="repeater-input" placeholder="" /><span class="dashicons dashicons-sort"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a></div>';
        if (useAs == 'sort') {
            var newRow = '<div class="repeater" style="display:none"><input type="text" value="' + defaultValue + '" class="repeater-input" placeholder="" /><span class="dashicons dashicons-sort"></span></div>';
        }

        $element.find('.sortable').append(newRow);
        $element.find('.sortable').find('.repeater:last').slideDown(100, function () {
            $(this).find('input').focus();
        });
    }

    // Get the values from the repeater input fields and add to our hidden field
    function listzenGetAllInputs($element) {
        var inputValues = $element.find('.repeater-input').map(function () {
            return $(this).val();
        }).toArray();
        // Add all the values from our repeater fields to the hidden field (which is the one that actually gets saved)
        $element.find('.customize-control-sortable-repeater').val(inputValues);
        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.customize-control-sortable-repeater').trigger('change');
    }

    /**
     * Slider Custom Control
     *
     * @since Roofix 1.0
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */

    // Set our slider defaults and initialise the slider
    $('.slider-custom-control').each(function () {
        var sliderValue = $(this).find('.customize-control-slider-value').val();
        var newSlider = $(this).find('.slider');
        var sliderMinValue = parseFloat(newSlider.attr('slider-min-value'));
        var sliderMaxValue = parseFloat(newSlider.attr('slider-max-value'));
        var sliderStepValue = parseFloat(newSlider.attr('slider-step-value'));

        newSlider.slider({
            value: sliderValue,
            min: sliderMinValue,
            max: sliderMaxValue,
            step: sliderStepValue,
            change: function (e, ui) {
                // Important! When slider stops moving make sure to trigger change event so Customizer knows it has to save the field
                $(this).parent().find('.customize-control-slider-value').trigger('change');
            }
        });
    });

    // Change the value of the input field as the slider is moved
    $('.slider').on('slide', function (event, ui) {
        $(this).parent().find('.customize-control-slider-value').val(ui.value);
    });

    // Reset slider and input field back to the default value
    $('.slider-reset').on('click', function () {
        var resetValue = $(this).attr('slider-reset-value');
        $(this).parent().find('.customize-control-slider-value').val(resetValue);
        $(this).parent().find('.slider').slider('value', resetValue);
    });

    // Update slider if the input field loses focus as it's most likely changed
    $('.customize-control-slider-value').blur(function () {
        var resetValue = $(this).val();
        var slider = $(this).parent().find('.slider');
        var sliderMinValue = parseInt(slider.attr('slider-min-value'));
        var sliderMaxValue = parseInt(slider.attr('slider-max-value'));

        // Make sure our manual input value doesn't exceed the minimum & maxmium values
        if (resetValue < sliderMinValue) {
            resetValue = sliderMinValue;
            $(this).val(resetValue);
        }
        if (resetValue > sliderMaxValue) {
            resetValue = sliderMaxValue;
            $(this).val(resetValue);
        }
        $(this).parent().find('.slider').slider('value', resetValue);
    });

    /**
     * Single Accordion Custom Control
     *
     * @since Roofix 1.0
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */

    $('.single-accordion-toggle').click(function () {
        var $accordionToggle = $(this);
        $(this).parent().find('.single-accordion').slideToggle('slow', function () {
            $accordionToggle.toggleClass('single-accordion-toggle-rotate', $(this).is(':visible'));
        });
    });

    /**
     * Image Check Box Custom Control
     *
     * @since Roofix 1.0
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */

    $('.multi-image-checkbox').on('change', function () {
        getAllCheckboxes($(this).parent().parent());
    });

    // Get the values from the checkboxes and add to our hidden field
    function getAllCheckboxes($element) {
        var inputValues = $element.find('.multi-image-checkbox').map(function () {
            if ($(this).is(':checked')) {
                return $(this).val();
                //   } else {
                //     return '';
            }
        }).toArray();
        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.customize-control-multi-image-checkbox').val(inputValues).trigger('change');
    }

    /**
     * Dropdown Select2 Custom Control
     *
     * @since Roofix 1.4
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */


    $('.customize-control-dropdown-pages select').select2({
        allowClear: true,
    });


    $('.customize-control-dropdown-select2').each(function () {
        $('.customize-control-select2').select2({
            allowClear: true,
            templateSelection: function (state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span data-id="' + state.id + '" class="listzen-select-item">' + state.text + '</span>'
                );
                return $state;
            }
        });
    });


    // TODO==========================
    $("ul.select2-selection__rendered").sortable({
        containment: 'parent',
        stop: function () {
            // Update select2 data after sorting
            var selectedValues = [];
            $(this).find("li").each(function () {
                // var value = $(this).attr("title");
                var value = $(this).find('.listzen-select-item').attr("data-id");
                selectedValues.push(value);
            });

            // Clear current selections and select the sorted values
            $(this).closest('.dropdown_select2_control').find('.customize-control-dropdown-select2').val(null).trigger("change");
            $(this).closest('.dropdown_select2_control').find('.customize-control-dropdown-select2').val(selectedValues).trigger("change");
        }
    });

    // TODO==========================

    $(".customize-control-select2").on("change", function () {
        var select2Val = $(this).val();
        $(this).parent().find('.customize-control-dropdown-select2').val(select2Val).trigger('change');
    });


    /**
     * Googe Font Select Custom Control
     *
     * @since Roofix 1.0
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */

    $('.google-fonts-list').each(function (i, obj) {
        if (!$(obj).hasClass('select2-hidden-accessible')) {
            $(obj).select2();
        }
    });

    $('.google-fonts-list').on('change', function () {
        var elementRegularWeight = $(this).parent().parent().find('.google-fonts-regularweight-style');
        var elementItalicWeight = $(this).parent().parent().find('.google-fonts-italicweight-style');
        var elementBoldWeight = $(this).parent().parent().find('.google-fonts-boldweight-style');
        var selectedFont = $(this).val();
        var customizerControlName = $(this).attr('control-name');
        var elementItalicWeightCount = 0;
        var elementBoldWeightCount = 0;

        // Clear Weight/Style dropdowns
        elementRegularWeight.empty();
        elementItalicWeight.empty();
        elementBoldWeight.empty();
        // Make sure Italic & Bold dropdowns are enabled
        elementItalicWeight.prop('disabled', false);
        elementBoldWeight.prop('disabled', false);

        // Get the Google Fonts control object
        var bodyfontcontrol = _wpCustomizeSettings.controls[customizerControlName];

        // Find the index of the selected font
        var indexes = $.map(bodyfontcontrol.listzenfontslist, function (obj, index) {
            if (obj.family === selectedFont) {
                return index;
            }
        });
        var index = indexes[0];

        // For the selected Google font show the available weight/style variants
        $.each(bodyfontcontrol.listzenfontslist[index].variants, function (val, text) {
            elementRegularWeight.append(
                $('<option></option>').val(text).html(text)
            );
            if (text.indexOf("italic") >= 0) {
                elementItalicWeight.append(
                    $('<option></option>').val(text).html(text)
                );
                elementItalicWeightCount++;
            } else {
                elementBoldWeight.append(
                    $('<option></option>').val(text).html(text)
                );
                elementBoldWeightCount++;
            }
        });

        if (elementItalicWeightCount == 0) {
            elementItalicWeight.append(
                $('<option></option>').val('').html('Not Available for this font')
            );
            elementItalicWeight.prop('disabled', 'disabled');
        }
        if (elementBoldWeightCount == 0) {
            elementBoldWeight.append(
                $('<option></option>').val('').html('Not Available for this font')
            );
            elementBoldWeight.prop('disabled', 'disabled');
        }

        // Update the font category based on the selected font
        $(this).parent().parent().find('.google-fonts-category').val(bodyfontcontrol.listzenfontslist[index].category);

        listzenGetAllSelects($(this).parent().parent());
    });

    $('.google_fonts_select_control select').on('change', function () {
        listzenGetAllSelects($(this).parent().parent());
    });

    function listzenGetAllSelects($element) {
        var selectedFont = {
            font: $element.find('.google-fonts-list').val(),
            regularweight: $element.find('.google-fonts-regularweight-style').val(),
            italicweight: $element.find('.google-fonts-italicweight-style').val(),
            boldweight: $element.find('.google-fonts-boldweight-style').val(),
            category: $element.find('.google-fonts-category').val()
        };

        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.customize-control-google-font-selection').val(JSON.stringify(selectedFont)).trigger('change');
    }

    $('.listzen-background-attributes select').on('change', function () {
        console.log('changes')
        listzenBGAttributeTrigger($(this).closest('.listzen-background-attributes'));
    });

    function listzenBGAttributeTrigger($element) {
        var selectedVal = {
            position: $element.find('.listzen-bg-position').val(),
            attachment: $element.find('.listzen-bg-attachment').val(),
            repeat: $element.find('.listzen-bg-repeat').val(),
            size: $element.find('.listzen-bg-size').val(),
        };

        console.log(selectedVal)

        // Important! Make sure to trigger change event so Customizer knows it has to save the field
        $element.find('.customize-control-background-atts').val(JSON.stringify(selectedVal)).trigger('change');
    }

    /**
     * TinyMCE Custom Control
     *
     * @since Roofix 1.0
     *
     * @author Anthony Hortin <http://maddisondesigns.com>
     * @license http://www.gnu.org/licenses/gpl-2.0.html
     * @link https://github.com/maddisondesigns
     */

    $('.customize-control-tinymce-editor').each(function () {
        // Get the toolbar strings that were passed from the PHP Class
        var tinyMCEToolbar1String = _wpCustomizeSettings.controls[$(this).attr('id')].listzenTinymcetoolbar1;
        var tinyMCEToolbar2String = _wpCustomizeSettings.controls[$(this).attr('id')].listzenTinymcetoolbar2;

        wp.editor.initialize($(this).attr('id'), {
            tinymce: {
                wpautop: true,
                toolbar1: tinyMCEToolbar1String,
                toolbar2: tinyMCEToolbar2String
            },
            quicktags: true
        });
    });
    $(document).on('tinymce-editor-init', function (event, editor) {
        editor.on('change', function (e) {
            tinyMCE.triggerSave();
            $('#' + editor.id).trigger('change');
        });
    });

    /**
     * Alpha Color Picker Custom Control
     *
     * @author Braad Martin <http://braadmartin.com>
     * @license http://www.gnu.org/licenses/gpl-3.0.html
     * @link https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker
     */

    // Loop over each control and transform it into our color picker.
    $('.alpha-color-control').each(function () {

        // Scope the vars.
        var $control, startingColor, paletteInput, showOpacity, defaultColor, palette,
            colorPickerOptions, $container, $alphaSlider, alphaVal, sliderOptions;

        // Store the control instance.
        $control = $(this);

        // Get a clean starting value for the option.
        startingColor = $control.val().replace(/\s+/g, '');

        // Get some data off the control.
        paletteInput = $control.attr('data-palette');
        showOpacity = $control.attr('data-show-opacity');
        defaultColor = $control.attr('data-default-color');

        // Process the palette.
        if (paletteInput.indexOf('|') !== -1) {
            palette = paletteInput.split('|');
        } else if ('false' == paletteInput) {
            palette = false;
        } else {
            palette = true;
        }

        // Set up the options that we'll pass to wpColorPicker().
        colorPickerOptions = {
            change: function (event, ui) {
                var key, value, alpha, $transparency;

                key = $control.attr('data-customize-setting-link');
                value = $control.wpColorPicker('color');

                // Set the opacity value on the slider handle when the default color button is clicked.
                if (defaultColor == value) {
                    alpha = acp_get_alpha_value_from_color(value);
                    $alphaSlider.find('.ui-slider-handle').text(alpha);
                }

                // Send ajax request to wp.customize to trigger the Save action.
                wp.customize(key, function (obj) {
                    obj.set(value);
                });

                $transparency = $container.find('.transparency');

                // Always show the background color of the opacity slider at 100% opacity.
                $transparency.css('background-color', ui.color.toString('no-alpha'));
            },
            palettes: palette // Use the passed in palette.
        };

        // Create the colorpicker.
        $control.wpColorPicker(colorPickerOptions);

        $container = $control.parents('.wp-picker-container:first');

        // Insert our opacity slider.
        $('<div class="alpha-color-picker-container">' +
            '<div class="min-click-zone click-zone"></div>' +
            '<div class="max-click-zone click-zone"></div>' +
            '<div class="alpha-slider"></div>' +
            '<div class="transparency"></div>' +
            '</div>').appendTo($container.find('.wp-picker-holder'));

        $alphaSlider = $container.find('.alpha-slider');

        // If starting value is in format RGBa, grab the alpha channel.
        alphaVal = acp_get_alpha_value_from_color(startingColor);

        // Set up jQuery UI slider() options.
        sliderOptions = {
            create: function (event, ui) {
                var value = $(this).slider('value');

                // Set up initial values.
                $(this).find('.ui-slider-handle').text(value);
                $(this).siblings('.transparency ').css('background-color', startingColor);
            },
            value: alphaVal,
            range: 'max',
            step: 1,
            min: 0,
            max: 100,
            animate: 300
        };

        // Initialize jQuery UI slider with our options.
        $alphaSlider.slider(sliderOptions);

        // Maybe show the opacity on the handle.
        if ('true' == showOpacity) {
            $alphaSlider.find('.ui-slider-handle').addClass('show-opacity');
        }

        // Bind event handlers for the click zones.
        $container.find('.min-click-zone').on('click', function () {
            acp_update_alpha_value_on_color_control(0, $control, $alphaSlider, true);
        });
        $container.find('.max-click-zone').on('click', function () {
            acp_update_alpha_value_on_color_control(100, $control, $alphaSlider, true);
        });

        // Bind event handler for clicking on a palette color.
        $container.find('.iris-palette').on('click', function () {
            var color, alpha;

            color = $(this).css('background-color');
            alpha = acp_get_alpha_value_from_color(color);

            acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);

            // Sometimes Iris doesn't set a perfect background-color on the palette,
            // for example rgba(20, 80, 100, 0.3) becomes rgba(20, 80, 100, 0.298039).
            // To compensante for this we round the opacity value on RGBa colors here
            // and save it a second time to the color picker object.
            if (alpha != 100) {
                color = color.replace(/[^,]+(?=\))/, (alpha / 100).toFixed(2));
            }

            $control.wpColorPicker('color', color);
        });

        // Bind event handler for clicking on the 'Clear' button.
        $container.find('.button.wp-picker-clear').on('click', function () {
            var key = $control.attr('data-customize-setting-link');

            // The #fff color is delibrate here. This sets the color picker to white instead of the
            // defult black, which puts the color picker in a better place to visually represent empty.
            $control.wpColorPicker('color', '#ffffff');

            // Set the actual option value to empty string.
            wp.customize(key, function (obj) {
                obj.set('');
            });

            acp_update_alpha_value_on_alpha_slider(100, $alphaSlider);
        });

        // Bind event handler for clicking on the 'Default' button.
        $container.find('.button.wp-picker-default').on('click', function () {
            var alpha = acp_get_alpha_value_from_color(defaultColor);

            acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);
        });

        // Bind event handler for typing or pasting into the input.
        $control.on('input', function () {
            var value = $(this).val();
            var alpha = acp_get_alpha_value_from_color(value);

            acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);
        });

        // Update all the things when the slider is interacted with.
        $alphaSlider.slider().on('slide', function (event, ui) {
            var alpha = parseFloat(ui.value) / 100.0;

            acp_update_alpha_value_on_color_control(alpha, $control, $alphaSlider, false);

            // Change value shown on slider handle.
            $(this).find('.ui-slider-handle').text(ui.value);
        });

    });

    /**
     * Override the stock color.js toString() method to add support for outputting RGBa or Hex.
     */
    Color.prototype.toString = function (flag) {

        // If our no-alpha flag has been passed in, output RGBa value with 100% opacity.
        // This is used to set the background color on the opacity slider during color changes.
        if ('no-alpha' == flag) {
            return this.toCSS('rgba', '1').replace(/\s+/g, '');
        }

        // If we have a proper opacity value, output RGBa.
        if (1 > this._alpha) {
            return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
        }

        // Proceed with stock color.js hex output.
        var hex = parseInt(this._color, 10).toString(16);
        if (this.error) {
            return '';
        }
        if (hex.length < 6) {
            for (var i = 6 - hex.length - 1; i >= 0; i--) {
                hex = '0' + hex;
            }
        }

        return '#' + hex;
    };

    /**
     * Given an RGBa, RGB, or hex color value, return the alpha channel value.
     */
    function acp_get_alpha_value_from_color(value) {
        var alphaVal;

        // Remove all spaces from the passed in value to help our RGBa regex.
        value = value.replace(/ /g, '');

        if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
            alphaVal = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]).toFixed(2) * 100;
            alphaVal = parseInt(alphaVal);
        } else {
            alphaVal = 100;
        }

        return alphaVal;
    }

    /**
     * Force update the alpha value of the color picker object and maybe the alpha slider.
     */
    function acp_update_alpha_value_on_color_control(alpha, $control, $alphaSlider, update_slider) {
        var iris, colorPicker, color;

        iris = $control.data('a8cIris');
        colorPicker = $control.data('wpWpColorPicker');

        // Set the alpha value on the Iris object.
        iris._color._alpha = alpha;

        // Store the new color value.
        color = iris._color.toString();

        // Set the value of the input.
        $control.val(color);

        // Update the background color of the color picker.
        colorPicker.toggler.css({
            'background-color': color
        });

        // Maybe update the alpha slider itself.
        if (update_slider) {
            acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);
        }

        // Update the color value of the color picker object.
        $control.wpColorPicker('color', color);
    }

    /**
     * Update the slider handle position and label.
     */
    function acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider) {
        $alphaSlider.slider('value', alpha);
        $alphaSlider.find('.ui-slider-handle').text(alpha.toString());
    }


    // Gallery Control

    $(window).load(function () {

        /*var begin_attachment_string = $("#images-input").val();
        var begin_attachment_array = begin_attachment_string.split(",");
        for(var i = 0; i < begin_attachment_array.length; i++){
            if(begin_attachment_array[i] != ""){
                $(".images").append( "<li class='image-list'><img src='"+begin_attachment_array[i]+"'></li>" );
            }
        }*/

        $(".button-secondary.upload").click(function () {
            var imageInput = $(this).parent().next();
            var custom_uploader = wp.media.frames.file_frame = wp.media({
                multiple: true
            });

            custom_uploader.on('select', function () {
                var selection = custom_uploader.state().get('selection');
                var attachments = [];
                selection.map(function (attachment) {
                    attachment = attachment.toJSON();
                    $(".images").append("<li class='image-list'><img src='" + attachment.url + "'></li>");
                    attachments.push(attachment.id);
                    // console.log(attachment.id)
                });
                var attachment_string = attachments.join() + "," + imageInput.val();
                imageInput.val(attachment_string).trigger('change');
            });
            custom_uploader.open();
        });

        $(".images").click(function (event) {
            var img_src = $(event.target).find("img").attr('src');
            $(event.target).closest("li").remove();
            var attachment_string = $('#images-input').val();
            attachment_string = attachment_string.replace(img_src + ",", "");
            $('#images-input').val(attachment_string).trigger('change');
        });
    });


// 	TODO: panel in to panel
    var api = wp.customize;

    api.bind('pane-contents-reflowed', function () {

        // Reflow sections
        var sections = [];

        api.section.each(function (section) {

            if (
                'pe_section' !== section.params.type ||
                'undefined' === typeof section.params.section
            ) {

                return;

            }

            sections.push(section);

        });

        sections.sort(api.utils.prioritySort).reverse();

        $.each(sections, function (i, section) {

            var parentContainer = $('#sub-accordion-section-' + section.params.section);

            parentContainer.children('.section-meta').after(section.headContainer);

        });

        // Reflow panels
        var panels = [];

        api.panel.each(function (panel) {

            if (
                'pe_panel' !== panel.params.type ||
                'undefined' === typeof panel.params.panel
            ) {

                return;

            }

            panels.push(panel);

        });

        panels.sort(api.utils.prioritySort).reverse();

        $.each(panels, function (i, panel) {

            var parentContainer = $('#sub-accordion-panel-' + panel.params.panel);

            parentContainer.children('.panel-meta').after(panel.headContainer);

        });

    });


    // Extend Panel
    var _panelEmbed = wp.customize.Panel.prototype.embed;
    var _panelIsContextuallyActive = wp.customize.Panel.prototype.isContextuallyActive;
    var _panelAttachEvents = wp.customize.Panel.prototype.attachEvents;

    wp.customize.Panel = wp.customize.Panel.extend({
        attachEvents: function () {

            if (
                'pe_panel' !== this.params.type ||
                'undefined' === typeof this.params.panel
            ) {

                _panelAttachEvents.call(this);

                return;

            }

            _panelAttachEvents.call(this);

            var panel = this;

            panel.expanded.bind(function (expanded) {

                var parent = api.panel(panel.params.panel);

                if (expanded) {

                    parent.contentContainer.addClass('current-panel-parent');

                } else {

                    parent.contentContainer.removeClass('current-panel-parent');

                }

            });

            panel.container.find('.customize-panel-back')
                .off('click keydown')
                .on('click keydown', function (event) {

                    if (api.utils.isKeydownButNotEnterEvent(event)) {

                        return;

                    }

                    event.preventDefault(); // Keep this AFTER the key filter above

                    if (panel.expanded()) {

                        api.panel(panel.params.panel).expand();

                    }

                });

        },
        embed: function () {

            if (
                'pe_panel' !== this.params.type ||
                'undefined' === typeof this.params.panel
            ) {

                _panelEmbed.call(this);

                return;

            }

            _panelEmbed.call(this);

            var panel = this;
            var parentContainer = $('#sub-accordion-panel-' + this.params.panel);

            parentContainer.append(panel.headContainer);

        },
        isContextuallyActive: function () {

            if (
                'pe_panel' !== this.params.type
            ) {

                return _panelIsContextuallyActive.call(this);

            }

            var panel = this;
            var children = this._children('panel', 'section');

            api.panel.each(function (child) {

                if (!child.params.panel) {

                    return;

                }

                if (child.params.panel !== panel.id) {

                    return;

                }

                children.push(child);

            });

            children.sort(api.utils.prioritySort);

            var activeCount = 0;

            _(children).each(function (child) {

                if (child.active() && child.isContextuallyActive()) {

                    activeCount += 1;

                }

            });

            return (activeCount !== 0);

        }

    });


    // Extend Section
    var _sectionEmbed = wp.customize.Section.prototype.embed;
    var _sectionIsContextuallyActive = wp.customize.Section.prototype.isContextuallyActive;
    var _sectionAttachEvents = wp.customize.Section.prototype.attachEvents;

    wp.customize.Section = wp.customize.Section.extend({
        attachEvents: function () {

            if (
                'pe_section' !== this.params.type ||
                'undefined' === typeof this.params.section
            ) {

                _sectionAttachEvents.call(this);

                return;

            }

            _sectionAttachEvents.call(this);

            var section = this;

            section.expanded.bind(function (expanded) {

                var parent = api.section(section.params.section);

                if (expanded) {

                    parent.contentContainer.addClass('current-section-parent');

                } else {

                    parent.contentContainer.removeClass('current-section-parent');

                }

            });

            section.container.find('.customize-section-back')
                .off('click keydown')
                .on('click keydown', function (event) {

                    if (api.utils.isKeydownButNotEnterEvent(event)) {

                        return;

                    }

                    event.preventDefault(); // Keep this AFTER the key filter above

                    if (section.expanded()) {

                        api.section(section.params.section).expand();

                    }

                });

        },
        embed: function () {

            if (
                'pe_section' !== this.params.type ||
                'undefined' === typeof this.params.section
            ) {

                _sectionEmbed.call(this);

                return;

            }

            _sectionEmbed.call(this);

            var section = this;
            var parentContainer = $('#sub-accordion-section-' + this.params.section);

            parentContainer.append(section.headContainer);

        },
        isContextuallyActive: function () {

            if (
                'pe_section' !== this.params.type
            ) {

                return _sectionIsContextuallyActive.call(this);

            }

            var section = this;
            var children = this._children('section', 'control');

            api.section.each(function (child) {

                if (!child.params.section) {

                    return;

                }

                if (child.params.section !== section.id) {

                    return;

                }

                children.push(child);

            });

            children.sort(api.utils.prioritySort);

            var activeCount = 0;

            _(children).each(function (child) {

                if ('undefined' !== typeof child.isContextuallyActive) {

                    if (child.active() && child.isContextuallyActive()) {

                        activeCount += 1;

                    }

                } else {

                    if (child.active()) {

                        activeCount += 1;

                    }

                }

            });

            return (activeCount !== 0);

        }

    });


});
