<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class LuminaireIssueBundle
 */
class LuminaireIssueBundle implements Migration
{
    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->createLuminaireIssueTable($schema);
        $this->createLuminaireIssuePriorityTable($schema);
        $this->createLuminaireIssueResolutionTable($schema);
        $this->createLuminaireIssueStatusTable($schema);
        $this->createLuminaireIssueTypeTable($schema);
        $this->addLuminaireIssueForeignKeys($schema);
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
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('status_name', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('type_name', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('resolution_name', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('priority_name', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['type_name'], 'IDX_901BA050892CBB0E', []);
        $table->addIndex(['status_name'], 'IDX_901BA0506625D392', []);
        $table->addIndex(['priority_name'], 'IDX_901BA050965BD3DF', []);
        $table->addIndex(['resolution_name'], 'IDX_901BA0508EEEA2E1', []);
        $table->addIndex(['reporter_id'], 'IDX_901BA050E1CFE6F5', []);
        $table->addIndex(['assignee_id'], 'IDX_901BA05059EC7D60', []);
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
     * Create luminaire_issue_status table
     *
     * @param Schema $schema
     */
    protected function createLuminaireIssueStatusTable(Schema $schema)
    {
        $table = $schema->createTable('luminaire_issue_status');
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
     * Add luminaire_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addLuminaireIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('luminaire_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue_status'),
            ['status_name'],
            ['name'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('luminaire_issue_type'),
            ['type_name'],
            ['name'],
            ['onDelete' => null, 'onUpdate' => null]
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
            ['reporter_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}
