<?php

namespace Naran\Board\Models\Stores;

use Naran\Board\Models\Objects\Board;

/**
 * Class BoardDataStore
 *
 * @package Naran\Board\Models\Stores
 *
 * @method Board get(string $id)
 */
class BoardDataStore extends OptionTableStores
{
    public function import($records)
    {
        $values = [];

        foreach ($records as $id => $record) {
            if ($record) {
                $values[$id] = Board::fromArray($record);
            }
        }

        return $values;
    }

    public function export()
    {
        $array = [];

        foreach ($this->getValues() as $k => $v) {
            if ($v instanceof Board) {
                $array[$k] = $v->toArray();
            }
        }

        return $array;
    }

    public static function getStorageKey()
    {
        return 'nrbrd_board_data';
    }

    public static function getDefaultValues()
    {
        return [];
    }
}
