<?php

defined('_JEXEC') or die();

class MeiadminModelFile_Versions extends BBDFOFModel
{

    public $version = 0;

	public function getItemsListById ($id)
    {
		return $this->getTable($this->table)->loadById($id);
	}

    public function upload($fileArr)
    {
        if($this->_nothingUploaded($fileArr)) return true;
        if($fileArr['error'] !== 0) throw new Exception(JText::_('COM_MEIADMIN_FILE_UPLOAD_ERROR'));
        $this->_loadNeededLibraries();
        $path = $this->_getRequiredPath();
        $this->_checkDirectoryStructure($path);
        $fileName = $this->_getFileName($fileArr['name']);
        if(JFile::upload($fileArr['tmp_name'], $path . $fileName)){
            return $this->save(array(
                'fk_file_id' => $this->fk_file_id,
                'version' => $this->version,
                'filename' => $fileName,
                'path' => $path . $fileName));
        }
        throw new Exception(JText::_('COM_MEIADMIN_FILE_UPLOAD_ERROR'));
    }

    protected function _nothingUploaded($fileArr)
    {
        return (empty($fileArr) || $fileArr['error'] === 4);
    }

    protected function _loadNeededLibraries()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
    }

    protected function _getRequiredPath()
    {
        $slugs = $this->_getSlugsAssociatedWithFile();
        if(array_search(array('../','./','/'), $slugs)) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_PATH_ERROR'));
        $slugPath = implode('/', $slugs) . '/';
        $path = JPATH_BASE . '/../productdownloads/' . $slugPath;
        return $path;
    }

    protected function _getSlugsAssociatedWithFile()
    {
        $query = $this->_db->getQuery(true);
        $query->select("p.slug AS 'ProductSlug', fi.slug AS 'FileSlug'")
            ->from("#__meiadmin_files AS fi")
            ->innerJoin("#__meiadmin_products AS p ON (fi.fk_product_id = p.meiadmin_product_id)")
            ->where("fi.meiadmin_file_id = " . $this->_db->quote($this->fk_file_id));
        $this->_db->setQuery($query);
        $result = $this->_db->loadAssoc();
        if(!$result || count($result) < 2) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_SLUG_ERROR'));
        return $result;
    }

    protected function _checkDirectoryStructure($path)
    {
        $result = JFolder::create($path, 0760);
        if(!$result) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_CREATE_ERROR'));
    }

    protected function _getFileName($tempName)
    {
        $fileMeta = $this->_getNextFileMetadata();
        $this->_loadNextVersion($fileMeta->version);
        return $this->_generateNewFileName($tempName, $fileMeta->slug);
    }

    protected function _generateNewFileName($uploaded, $slug)
    {
        $base = JFile::makeSafe($slug) . '-' . $this->version;
        return (JFile::getExt($uploaded)) ? JFile::makeSafe($base . '.' . JFile::getExt($uploaded)) : $base;
    }

    protected function _getNextFileMetadata()
    {
        $query = $this->_db->getQuery(true);
        $query->select('fi.slug, MAX(v.version) As version')
            ->from('#__meiadmin_files AS fi')
            ->leftJoin('#__meiadmin_file_versions AS v ON(fi.meiadmin_file_id = v.fk_file_id)')
            ->where('fi.meiadmin_file_id = ' . $this->_db->quote($this->fk_file_id));
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    protected function _loadNextVersion($versionNumber)
    {
        $this->version = $this->_generateVersion($versionNumber);
    }

    protected function _generateVersion($versionNumber)
    {
        $initalVersion = $this->_getInitialVersion();
        if(!$versionNumber) return $initalVersion;
        if(floatval($initalVersion) > floatval($versionNumber)) return $initalVersion;
        $pieces = explode('.', $versionNumber);
        if($pieces[1] === '9') return ((int) $pieces[0] + 1) . '.0';
        return $pieces[0] . '.' . ((int) $pieces[1] + 1);
    }

    protected function _getInitialVersion()
    {
        if($this->initialVersion) return $this->initialVersion;
        return '1.0';
    }
}