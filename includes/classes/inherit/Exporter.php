<?php

namespace Mashvp\Forms;

use Mashvp\Forms\Utils;

abstract class Exporter
{
    abstract public static function getAvailableExporterSettings();

    public static function getDefaultExporterSettings()
    {
        return array_map(function ($defs) {
            return Utils::get($defs, 'default');
        }, static::getAvailableExporterSettings());
    }

    abstract public function echoHeaders();
    abstract public function generateFile($data);

    public function __construct($settings = [])
    {
        $this->settings = $settings;
    }

    protected function getExporterSettings()
    {
        return array_replace_recursive(
            static::getDefaultExporterSettings(),
            $this->settings,
        );
    }

    protected function getSetting($name)
    {
        $settings = $this->getExporterSettings();

        if (is_array($settings)) {
            return Utils::get($settings, $name);
        }

        return null;
    }

    private function getFormattedDate()
    {
        return date('Y-m-d_H-i-s');
    }

    private function getObjectDescriptor()
    {
        $export_type = Utils::get($_REQUEST, 'export_type');
        $form_id = Utils::get($_REQUEST, 'form_id');

        return implode('-', [
            $export_type,
            $form_id
        ]);
    }

    protected function getFilename($ext)
    {
        return implode('', [
            implode('__', [
                'export',
                $this->getObjectDescriptor(),
                $this->getFormattedDate(),
            ]),

            '.',
            $ext
        ]);
    }

    protected function getFieldIDs($data)
    {
        return array_unique(
            array_reduce($data, function ($acc, $entry) {
                $ids = array_map(function ($field) {
                    return Utils::get($field, 'id');
                }, $entry->getPrintableFields());

                return array_merge($acc, $ids);
            }, [])
        );
    }
}
