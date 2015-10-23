<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

/**
 * Class LuminaireIssueBundle
 */
class LuminaireIssueBundle implements Migration, NoteExtensionAwareInterface, ActivityExtensionAwareInterface
{
    /**
     * @var NoteExtension
     */
    protected $noteExtension;

    /**
     * @var ActivityExtension
     */
    protected $activityExtension;

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
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }

    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createLuminaireIssueTable($schema);
        $this->createLuminaireIssuePriorityTable($schema);
        $this->createLuminaireIssueResolutionTable($schema);
        $this->createLuminaireIssueTypeTable($schema);
        $this->createLuminaireUserToIssueTable($schema);

        /** Foreign keys generation **/
        $this->addLuminaireIssueForeignKeys($schema);
        $this->addLuminaireUserToIssueForeignKeys($schema);
    }

    /**
     * Create luminaire_issue table
     *
     * @param Schema $schema
     */
    protected function createLuminaireIssueTable(Schema $schema)
    {
        $table = $schema->createTable('luminaire_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_name', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('priority_name', 'string', ['length' => 16]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', []);
        $table->addColumn('type_name', 'string', ['length' => 16]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_901BA0501023C4EE');
        $table->addIndex(['type_name'], 'IDX_901BA050892CBB0E', []);
        $table->addIndex(['priority_name'], 'IDX_901BA050965BD3DF', []);
        $table->addIndex(['resolution_name'], 'IDX_901BA0508EEEA2E1', []);
        $table->addIndex(['reporter_id'], 'IDX_901BA050E1CFE6F5', []);
        $table->addIndex(['assignee_id'], 'IDX_901BA05059EC7D60', []);
        $table->addIndex(['parent_id'], 'IDX_901BA050727ACA70', []);
        $table->addIndex(['workflow_step_id'], 'IDX_901BA05071FE882C', []);

        $this->noteExtension->addNoteAssociation($schema, $table->getName());
        $this->activityExtension->addActivityAssociation($schema, 'oro_email', $table->getName(), true);
    }

    /**
     * Create luminaire_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createLuminaireIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('luminaire_issue_priority');
        $table->addColumn('name', 'string', ['length' => 16]);
        $table->addColumn('order', 'integer', []);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['name']);
    }

    /**
     * Create luminaire_issue_resolution table
     *
     * @param Schema $schema
     */
    protected function createLuminaireIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('luminaire_issue_resolution');
        $table->addColumn('name', 'string', ['length' => 16]);
        $table->addColumn('order', 'integer', []);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['name']);
    }

    /**
     * Create luminaire_issue_type table
     *
     * @param Schema $schema
     */
    protected function createLuminaireIssueTypeTable(Schema $schema)
    {
        $table = $schema->createTable('luminaire_issue_type');
        $table->addColumn('name', 'string', ['length' => 16]);
        $table->addColumn('order', 'integer', []);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['name']);
    }

    /**
     * Create luminaire_user_to_issue table
     *
     * @param Schema $schema
     */
    protected function createLuminaireUserToIssueTable(Schema $schema)
    {
        $table = $schema->createTable('luminaire_user_to_issue');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_8236A7725E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_8236A772A76ED395', []);
    }

    /**
     * Add luminaire_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addLuminaireIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('luminaire_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue_resolution'),
            ['resolution_name'],
            ['name'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue_priority'),
            ['priority_name'],
            ['name'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue_type'),
            ['type_name'],
            ['name'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * Add luminaire_user_to_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addLuminaireUserToIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('luminaire_user_to_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
