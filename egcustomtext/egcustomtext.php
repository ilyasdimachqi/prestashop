<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

class EgCustomText extends Module
{
    protected $domain;
    public function __construct()
    {
        $this->name = 'egcustomtext';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Egio Digital';
        $this->need_instance = 0;
        $this->bootstrap =true;

        parent::__construct();

        $this->domain = 'Modules.Egcustomtext.Egcustomtext';
        $this->displayName = $this->trans('EG Custom Text', [], $this->domain);
        $this->description = $this->trans('Module EG Custom Text', [], $this->domain);
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], $this->domain);
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('header') ||
            !$this->registerHook('displayHome') ||
            !Configuration::updateValue('EG_CUSTOM_TEXT_ACTIVE', 1) ||
            !Configuration::updateValue('EG_CUSTOM_TEXT_TITLE', 'Default Title') ||
            !Configuration::updateValue('EG_CUSTOM_TEXT_DESCRIPTION', 'Default Description')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('EG_CUSTOM_TEXT_ACTIVE') ||
            !Configuration::deleteByName('EG_CUSTOM_TEXT_TITLE') ||
            !Configuration::deleteByName('EG_CUSTOM_TEXT_DESCRIPTION')
        ) {
            return false;
        }
        return true;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookDisplayHome($params)
    {
        $active = Configuration::get('EG_CUSTOM_TEXT_ACTIVE',$this->context->language->id);
        $title = Configuration::get('EG_CUSTOM_TEXT_TITLE',$this->context->language->id);
        $description = Configuration::get('EG_CUSTOM_TEXT_DESCRIPTION',$this->context->language->id);
        if ($active) {
            $this->context->smarty->assign([
                'EG_CUSTOM_TEXT_TITLE' => $title,
                'EG_CUSTOM_TEXT_DESCRIPTION' => $description,
            ]);
            return $this->display(__FILE__, 'views/templates/hook/egcustomtext.tpl');
        }
        return '';
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submitModule')) {
            $active = Tools::getValue('EG_CUSTOM_TEXT_ACTIVE');
            $title = [];
            $description = [];
            $languages = Language::getLanguages(false);

            foreach ($languages as $lang) {
                $title[$lang['id_lang']] = Tools::getValue('EG_CUSTOM_TEXT_TITLE_'
                    . $lang['id_lang']);
                $description[$lang['id_lang']] = Tools::getValue('EG_CUSTOM_TEXT_DESCRIPTION_'
                    . $lang['id_lang']);
            }

            Configuration::updateValue('EG_CUSTOM_TEXT_TITLE', $title);
            Configuration::updateValue('EG_CUSTOM_TEXT_DESCRIPTION', $description);
            Configuration::updateValue('EG_CUSTOM_TEXT_ACTIVE', $active);
            $output .= $this->displayConfirmation($this->trans('Settings updated'));
        }

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];
        return $helper->generateForm([$this->getConfigForm()]);
    }

    public function getConfigFieldsValues()
    {
        $title = [];
        $description = [];
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = (string) Tools::getValue('EG_CUSTOM_TEXT_TITLE_'
                . $lang['id_lang'], Configuration::get('EG_CUSTOM_TEXT_TITLE', $lang['id_lang']));
            $description[$lang['id_lang']] = (string) Tools::getValue('EG_CUSTOM_TEXT_DESCRIPTION_'
                . $lang['id_lang'], Configuration::get('EG_CUSTOM_TEXT_DESCRIPTION', $lang['id_lang']));
        }
        return [
            'EG_CUSTOM_TEXT_ACTIVE' => Configuration::get('EG_CUSTOM_TEXT_ACTIVE'),
            'EG_CUSTOM_TEXT_TITLE' => $title,
            'EG_CUSTOM_TEXT_DESCRIPTION' => $description,
        ];
    }
    protected function getConfigForm()
    {
        return [
            'form' => [
                'tinymce' => true,
                'legend' => [
                    'title' => $this->trans('Configuration', [], $this->domain),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->trans('Title', [], $this->domain),
                        'name' => 'EG_CUSTOM_TEXT_TITLE',
                        'lang' => true,
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->trans('Description', [], $this->domain),
                        'name' => 'EG_CUSTOM_TEXT_DESCRIPTION',
                        'lang' => true,
                        'cols' => 40,
                        'rows' => 10,
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->trans('Display', [], $this->domain),
                        'name' => 'EG_CUSTOM_TEXT_ACTIVE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('on',[], $this->domain)
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('off', [], $this->domain)
                            ]
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save',[], $this->domain),
                ],
            ],
        ];
    }
}
