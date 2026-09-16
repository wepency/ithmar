<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddBondsTypesToHistoriesTable extends Migration
{
    /**
     * The history types the bonds visibility toggle records.
     *
     * @var array
     */
    protected $types = ['excluded_from_bonds', 'included_in_bonds'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $column = $this->currentType();

        // The column is a plain string on a fresh install and only needs
        // widening when the database still carries the legacy enum.
        if (is_null($column) || ! str_starts_with($column, 'enum(')) {
            return;
        }

        $values = $this->values($column);

        foreach ($this->types as $type) {
            if (! in_array($type, $values)) {
                $values[] = $type;
            }
        }

        $this->setEnum($values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $column = $this->currentType();

        if (is_null($column) || ! str_starts_with($column, 'enum(')) {
            return;
        }

        if (DB::table('histories')->whereIn('type', $this->types)->exists()) {
            return;
        }

        $values = array_values(array_diff($this->values($column), $this->types));

        $this->setEnum($values);
    }

    /**
     * The column definition currently in the database.
     *
     * @return string|null
     */
    protected function currentType()
    {
        if (! Schema::hasColumn('histories', 'type')) {
            return null;
        }

        $column = DB::selectOne(
            'SELECT COLUMN_TYPE as column_type FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            ['histories', 'type']
        );

        return $column->column_type ?? null;
    }

    /**
     * Pull the allowed values out of an enum definition.
     *
     * @param  string  $column
     * @return array
     */
    protected function values($column)
    {
        preg_match_all("/'((?:[^']|'')*)'/", $column, $matches);

        return array_map(function ($value) {
            return str_replace("''", "'", $value);
        }, $matches[1]);
    }

    /**
     * Rewrite the enum with the given values.
     *
     * @param  array  $values
     * @return void
     */
    protected function setEnum(array $values)
    {
        $list = implode(', ', array_map(function ($value) {
            return "'" . str_replace("'", "''", $value) . "'";
        }, $values));

        DB::statement("ALTER TABLE `histories` MODIFY `type` ENUM($list)
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
    }
}
