<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Netzmacht\Contao\Toolkit\Dca\DcaManager;
use Netzmacht\Contao\Toolkit\Dca\Listener\AbstractListener;
use Symfony\Component\Routing\RouterInterface;
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

    /** @param Adapter<Input> $inputAdapter */
    public function __construct(
        DcaManager $dcaManager,
        private readonly TranslatorInterface $translator,
        private readonly Connection $connection,
        private readonly ContaoFramework $framework,
        private readonly RouterInterface $router,
        private readonly Adapter $inputAdapter,
    ) {
        parent::__construct($dcaManager);
    }

    /**
     * Get all templates. A templatePrefix can be defined using eval.templatePrefix.
     *
     * @param DataContainer $dataContainer The data container driver.
     *
     * @return array<string>
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getTemplates(DataContainer $dataContainer): array
    {
        $config = [];
        $prefix = '';

        $field      = $dataContainer->field;
        $table      = $dataContainer->table;
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
        $template  = ' <a href="%s" title="%s"';
        $template .= ' onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'%s\'';
        $template .= ',\'url\':this.href,\'id\':\'%s\',\'tag\':\'ctrl_%s\',\'self\':this});return false">%s</a>';

        return sprintf(
            $template,
            $this->router->generate(
                'contao_backend_page',
                [
                    'do'    => $this->inputAdapter->get('do'),
                    'table' => $dataContainer->table,
                    'field' => $dataContainer->field,
                    'value' => str_replace(['{{link_url::', '}}'], '', $dataContainer->value),
                ],
            ),
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
            $result = $this->connection->executeQuery(
                'SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a
                LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting',
            );
        } else {
            foreach ($user->pagemounts as $id) {
                $pids[] = $id;
                $pids   = array_merge(
                    $pids,
                    $this->framework->createInstance(Database::class)->getChildRecords($id, 'tl_page'),
                );
            }

            if ($pids === []) {
                return $articles;
            }

            $pids = implode(',', array_map('intval', array_unique($pids)));

            $result = $this->connection->executeQuery(
                'SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent
                FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(' . $pids . ')
                ORDER BY parent, a.sorting',
            );
        }

        // Edit the result
        if ($result->rowCount()) {
            Controller::loadLanguageFile('tl_article');

            while ($row = $result->fetchAssociative()) {
                $transKey    = 'tl_article.' . $row['inColumn'];
                $translation = $this->translator->trans($transKey);
                $key         = $row['parent'] . ' (ID ' . $row['pid'] . ')';

                $articles[$key][$row['id']] = $row['title']
                    . ' ('
                    . ($translation === $transKey ? $row['inColumn'] : $translation)
                    . ', ID '
                    . $row['id']
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

        if ($this->inputAdapter->get('table') === 'tl_module' && $this->inputAdapter->get('act') === 'edit') {
            $query .= ' WHERE m.id != :id';
        }

        $query .= ' ORDER BY t.name, m.name';
        $result = $this->connection->executeQuery($query, ['id' => $this->inputAdapter->get('id')]);

        while ($row = $result->fetchAssociative()) {
            $modules[$row['theme']][$row['id']] = $row['name'] . ' (ID ' . $row['id'] . ')';
        }

        return $modules;
    }
}
