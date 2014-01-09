<?php

defined('_JEXEC') or die();

class MeiadminTableFile_Version extends BBDFOFTable
{

    public function __construct($table, $key, &$db)
    {
        parent::__construct('#__meiadmin_file_versions', 'meiadmin_file_version_id', $db);
    }

    public function loadById($id)
    {
        if (!$this->_tableExists) {
            $result = false;
        }

        $query = $this->_db->getQuery(true);
        $query->select($this->_tbl . '.*, u.name')
            ->from($this->_tbl)
            ->leftJoin("#__users AS u ON (created_by = u.id)")
            ->where('fk_file_id = ' . (int)$id)
            ->order('version DESC LIMIT 0,10');

        $this->_db->setQuery($query);

        if (version_compare(JVERSION, '3.0', 'ge')) {
            try {
                $rows = $this->_db->loadObjectList();
            } catch (JDatabaseException $e) {
                $this->setError($e->getMessage());
            }
        } else {
            if (!$rows = $this->_db->loadObjectList()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return $rows;
    }

}
