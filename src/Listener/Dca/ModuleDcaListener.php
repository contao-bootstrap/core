<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use Contao\BackendUser;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use MultiColumnWizard;

/**
 * Class Module is used for tl_module.
 *
 * @package ContaoBootstrap\Core\DataContainer
 */
final class ModuleDcaListener
{
    /**
     * Get all templates. A templatePrefix can be defined using eval.templatePrefix.
     *
     * @param DataContainer $dataContainer The data container driver.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getTemplates(DataContainer $dataContainer): array
    {
        $config = array();
        $prefix = '';

        // MCW compatibility
        if ($dataContainer instanceof MultiColumnWizard) {
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

        return \Controller::getTemplateGroup($prefix);
    }

    /**
     * Generate the page picker.
     *
     * @param DataContainer $dataContainer The data container driver.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function pagePicker(DataContainer $dataContainer): string
    {
        $template  = ' <a href="contao/page.php?do=%s&amp;table=%s&amp;field=%s&amp;value=%s" title="%s"';
        $template .= ' onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'%s\'';
        $template .= ',\'url\':this.href,\'id\':\'%s\',\'tag\':\'ctrl_%s\',\'self\':this});return false">%s</a>';

        return sprintf(
            $template,
            Input::get('do'),
            $dataContainer->table,
            $dataContainer->field,
            str_replace(array('{{link_url::', '}}'), '', $dataContainer->value),
            specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']),
            specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])),
            $dataContainer->field,
            $dataContainer->field . ((\Input::get('act') == 'editAll') ? '_' . $dataContainer->id : ''),
            Image::getHtml(
                'pickpage.gif',
                $GLOBALS['TL_LANG']['MSC']['pagepicker'],
                'style="vertical-align:top;cursor:pointer"'
            )
        );
    }

    /**
     * Get all articles and return them as array.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getAllArticles(): array
    {
        $user     = BackendUser::getInstance();
        $pids     = array();
        $articles = array();

        // Limit pages to the user's pagemounts
        if ($user->isAdmin) {
            $objArticle = Database::getInstance()->execute(
                'SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a
                LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting'
            );
        } else {
            foreach ($user->pagemounts as $id) {
                $pids[] = $id;
                $pids   = array_merge($pids, Database::getInstance()->getChildRecords($id, 'tl_page'));
            }

            if (empty($pids)) {
                return $articles;
            }

            $pids = implode(',', array_map('intval', array_unique($pids)));

            $objArticle = Database::getInstance()->execute(
                'SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent
                FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(' . $pids . ')
                ORDER BY parent, a.sorting'
            );
        }

        // Edit the result
        if ($objArticle->numRows) {
            Controller::loadLanguageFile('tl_article');

            while ($objArticle->next()) {
                $key                             = $objArticle->parent . ' (ID ' . $objArticle->pid . ')';
                $articles[$key][$objArticle->id] = $objArticle->title
                    . ' (' . ($GLOBALS['TL_LANG']['tl_article'][$objArticle->inColumn] ?: $objArticle->inColumn)
                    . ', ID ' . $objArticle->id . ')';
            }
        }

        return $articles;
    }

    /**
     * Get all modules prepared for select wizard.
     *
     * @return array
     */
    public function getAllModules(): array
    {
        $modules = array();
        $query   = 'SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id';

        if (Input::get('table') == 'tl_module' && \Input::get('act') == 'edit') {
            $query .= ' WHERE m.id != ?';
        }

        $query .= ' ORDER BY t.name, m.name';
        $result = Database::getInstance()
            ->prepare($query)
            ->execute(Input::get('id'));

        while ($result->next()) {
            $modules[$result->theme][$result->id] = $result->name . ' (ID ' . $result->id . ')';
        }

        return $modules;
    }
}
