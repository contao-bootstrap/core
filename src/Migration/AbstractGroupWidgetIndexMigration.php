<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Override;

use function is_numeric;
use function serialize;

/**
 * Abstract migration class which support to migrate from the Multicolumnwizard to the group widget.
 *
 * Contao does not support multiple usage of migration classes. Therefore, using this class requires to create an own
 * migration class
 */
abstract class AbstractGroupWidgetIndexMigration extends AbstractMigration
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $tableName,
        private readonly string $columnName,
    ) {
    }

    #[Override]
    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();
        if (! $schemaManager->tablesExist([$this->tableName])) {
            return false;
        }

        if (! $schemaManager->introspectTable($this->tableName)->hasColumn($this->columnName)) {
            return false;
        }

        $platform = $this->connection->getDatabasePlatform();
        $affected = (int) $this->connection->fetchOne(
            'SELECT COUNT(*) FROM '
            . $platform->quoteSingleIdentifier($this->tableName)
            . ' WHERE '
            . $platform->quoteSingleIdentifier($this->columnName)
            . ' LIKE \'a:%:{i:0;%\'',
        );

        return $affected > 0;
    }

    #[Override]
    public function run(): MigrationResult
    {
        $platform = $this->connection->getDatabasePlatform();
        $result   = $this->connection->fetchAllAssociative(
            'SELECT * FROM '
            . $platform->quoteSingleIdentifier($this->tableName)
            . ' WHERE '
            . $platform->quoteSingleIdentifier($this->columnName)
            . ' LIKE \'a:%:{i:0;%\'',
        );

        foreach ($result as $row) {
            $templates = [];

            foreach (StringUtil::deserialize($row[$this->columnName], true) as $key => $template) {
                if (is_numeric($key)) {
                    $key = (int) $key;
                    ++$key;
                }

                $templates[$key] = $template;
            }

            $this->connection->update(
                $this->tableName,
                [$this->columnName => serialize($templates)],
                ['id' => $row['id']],
            );
        }

        return $this->createResult(true);
    }
}
