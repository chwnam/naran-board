<?php


namespace Naran\Board\Models\Fields;


class OptionFields
{
    private static $fields = [];

    private $optionName;

    private $setting;

    public static function getField($optionName)
    {
        if (!isset(static::$fields[$optionName])) {
            static::$fields[$optionName] = new static($optionName);
        }

        return static::$fields[$optionName];
    }

    private function __construct($optionName)
    {
        $this->optionName = sanitize_key($optionName);

        $settings = get_registered_settings();
        if (isset($settings[$this->optionName])) {
            $this->setting = &$settings[$this->optionName];
        } else {
            $this->setting = [];
        }
    }

    public function get()
    {
        return get_option($this->optionName);
    }

    public function update($value)
    {
        return update_option($this->optionName, $value);
    }

    public function delete()
    {
        return delete_option($this->optionName);
    }
}
