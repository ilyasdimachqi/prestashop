{*
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
    <div class="category-container">
        <div class="row">
            <h2 class="col-12">{l s='NOS CATEGORIES' mod='EgCategoryBlock'}</h2>
            {foreach $categoriesList as $category}
                <div class="col-md-4">
                    <div class="category-card">
                        <a href="{$base_url}{$category.url}">
                            <img class="img-fluid" src="{$category.image}" alt="Category Image">
                            <div>
                                <h5 class="card-title">{$category.title}</h5>
                            </div>
                        </a>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{else}
    <div class="category-container">
        <div class="row">
            <h2 class="text-center">No categories available.</h2>
        </div>
    </div>
{/if}



