<?php

// Protect from unauthorized access
defined('_JEXEC') or die;

if (!class_exists('JFormFieldList')) {
    require_once 'list.php';
}


class FOFFormFieldMultiSelectList extends FOFFormFieldList implements FOFFormField
{


    protected function getOptions()
    {
        $options = '<option></option>';
        // Do we have a class and method source for our options?
        $source_file = empty($this->element['source_file']) ? '' : (string)$this->element['source_file'];
        $source_class = empty($this->element['source_class']) ? '' : (string)$this->element['source_class'];
        $source_method = empty($this->element['source_method']) ? '' : (string)$this->element['source_method'];
        $source_key = empty($this->element['source_key']) ? '*' : (string)$this->element['source_key'];
        $source_value = empty($this->element['source_value']) ? '*' : (string)$this->element['source_value'];
        $source_translate = empty($this->element['source_translate']) ? 'true' : (string)$this->element['source_translate'];
        $source_translate = in_array(strtolower($source_translate), array('true', 'yes', '1', 'on')) ? true : false;

        if ($source_class && $source_method) {
            // Maybe we have to load a file?
            if (!empty($source_file)) {
                $source_file = FOFTemplateUtils::parsePath($source_file, true);

                JLoader::import('joomla.filesystem.file');

                if (JFile::exists($source_file)) {
                    include_once $source_file;
                }
            }

            if (class_exists($source_class, true)) {

                if (in_array($source_method, get_class_methods($source_class))) {

                    $source_data = $source_class::$source_method();

                    $options = JHtml::_('select.options', $source_data, $source_key, $source_value, $source_class::selected(), $source_translate);

                }
            }
        }

        return $options;
    }

    protected function getInput()
    {
        return '<select' . ($this->id !== '' ? ' id="' . $this->id . '"' : '') . ' name="' . $this->name . '" multiple="multiple">' . PHP_EOL
        . $this->getOptions() . '</select>' . PHP_EOL;

    }
}