<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

/**
 * Class Module is used for tl_module.
 *
 * @package Netzmacht\Bootstrap\Core\Contao\DataContainer
 */
class Module
{
    /**
     * Get all templates. A templatePrefix can be defined using eval.templatePrefix.
     *
     * @param \DataContainer $dataContainer The data container driver.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getTemplates(\DataContainer $dataContainer)
    {
        $config = array();
        $prefix = '';
        $key    = null;

        // MCW compatibility
        if ($dataContainer instanceof \MultiColumnWizard) {
            $field = $dataContainer->strField;
            $table = $dataContainer->strTable;
        } else {
            $field = $dataContainer->field;
            $table = $dataContainer->table;
        }

        if (array_key_exists('eval', $GLOBALS['TL_DCA'][$table]['fields'][$field])) {
            $config = $GLOBALS['TL_DCA'][$table]['fields'][$field]['eval'];
        }

        if (array_key_exists('templatePrefix', $config)) {
            $prefix = $config['templatePrefix'];
        }

        if (array_key_exists('templateThemeId', $config)) {
            $key = $config['templateThemeId'];
        }

        $key = $key == '' ? null : $dataContainer->activeRecord->$key;

        return \Controller::getTemplateGroup($prefix, $key);
    }

    /**
     * Generate the page picker.
     *
     * @param \DataContainer $dataContainer The data container driver.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function pagePicker(\DataContainer $dataContainer)
    {
        $template  = ' <a href="contao/page.php?do=%s&amp;table=%s&amp;field=%s&amp;value=%s" title="%s"';
        $template .= ' onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'%s\'';
        $template .= ',\'url\':this.href,\'id\':\'%s\',\'tag\':\'ctrl_%s\',\'self\':this});return false">%s</a>';

        return sprintf(
            $template,
            \Input::get('do'),
            $dataContainer->table,
            $dataContainer->field,
            str_replace(array('{{link_url::', '}}'), '', $dataContainer->value),
            specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']),
            specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])),
            $dataContainer->field,
            $dataContainer->field . ((\Input::get('act') == 'editAll') ? '_' . $dataContainer->id : ''),
            \Image::getHtml(
                'pickpage.gif',
                $GLOBALS['TL_LANG']['MSC']['pagepicker'],
                'style="vertical-align:top;cursor:pointer"'
            )
        );
    }
}
