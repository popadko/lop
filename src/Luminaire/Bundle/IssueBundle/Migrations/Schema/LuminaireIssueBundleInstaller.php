<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Luminaire\Bundle\IssueBundle\Migrations\Schema\v1_0\LuminaireIssueBundle;

/**
 * Class LuminaireIssueBundle
 */
class LuminaireIssueBundleInstaller implements Installation
{
    /**
     * @inheritDoc
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $migration = new LuminaireIssueBundle();
        $migration->up($schema, $queries);
    }
}
