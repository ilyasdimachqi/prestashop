{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

<div class="modal" id="eg-product-alert-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' mod='egalertstock'}">
                    <span aria-hidden="true"><i class="material-icons">close</i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <form method="post" id="eg-alert-form">
                            <p class=" card-title">{l s="This product is currently out of stock." mod='egalertstock'}</p>
                            <input type="hidden" name="id_product" value="{$idProduct|escape:'htmlall':'UTF-8'}"/>
                            <input type="hidden" name="id_user" value="{$idUser|escape:'htmlall':'UTF-8'}">
                            {if $idUser == 0}
                            <div class="form-group row">
                                <label for="customerEmail" class="col-md-4 col-form-label">{l s='Email' mod='egalertstock'}</label>
                                <div class="col-md-8">
                                    <input type="email"
                                           class="form-control"
                                           id="customerEmail"
                                           name="customerEmail"
                                           placeholder="{l s='Enter your Email ' mod='egalertstock'}"
                                    >
                                </div>
                            </div>

                            {else}
                            <div class="form-group">
                                <div class="col-md-8 eg-form-element">
                                    <input type="hidden"
                                           class="form-control"
                                           id="customerEmail"
                                           name="customerEmail"
                                           value="{$emailAlert|escape:'htmlall':'UTF-8'}"
                                           placeholder="{l s='Enter your Email ' mod='egalertstock'}">
                                </div>
                            {/if}
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary eg-alert-submit-button float-xs-right">
                                    {l s='Notify' mod='egalertstock'}
                                </button>
                            </div>
                            <br><br><br>
                            <div class="message_submit">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
