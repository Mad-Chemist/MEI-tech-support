<?php

defined('_JEXEC') or die();


class MeiModelFiles extends BBDFOFModel
{

    protected $_userAccessRecord = null;

    public function &getItemList($overrideLimits = false, $group = '')
    {
        $this->resetSavedState();
        $list = parent::getItemList($overrideLimits, $group);
        $list = $this->_organizeList($list);
        return $list;
    }

    protected function _organizeList($list)
    {
        $list_temp = array();
        foreach ($list as $list_item) {
            $list_item->url = 'index.php?option=com_mei&view=file&task=download&id=' . $list_item->meiadmin_file_id;
            $list_temp[$list_item->section][$list_item->type][] = $list_item;
        }
        return $list_temp;
    }

    public function buildQuery($overrideLimits = false)
    {
        $query = parent::buildQuery($overrideLimits);
        $this->_addAccessWhere($query);
        return $query;
    }

    protected function _addAccessWhere(&$query){
        $customerModel = parent::getAnInstance('customers', 'MeiadminModel');
        if ($customerModel->hasSpecialAccess()) return true;
        $this->_userAccessRecord = $customerModel->getAccessRecord();
        $where = $this->_buildAccessWhere();
        $query->where($where);
    }

    protected function _buildAccessWhere()
    {
        $fields = $this->_userAccessRecord->getTableFields();
        $where = $this->_buildAccessConditions($fields, '_conditionDefault');
        $whereOr = $this->_buildAccessConditions($fields, '_conditionMatch');
        $where = ($whereOr) ? $where . ' OR ' . $whereOr : $where;
        return '(' . $where . ')';
    }

    protected function _buildAccessConditions($fields, $conditionMethod)
    {
        $where = '(';
        foreach ($fields as $field) {
            $fieldTitle = $field->Field;
            if (strpos($fieldTitle, 'access') !== 0) continue;
            $this->$conditionMethod($fieldTitle, $where);
        }
        $where = ($where === '(') ? false : $where . ')';
        return $where;
    }

    protected function _conditionDefault($fieldTitle, &$where)
    {
        $this->_addConditionClause("AND", $where);
        $where .= $this->_db->quoteName($fieldTitle) . " = ''";
    }

    protected function _conditionMatch($fieldTitle, &$where)
    {
        if (!$this->_userAccessRecord->$fieldTitle) return;
        $this->_addConditionClause("OR", $where);
        $where .= $this->_db->quoteName($fieldTitle) . ' = ' . $this->_db->quote($this->_userAccessRecord->$fieldTitle);
    }

    protected function _addConditionClause($type, &$where)
    {
        if ($where !== '(') $where .= " {$type} ";
    }

    public function download()
    {
        $filePath = $this->_getFileWithAccess();
        if (!$filePath) throw new Exception(JText::_('COM_MEI_FILE_DOWNLOAD_NOT_FOUND'));
        $this->_prepareDownloadEnvironment();
        $this->_initiateDownload($filePath);
    }

    protected function _getFileWithAccess()
    {
        $query = $this->_db->getQuery(true);
        $query->select('v.path')
            ->from('#__meiadmin_file_versions AS v')
            ->innerJoin('#__meiadmin_files AS fi ON(v.fk_file_id = fi.meiadmin_file_id)')
            ->where('fi.meiadmin_file_id = ' . $this->_db->quote($this->id))
            ->where('fi.current_version = v.version');
        $this->_addAccessWhere($query);
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    // Stole from Akeeba Backup, thanks Nicholas
    protected function _prepareDownloadEnvironment()
    {
        // For a certain unmentionable browser -- Thank you, Nooku, for the tip
        if (function_exists('ini_get') && function_exists('ini_set')) {
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
        }

// Remove php's time limit -- Thank you, Nooku, for the tip
        if (function_exists('ini_get') && function_exists('set_time_limit')) {
            if (!ini_get('safe_mode')) {
                @set_time_limit(0);
            }
        }
    }

    protected function _initiateDownload($filename)
    {

        $basename = @basename($filename);
        $filesize = @filesize($filename);
        $extension = strtolower(str_replace(".", "", strrchr($filename, ".")));

        while (@ob_end_clean()) ;
        @clearstatcache();
// Send MIME headers
        header('MIME-Version: 1.0');
        header('Content-Disposition: attachment; filename="' . $basename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        switch ($extension) {
            case 'zip':
                // ZIP MIME type
                header('Content-Type: application/zip');
                break;

            default:
                // Generic binary data MIME type
                header('Content-Type: application/octet-stream');
                break;
        }
// Notify of filesize, if this info is available
        if ($filesize > 0) header('Content-Length: ' . @filesize($filename));
// Disable caching
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        header('Pragma: no-cache');
        flush();
        if ($filesize > 0) {
            // If the filesize is reported, use 1M chunks for echoing the data to the browser
            $blocksize = 1048756; //1M chunks
            $handle = @fopen($filename, "r");
            // Now we need to loop through the file and echo out chunks of file data
            if ($handle !== false) while (!@feof($handle)) {
                echo @fread($handle, $blocksize);
                @ob_flush();
                flush();
            }
            if ($handle !== false) @fclose($handle);
        } else {
            // If the filesize is not reported, hope that readfile works
            @readfile($filename);
        }
        exit(0);
    }

}
