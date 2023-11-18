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
class EgCategoryBlockClass extends ObjectModel
{
    public $id_eg_category;
    public $id_category;
    public $title;
    public $subtitle;
    public $image;
    public $url;
    public $position;
    public $active = true;

    public static $definition = array(
        'table' => 'eg_category',
        'primary' => 'id_eg_category',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'id_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'image'        => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'position'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'      => array('type' => self::TYPE_BOOL),

            'title'        => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'subtitle'      => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'url'   => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
        ),
    );

    public function add($auto_date = true, $null_values = false)
    {
        $this->position = (int) $this->getMaxPosition() + 1;
        return parent::add($auto_date, $null_values);
    }

    public static function getMaxPosition()
    {
        $query = new DbQuery();
        $query->select('MAX(position)');
        $query->from('eg_category', 'eg');
        $response = Db::getInstance()->getRow($query);

        return ($response && $response['MAX(position)'] !== null) ? $response['MAX(position)'] : -1;
    }

    public static function getCategories($maxDisplay = null)
    {
        $query = new DbQuery();
        $query->select('t.*, tl.title, tl.subtitle, tl.url, tl.image');
        $query->from('eg_category', 't');
        $query->leftJoin('eg_category_lang', 'tl', 't.id_eg_category = tl.id_eg_category AND
        tl.id_lang = ' . (int)Context::getContext()->language->id);
        $query->where('t.status = "approuve"');
        $query->orderBy('t.position ASC');

        if ($maxDisplay !== null) {
            $query->limit($maxDisplay);
        }

        return Db::getInstance()->executeS($query);
    }

    public function updatePosition($way, $position)
    {
        $query = new DbQuery();
        $query->select('eg.`id_eg_category`, eg.`position`');
        $query->from('eg_category', 'eg');
        $query->orderBy('eg.`position` ASC');
        $tabs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$tabs) {
            return false;
        }

        foreach ($tabs as $tab) {
            if ((int)$tab['id_eg_category'] == (int)$this->id) {
                $moved_tab = $tab;
            }
        }

        if (!isset($moved_tab) || !isset($position)) {
            return false;
        }

        $positionUpdateQuery = '
            UPDATE `' . _DB_PREFIX_ . 'eg_category`
            SET `position` = `position` ' . ($way ? '- 1' : '+ 1') . '
            WHERE `position` ' . ($way
                ? '> ' . (int)$moved_tab['position'] . ' AND `position` <= ' . (int)$position
                : '< ' . (int)$moved_tab['position'] . ' AND `position` >= ' . (int)$position);

        $updatePositionResult = Db::getInstance()->execute($positionUpdateQuery);

        $idUpdateQuery = '
            UPDATE `' . _DB_PREFIX_ . 'eg_category`
            SET `position` = ' . (int)$position . '
            WHERE `id_eg_category` = ' . (int)$moved_tab['id_eg_category'];

        $idUpdateResult = Db::getInstance()->execute($idUpdateQuery);

        return $updatePositionResult && $idUpdateResult;
    }

    public function stUploadImage($item)
    {
        $result = array(
            'error' => array(),
            'image' => '',
        );
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name'])) {
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);
            $imageSize = @getimagesize($_FILES[$item]['tmp_name']);
            if ($this->isCorrectImageFileExt($_FILES[$item]['name'])) {
                $imageName = explode('.', $_FILES[$item]['name']);
                $imageExt = $imageName[1];
                $coverImageName = $name . '-' . rand(0, 1000) . '.' . $imageExt;
                $destinationFile = _PS_MODULE_DIR_ . 'egcategoryblock/views/img/' . $coverImageName;
                if (!move_uploaded_file($_FILES[$item]['tmp_name'], $destinationFile)) {
                    $result['error'][] = $this->l('An error occurred during move image.');
                }
                if (!count($result['error'])) {
                    $result['image'] = $coverImageName;
                    $result['width'] = $imageSize[0];
                    $result['height'] = $imageSize[1];
                }
                return $result;
            }
        } else {
            return $result;
        }
    }

    public function isCorrectImageFileExt($filename, $authorizedExtensions = null)
    {
        // Filter on file extension
        if ($authorizedExtensions === null) {
            $authorizedExtensions = array('gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg');
        }
        $nameExplode = explode('.', $filename);
        if (count($nameExplode) >= 2) {
            $currentExtension = strtolower($nameExplode[count($nameExplode) - 1]);
            if (!in_array($currentExtension, $authorizedExtensions)) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
    public static function showLogo($value)
    {
        $src = __PS_BASE_URI__. 'modules/egcategoryblock/views/img/'.$value;
        return $value ? '<img src="'.$src.'" width="80" height="40px" class="img img-thumbnail"/>' : '-';
    }

}
