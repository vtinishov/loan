<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%loan_request}}`.
 */
class m260318_153443_create_loan_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%loan_request}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => $this->string(20)->defaultValue('pending'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-loan_request-user_id', '{{%loan_request}}', 'user_id');
        $this->createIndex('idx-loan_request-status', '{{%loan_request}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%loan_request}}');
    }
}
