/**
 * * Copyright since 2007 PrestaShop SA and Contributors
 *  * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *  *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 *  * that is bundled with this package in the file LICENSE.md.
 *  * It is also available through the world-wide-web at this URL:
 *  * https://opensource.org/licenses/AFL-3.0
 *  * If you did not receive a copy of the license and are unable to
 *  * obtain it through the world-wide-web, please send an email
 *  * to license@prestashop.com so we can send you a copy immediately.
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 *  * versions in the future. If you wish to customize PrestaShop for your
 *  * needs please refer to https://devdocs.prestashop.com/ for more information.
 *  *
 *  * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 *  * @copyright Since 2007 PrestaShop SA and Contributors
 *  * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

$(document).ready(function () {
    // Append Modal
    $('body').on('click','#display-alert-stock', function(e){
        var idProduct = $(this).data('id_product');
        var idUser = $(this).data('id_user');
        var emailAlert = $(this).data('email_user');

        $.ajax({
            url: eg_alert_link,
            type: "POST",
            cache: false,
            dataType: "json",
            data: {
                ajax: true,
                action: 'getModal',
                id_product: idProduct,
                id_user: idUser,
                email_user: emailAlert,
            },
            success : function(res){
                if (typeof res.modal != 'undefined') {
                    $("#eg-product-alert-modal").remove();
                    $("body").append(res.modal);
                    $("#eg-product-alert-modal").modal("show");
                }
            }
        });
    });
    function initAlert(message, isSuccess) {
        var alertClass = isSuccess ? 'alert-success' : 'alert-danger';

        // Supprime toutes les alertes existantes
        $('.alert-message').remove();

        // Crée un nouvel élément d'alerte
        var alertElement = $('<div class="alert-message ' + alertClass + '">' + message + '</div>');

        // Ajoute l'élément d'alerte à la fin du conteneur du bouton
        $('.message_submit').append(alertElement);

        // Facultatif : Définissez un délai pour masquer automatiquement l'alerte
        setTimeout(function () {
            alertElement.fadeOut('slow', function () {
                $(this).remove();
            });
        }, 8000);
    }


    // Submit Alert
    $('body').on('click', '.eg-alert-submit-button', function(e){
        var $this = $(this);
        var data = $('#eg-alert-form').serialize();
        if(data){
            $.ajax({
                url: eg_alert_link,
                type: "POST",
                cache: false,
                dataType: "json",
                data: {
                    ajax: true,
                    action: 'submitAlert',
                    data: data,
                },
                beforeSend: function() {
                    $('#eg-alert-form .alert').remove();
                },
                success: function(res) {
                    console.log(res);
                    if (res.status === 1) {
                        console.log('Success', res.message);
                        initAlert(res.message, true);
                        refreshFields();
                        setTimeout(initPopupForm, 5000);
                        $this.parents('form').trigger('reset');
                        $("#eg-product-alert-modal").modal("hide");
                        $("#eg-product-alert-modal").remove();

                        console.log('Message:', res.message);
                        $('.alert-message').text(res.message);
                    } else if (res.status === 0) {
                        console.log('Failure', res.message);
                        initAlert(res.message, false);

                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    });
});

