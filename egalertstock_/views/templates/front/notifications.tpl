{**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{extends file='page.tpl'}
{block name='page_content'}
    <div class="container">
        <div class="title-content" style="text-align: center">
            <h2 class="page-heading">{l s='Add Testimonial' mod='egtestimony'}</h2>
            <span class="subtitle">
            {l s='We welcome your testimonials - please enter yours using the form below' mod='egtestimony'}
        </span>
        </div>
        <div id="content-testimonial-wrapper" class="col-xs-12 col-sm-12 col-md-12">
            <section id="main">
                <div id="content" class="page-content card card-block">
                    <section class="contact-form">
                        <form action="" method="post" enctype="multipart/form-data">
                            <section class="form-fields">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="title">
                                        {l s='Title' mod='egtestimony'}<em>*</em>
                                    </label>
                                    <div class="col-md-6">
                                        <input id="title"
                                               class="form-control"
                                               name="title"
                                               type="text"
                                               placeholder="{l s='Enter q title' mod='egtestimony'}"
                                               required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="file-upload">
                                        {l s='Avatar' mod='egtestimony'}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="file"
                                               id="image"
                                               name="image"
                                               accept="image/png, image/jpeg">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="testimony-form-message">
                                        {l s='Message' mod='egtestimony'}<em>*</em>
                                    </label>
                                    <div class="col-md-9">
                                        <textarea id="testimony-form-message"
                                                  class="form-control"
                                                  name="message"
                                                  required
                                                  rows="3"></textarea>
                                    </div>
                                </div>
                            </section>
                            <footer class="form-footer text-sm-right">
                                <input class="btn btn-primary"
                                       type="submit"
                                       name="submitTestimony"
                                       value="{l s='Submit' mod='egtestimony'}">
                            </footer>
                        </form>
                    </section>
                </div>
            </section>
        </div>
{/block}
