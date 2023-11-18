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

{if $categoriesList}
    <div id="custom-text">
        <h2 class="slider-title">Categories List</h2>
        <div class="slider">
            {foreach $categoriesList as $categories}
                    <div class="slider-item">
                        <div class="card">
                            <img class="card-img-top" src="{$categories.image}">
                            <div class="card-body">
                                <h5 class="card-title">{$categories.title}</h5>
                            </div>
                        </div>
                    </div>
            {/foreach}
        </div>
    </div>
{else}
    <h2 class="slider-title">No testimonials available.</h2>
{/if}
