<?php

namespace Naran\Board\Models\Stores;

use Naran\Board\Interfaces\DataStoreInterface;
use Naran\Board\Traits\Singleton;

abstract class OptionTableStores implements DataStoreInterface
{
    use Singleton;

    protected $dirty;

    protected $values;

    private function __construct()
    {
        add_action('shutdown', [$this, 'save']);
        $this->load();
    }

    public function delete($id)
    {
        if (isset($this->values[$id])) {
            unset($this->values[$id]);
            $this->dirty = true;
        }

        return $this;
    }

    public function get($id)
    {
        return $this->values[$id] ?? null;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function load()
    {
        $this->values = $this->import((array)get_option($this->getStorageKey(), $this->getDefaultValues()));
        $this->dirty  = false;

        return $this;
    }

    public function save()
    {
        if ($this->dirty) {
            update_option($this->getStorageKey(), $this->export());
            $this->dirty = false;
        }

        return $this;
    }

    public function set($id, $value)
    {
        if (!isset($this->values[$id]) || $value != $this->values[$id]) {
            $this->values[$id] = $value;
            $this->dirty       = true;
        }

        return $this;
    }

    // NOTE: implement import().
    // NOTE: implement export().
    // NOTE: implement getStorageKey().
    // NOTE: implement getDefaultValues();
}
