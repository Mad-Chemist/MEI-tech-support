<?php

defined('_JEXEC') or die();

class MeiadminModelFiles extends BBDFOFModel
{

    public function newVersion($versionNumber, $fileSize)
    {
        $fileTable = $this->getTable($this->table);
        if (!$fileTable->meiadmin_file_id) throw new Exception(JText::_('COM_MEIADMIN_FILE_UPDATE_VERSION'));
        $fileTable->current_version = $versionNumber;
        $fileTable->file_size = $fileSize;
        return $fileTable->store();
    }

    protected function loadFormData()
    {
        if (!$this->pid && empty($this->_formData)) return array();
        if ($this->pid){
            $this->_formData['fk_product_id'] = $this->pid;
        }
        if(isset($this->_formData['access_account'])) $this->_formData['access_account'] = explode(',', $this->_formData['access_account']);
        return $this->_formData;
    }

    public function promote($versionId)
    {
        $version        = $this->_lookupVersion($versionId);
        $customVersion  = $this->_lookupCustomVersion($versionId);
        if(!$version) return false;
        $table = $this->getTable();
        $table->load($this->id);
        $table->current_version = $version;
        $table->custom_version = $customVersion;
        return $table->store();
    }

    protected function _lookupVersion($versionId)
    {
        $query = $this->_db->getQuery(true);
        $query->select('version')->from('#__meiadmin_file_versions')->where('meiadmin_file_version_id = ' . $this->_db->quote($versionId));
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    protected function _lookupCustomVersion($versionId)
    {
        $query = $this->_db->getQuery(true);
        $query->select('custom_version')->from('#__meiadmin_file_versions')->where('meiadmin_file_version_id = ' . $this->_db->quote($versionId));
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    protected function onBeforeSave(&$data, &$table)
    {
        if (!parent::onBeforeSave($data, $table)) return false;
        $form = $form = $this->getForm($data, false, 'form.form');
        $data = $form->filter($data);
        $this->_flattenCustomers($data);
        return true;
    }

    protected function _flattenCustomers(&$data)
    {
        $data['access_account'] = implode(',', $data['access_account']);
    }

    public function save($data)
    {
        $this->_addSection($data);
        return parent::save($data);
    }

    protected function _addSection(&$data)
    {
        $types = array(
            'general'           => 'documentation',
            'service'           => 'documentation',
            'integration'       => 'documentation',
            'specifications'    => 'documentation',
            'drawing'           => 'documentation',
            'application'       => 'firmware',
            'variant'           => 'firmware',
            'channel'           => 'firmware',
            'combined'          => 'firmware',
            'service-tool'      => 'tool',
            'api'               => 'tool',
            'coin'              => 'tool',
            );
        if (isset($data['type']) && array_key_exists($data['type'], $types)){
            $data['section'] = $types[$data['type']];
        }
    }
}