<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Index-only migration, kept separate from the column migrations so that a failure while indexing a
 * large `messages` table cannot roll back the columns the feature actually depends on.
 *
 * Note that `whatsapp_message_id` deliberately gets a plain (prefix) index rather than a UNIQUE one:
 * the column is TEXT, duplicates already exist on live installs, and a UNIQUE index would make
 * `php artisan migrate` fail on upgrade. Uniqueness is enforced in application code instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages') && DB::getDriverName() === 'mysql' && !$this->indexExists('messages', 'messages_whatsapp_message_id_index')) {
            DB::statement('ALTER TABLE `messages` ADD INDEX `messages_whatsapp_message_id_index` (`whatsapp_message_id`(64))');
        }

        $this->addIndex('messages', ['conversation_id', 'ordering']);
        $this->addIndex('messages', ['conversation_id', 'type', 'status']);
        $this->addIndex('messages', ['media_sync_status']);
        $this->addIndex('conversations', ['whatsapp_account_id', 'contact_id']);
        $this->addIndex('contacts', ['user_id', 'mobile_code', 'mobile']);
        $this->addIndex('whatsapp_accounts', ['phone_number_id']);
        $this->addIndex('whatsapp_accounts', ['whatsapp_business_account_id']);
    }

    public function down(): void
    {
        if (Schema::hasTable('messages') && DB::getDriverName() === 'mysql' && $this->indexExists('messages', 'messages_whatsapp_message_id_index')) {
            DB::statement('ALTER TABLE `messages` DROP INDEX `messages_whatsapp_message_id_index`');
        }

        $this->dropIndex('messages', ['conversation_id', 'ordering']);
        $this->dropIndex('messages', ['conversation_id', 'type', 'status']);
        $this->dropIndex('messages', ['media_sync_status']);
        $this->dropIndex('conversations', ['whatsapp_account_id', 'contact_id']);
        $this->dropIndex('contacts', ['user_id', 'mobile_code', 'mobile']);
        $this->dropIndex('whatsapp_accounts', ['phone_number_id']);
        $this->dropIndex('whatsapp_accounts', ['whatsapp_business_account_id']);
    }

    private function indexName(string $table, array $columns): string
    {
        return $table . '_' . implode('_', $columns) . '_index';
    }

    private function addIndex(string $table, array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                return;
            }
        }

        $name = $this->indexName($table, $columns);

        if ($this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
            $blueprint->index($columns, $name);
        });
    }

    private function dropIndex(string $table, array $columns): void
    {
        $name = $this->indexName($table, $columns);

        if (!Schema::hasTable($table) || !$this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($name) {
            $blueprint->dropIndex($name);
        });
    }

    private function indexExists(string $table, string $name): bool
    {
        foreach (Schema::getIndexes($table) as $index) {
            if (($index['name'] ?? null) === $name) {
                return true;
            }
        }

        return false;
    }
};
