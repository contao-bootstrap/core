<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use Contao\BackendUser;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard;
use Netzmacht\Contao\Toolkit\Dca\DcaManager;
use Netzmacht\Contao\Toolkit\Dca\Listener\AbstractListener;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_key_exists;
use function array_map;
use function array_merge;
use function array_unique;
use function assert;
use function implode;
use function sprintf;
use function str_replace;

final class ModuleDcaListener extends AbstractListener
{
    /** @var string */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
    protected static $name = 'tl_module';

    public function __construct(DcaManager $dcaManager, private readonly TranslatorInterface $translator)
    {
        parent::__construct($dcaManager);
    }

    /**
     * Get all templates. A templatePrefix can be defined using eval.templatePrefix.
     *
     * @param DataContainer|MultiColumnWizard $dataContainer The data container driver.
     *
     * @return array<string>
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getTemplates(DataContainer|MultiColumnWizard $dataContainer): array
    {
        $config = [];
        $prefix = '';

        // MCW compatibility
        if ($dataContainer instanceof MultiColumnWizard) {
            $field = $dataContainer->strField;
            $table = $dataContainer->strTable;
        } else {
            $field = $dataContainer->field;
            $table = $dataContainer->table;
        }

        $definition = $this->getDefinition($table);

        if ($definition->has(['fields', $field, 'eval'])) {
            $config = $definition->get(['fields', $field, 'eval']);
        }

        if (array_key_exists('templatePrefix', $config)) {
            $prefix = $config['templatePrefix'];
        }

        return Controller::getTemplateGroup($prefix);
    }

    /**
     * Generate the page picker.
     *
     * @param DataContainer $dataContainer The data container driver.
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
            str_replace(['{{link_url::', '}}'], '', $dataContainer->value),
            StringUtil::specialchars($this->translator->trans('MSC.pagepicker', [], 'contao_default')),
            StringUtil::specialchars(
                str_replace("'", "\\'", $this->translator->trans('MOD.page.0', [], 'contao_default')),
            ),
            $dataContainer->field,
            $dataContainer->field . (Input::get('act') === 'editAll' ? '_' . $dataContainer->id : ''),
            Image::getHtml(
                'pickpage.gif',
                $this->translator->trans('MSC.pagepicker', [], 'contao_default'),
                'style="vertical-align:top;cursor:pointer"',
            ),
        );
    }

    /**
     * Get all articles and return them as array.
     *
     * @return array<string,array<string|int,string>>
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getAllArticles(): array
    {
        $user = BackendUser::getInstance();
        assert($user instanceof BackendUser);

        $pids     = [];
        $articles = [];

        // Limit pages to the user's pagemounts
        if ($user->isAdmin) {
            $objArticle = Database::getInstance()->execute(
                'SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a
                LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting',
            );
        } else {
            foreach ($user->pagemounts as $id) {
                $pids[] = $id;
                $pids   = array_merge($pids, Database::getInstance()->getChildRecords($id, 'tl_page'));
            }

            if ($pids === []) {
                return $articles;
            }

            $pids = implode(',', array_map('intval', array_unique($pids)));

            $objArticle = Database::getInstance()->execute(
                'SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent
                FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(' . $pids . ')
                ORDER BY parent, a.sorting',
            );
        }

        // Edit the result
        if ($objArticle->numRows) {
            Controller::loadLanguageFile('tl_article');

            while ($objArticle->next()) {
                $transKey    = 'tl_article.' . $objArticle->inColumn;
                $translation = $this->translator->trans($transKey);
                $key         = $objArticle->parent . ' (ID ' . $objArticle->pid . ')';

                $articles[$key][$objArticle->id] = $objArticle->title
                    . ' ('
                    . ($translation === $transKey ? $objArticle->inColumn : $translation)
                    . ', ID '
                    . $objArticle->id
                    . ')';
            }
        }

        return $articles;
    }

    /**
     * Get all modules prepared for select wizard.
     *
     * @return array<string,array<int|string,string>>
     */
    public function getAllModules(): array
    {
        $modules = [];
        $query   = 'SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id';

        if (Input::get('table') === 'tl_module' && Input::get('act') === 'edit') {
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
