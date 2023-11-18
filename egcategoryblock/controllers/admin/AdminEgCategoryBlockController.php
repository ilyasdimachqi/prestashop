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
class AdminEgCategoryBlockController extends ModuleAdminController
{
    protected $position_identifier = 'id_eg_category';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'eg_category';
        $this->className = 'EgCategoryBlockClass';
        $this->identifier = 'id_eg_category';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'ASC';
        $this->list_no_link = true;
        $this->lang = true;
        $this->addRowAction('delete');
        Shop::addTableAssociation($this->table, ['type' => 'shop']);

        parent::__construct();

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected'),
                'confirm' => $this->trans('Delete selected items?'),
                'icon' => 'icon-trash'
            ]
        ];

        $this->fields_list = [
            'id_eg_category' => [
                'title' => $this->trans('Id')
            ],
            'title' => [
                'title' => $this->trans('title'),
                'type' => 'text',
                'class' => 'fixed-width-xxl',
                'filter_key' => 'b!title',
                'search' => true,
            ],
            'subtitle' => [
                'title' => $this->trans('subtitle'),
                'type' => 'text',
                'class' => 'fixed-width-xxl',
                'filter_key' => 'b!subtitle',
                'search' => false,
            ],
            'url' => [
                'title' => $this->trans('URL'),
                'type' => 'text',
                'class' => 'fixed-width-xxl',
                'search' => false,
            ],
            'image' => [
                'title' => $this->trans('Image'),
                'type' => 'text',
                'callback' => 'showLogo',
                'callback_object' => 'EgCategoryBlockClass',
                'class' => 'fixed-width-xxl',
                'search' => false,
            ],
            'status' => [
                'title' => $this->trans('ON'),
                'align' => 'center',
                'active' => 'status',
                'class' => 'fixed-width-sm',
                'type' => 'bool',
                'orderby' => false
            ],
            'position' => [
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-md',
            ],
        ];
    }

    public function init()
    {
        parent::init();
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
        }

    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
    }

    public function postProcess()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if ($this->action && $this->action == 'save') {
            $cover = $obj->stUploadImage('image');
            if ($cover['image']) {
                $_POST['image'] = $cover['image'];
            } else {
                $_POST['image'] = $obj->image;
            }
        }

        return parent::postProcess();
    }

    public function initProcess()
    {
        $this->context->smarty->assign([
            'uri' => $this->module->getPathUri()
        ]);
        parent::initProcess();

    }

    public function renderForm()
    {
        $this->fields_form = [
            'tinymce' => true,
            'legend' => [
                'title' => $this->trans('Bloc'),
                'icon' => 'icon-folder-close'
            ],
            'input' => [
               ['type' => 'text',
                    'label' => $this->trans('Title:'),
                    'name' => 'title',
                    'desc' => $this->trans('Please enter a title .'),
                    'required' => true,
                ],
                ['type' => 'textarea',
                    'label' => $this->trans('subtitle'),
                    'name' => 'subtitle',
                ],
                ['type' => 'text',
                    'label' => $this->trans('URL'),
                    'name' => 'url',
                    'class' => 'fixed-width-xxl',
                    'search' => true,
                ],
                ['type' => 'file',
                    'label' => $this->trans('Image:'),
                    'name' => 'image',
                    'desc' => $this->trans('Download an image from your computer'),
                ],
                ['type' => 'switch',
                    'label' => $this->trans('Display'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => [
                        ['id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('ON')
                        ],
                        ['id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('OFF')
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save'),
                'class' => 'btn btn-default pull-right'
            ],

        ];
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association'),
                'name' => 'checkBoxShopAsso',
            ];
        }

        return parent::renderForm();
    }

    /**
     * Update Positions testimonial
     */
    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $idcategory = (int)(Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value){
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int)$pos[2] === $idcategory){
                if ($banner = new EgTestimonyClass((int)$pos[2])){
                    if (isset($position) && $banner->updatePosition($way, $position)){
                        echo 'ok position '.(int)$position.' for tab '.(int)$pos[1].'\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" :
                        "Can not update tab '.(int)$idcategory.' to position '.(int)$position.' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This tab ('.(int)$idcategory.') can t be loaded"}';
                }

                break;
            }
        }
    }
}


