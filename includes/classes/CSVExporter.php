<?php

namespace Mashvp\Forms;

use Mashvp\Forms\Submission;
use Mashvp\Forms\Exporter;
use Mashvp\Forms\Utils;

class CSVExporter extends Exporter
{
    public static function getAvailableExporterSettings()
    {
        return [
            'separator' => [
                'type'    => 'select',
                'label'   => _x('Separator', 'CSV Exporter settings', 'mashvp-forms'),
                'default' => ',',
                'values'  => [
                    ','  => _x('Comma', 'CSV Exporter settings', 'mashvp-forms'),
                    ';'  => _x('Semicolon', 'CSV Exporter settings', 'mashvp-forms'),
                    "\t" => _x('Tab', 'CSV Exporter settings', 'mashvp-forms'),
                    ' '  => _x('Space', 'CSV Exporter settings', 'mashvp-forms')
                ],
            ],

            'use_header' => [
                'type'    => 'checkbox',
                'label'   => _x('Include header', 'CSV Exporter settings', 'mashvp-forms'),
                'default' => true,
            ]
        ];
    }

    public function echoHeaders()
    {
        $filename = $this->getFilename('csv');

        header('Content-Type: text/csv; utf-8');
        header('Cache-Control: no-store, no-cache');
        header('Pragma: no-cache');
        header("Content-Disposition: attachment;filename={$filename}");
    }

    private function echoCSV($fd, $data)
    {
        fputcsv($fd, $data, $this->getSetting('separator'));
    }

    private function generateHeader($fd, $field_ids, $data)
    {
        $field_names = array_map(function ($id) use ($data) {
            foreach ($data as $entry) {
                $field = $entry->getFieldById($id);

                if ($field) {
                    return Utils::get($field, 'label');
                }

                return $id;
            }
        }, $field_ids);

        $this->echoCSV($fd, $field_names);
    }

    private function generatePrintableLine($fd, $field_ids, $entry)
    {
        $field_values = array_map(function ($id) use ($entry) {
            $field = $entry->getFieldById($id);

            if ($field) {
                return Submission::getPrintableFieldValue($field);
            }

            return '';
        }, $field_ids);

        $this->echoCSV($fd, $field_values);
    }

    public function generateFile($data)
    {
        $this->echoHeaders();

        if ($data && is_array($data) && count($data) > 0) {
            $fd = fopen('php://output', 'w');

            $field_ids = $this->getFieldIDs($data);

            if ($this->getSetting('use_header')) {
                $this->generateHeader($fd, $field_ids, $data);
            }

            foreach ($data as $entry) {
                $this->generatePrintableLine($fd, $field_ids, $entry);
            }

            fflush($fd);
            fclose($fd);
        }
    }
}
