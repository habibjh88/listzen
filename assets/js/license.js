(function ($) {
    'use strict';

    var $panel = $('.listzen-license-panel');
    if (!$panel.length || typeof listzenLicense === 'undefined') {
        return;
    }

    var $input        = $panel.find('#listzen_license_key');
    var $toggle       = $panel.find('.listzen-license-toggle');
    var $activate     = $panel.find('.listzen-license-activate');
    var $deactivate   = $panel.find('.listzen-license-deactivate');
    var $statusBadge  = $panel.find('.listzen-license-status-badge');
    var $statusText   = $statusBadge.find('.listzen-status-text');
    var $notice       = $panel.find('.listzen-license-notice');
    var $buttons      = $panel.find('.listzen-license-btn');

    var L10n = (typeof listzenLicense.i18n === 'object' && listzenLicense.i18n) ? listzenLicense.i18n : {};
    var STR = {
        activated:    L10n.activated    || 'Activated',
        notActive:    L10n.notActivated || 'Not Activated',
        activating:   L10n.activating   || 'Activating…',
        deactivating: L10n.deactivating || 'Deactivating…',
        emptyKey:     L10n.emptyKey     || 'Please enter your license key.',
        networkError: L10n.networkError || 'Network error. Please try again.',
        unexpected:   L10n.unexpected   || 'Unexpected response. Please try again.',
        expiresLabel: L10n.expiresLabel || 'Expires:',
        lifetime:     L10n.lifetime     || 'Lifetime license',
        confirmDeactivate: L10n.confirmDeactivate || 'Do you want to deactivate your license from this site?'
    };

    function setBusy(busy) {
        if (busy) {
            $buttons.prop('disabled', true);
        } else {
            var isActive = $panel.attr('data-status') === 'valid';
            $activate.prop('disabled', isActive);
            $deactivate.prop('disabled', !isActive);
        }
        $panel.toggleClass('is-busy', !!busy);
    }

    function setButtonLoading($btn, loading, loadingText) {
        $btn.toggleClass('is-loading', !!loading);
        if (loading) {
            $btn.data('original-text', $btn.find('.listzen-btn-text').text());
            $btn.find('.listzen-btn-text').text(loadingText);
        } else {
            var orig = $btn.data('original-text');
            if (orig) {
                $btn.find('.listzen-btn-text').text(orig);
            }
        }
    }

    function showNotice(message, type) {
        if (!message) {
            $notice.attr('hidden', true).removeClass('is-success is-error').empty();
            return;
        }
        $notice
            .removeClass('is-success is-error')
            .addClass(type === 'success' ? 'is-success' : 'is-error')
            .html(message)
            .removeAttr('hidden');
    }

    function applyStatus(status, expires) {
        var isActive = status === 'valid';
        $panel.attr('data-status', isActive ? 'valid' : 'invalid');

        $statusBadge
            .toggleClass('is-active', isActive)
            .toggleClass('is-inactive', !isActive);
        $statusText.text(isActive ? STR.activated : STR.notActive);

        $activate.prop('disabled', isActive);
        $deactivate.prop('disabled', !isActive);

        $input.prop('readonly', isActive);

        $panel.find('.listzen-license-expires').remove();
        if (isActive && expires) {
            var isLifetime = /^lifetime$/i.test(expires);
            $('<p class="listzen-license-expires"></p>')
                .text(isLifetime ? STR.lifetime : (STR.expiresLabel + ' ' + expires))
                .insertAfter($notice);
        }
    }

    // Toggle key visibility.
    $toggle.on('click', function () {
        var isPwd = $input.attr('type') === 'password';
        $input.attr('type', isPwd ? 'text' : 'password');
        $panel.toggleClass('is-revealed', isPwd);
    });

    // Activate / Deactivate.
    $panel.on('click', '.listzen-license-btn', function (e) {
        e.preventDefault();
        var $btn   = $(this);
        var action = $btn.data('action');
        var key    = ($input.val() || '').trim();

        if (action === 'license_activate' && !key) {
            $input.trigger('focus');
            showNotice(STR.emptyKey, 'error');
            return;
        }

        if (action === 'license_deactivate' && !window.confirm(STR.confirmDeactivate)) {
            return;
        }

        showNotice('', null);
        setBusy(true);
        setButtonLoading(
            $btn,
            true,
            action === 'license_activate' ? STR.activating : STR.deactivating
        );

        $.ajax({
            type: 'post',
            url: listzenLicense.ajaxurl,
            dataType: 'json',
            data: {
                action: 'listzen_manage_license',
                type: action,
                license_key: key,
                nonce: listzenLicense.nonce
            }
        }).done(function (res) {
            if (!res) {
                showNotice(STR.unexpected, 'error');
                return;
            }
            applyStatus(res.status, res.expires);
            showNotice(res.msg || '', res.error ? 'error' : 'success');
        }).fail(function () {
            showNotice(STR.networkError, 'error');
        }).always(function () {
            setBusy(false);
            setButtonLoading($btn, false);
        });
    });

    // Submit on Enter when not activated.
    $input.on('keydown', function (e) {
        if (e.key === 'Enter' && !$activate.prop('disabled')) {
            e.preventDefault();
            $activate.trigger('click');
        }
    });

    // Hide stale notice as soon as the user edits the key.
    $input.on('input', function () {
        if (!$notice.is(':hidden')) {
            showNotice('', null);
        }
    });
})(jQuery);
