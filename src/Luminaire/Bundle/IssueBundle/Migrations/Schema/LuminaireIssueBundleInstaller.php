<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Luminaire\Bundle\IssueBundle\Migrations\Schema\v1_0\LuminaireIssueBundle;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

/**
 * Class LuminaireIssueBundle
 */
class LuminaireIssueBundleInstaller implements Installation, NoteExtensionAwareInterface
{
    /**
     * @var NoteExtension
     */
    protected $noteExtension;

    /**
     * @inheritDoc
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

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
        $migration->setNoteExtension($this->noteExtension);
        $migration->up($schema, $queries);
    }
}
