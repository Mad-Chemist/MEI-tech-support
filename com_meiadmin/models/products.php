<?php

defined('_JEXEC') or die();

class MeiadminModelProducts extends BBDFOFModel
{
    protected $_buildMediaFlag = false;

    public function &getItem($id = null)
    {
        parent::getItem($id);
        $finput = new BBDFOFInput();
        if($finput->get('task') === 'add') $this->record->cat_id = $finput->get('cat_id', '1');
        return $this->record;
    }

    public function getFiles()
    {
        if (!isset($this->id) || !$this->id) return array();
        $finput = new BBDFOFInput(array('fk_product_id' => $this->id, 'option' => 'com_meiadmin', 'limitStart' => 0, 'limit' => 200, 'id' => 0));
        $filesModel = parent::getAnInstance('files', 'MeiadminModel', array('input' => $finput));
        return $this->_organizeAndSetUrl($filesModel->getItemList());
    }

    public function getForm($data = array(), $loadData = true, $source = null)
    {
        $form = parent::getForm($data, $loadData, $source);
        $product = $this->_loadProductFromInput();
        if ($this->_editingProduct($product)) $this->_setFormDirectoryWithProductSlugs($form, $product);
        return $form;
    }

    protected function _loadProductFromInput()
    {
        $finput = new BBDFOFInput();
        $product = $this->getTable();
        $product->load($finput->get('id', 0));
        return $product;
    }

    protected function _editingProduct($product)
    {
        if ($product->meiadmin_product_id != 0) return true;
        return false;
    }

    protected function _setFormDirectoryWithProductSlugs(&$form, $product)
    {
        $slugs = $this->_getSlugsAssociatedWithFile($product->meiadmin_product_id);
        $thumbnailDirectory = implode('/', $slugs);
        $form->setFieldAttribute('images', 'directory', $thumbnailDirectory);
    }

    protected function _organizeAndSetUrl($fileList)
    {
        $temp = array();
        foreach ($fileList as $listItem) {
            $listItem->url = JUri::base() . '/index.php?option=com_meiadmin&view=file&task=edit&pid=' . $this->id . '&id=' . $listItem->meiadmin_file_id . $this->_getItemId();
            $temp[$listItem->section][$listItem->type][] = $listItem;
        }
        return $temp;
    }

    protected function _getItemId()
    {
        static $Itemid = 0;
        if ($Itemid !== 0) return $Itemid;
        $jinput = JFactory::getApplication()->input;
        $Itemid = $jinput->getAlnum('Itemid', false);
        $Itemid = ($Itemid) ? '&Itemid=' . (int) $Itemid : '';
        return $Itemid;
    }

    protected function onBeforeSave(&$data, &$table)
    {
        if (!parent::onBeforeSave($data, $table)) return false;
        $this->_validateForm($data);
        $this->_setFlagMediaFolders($table->meiadmin_product_id);
        return true;
    }

    protected function onAfterSave(&$table)
    {
        $result = parent::onAfterSave($table);
        if($this->_buildMediaFlag) $this->_buildMediaFolders();
        return $result;
    }

    protected function _validateForm(&$data)
    {
        $form = $this->_loadForm($data);
        $data = $form->filter($data);
    }

    protected function _loadForm($data)
    {
        $view = $this->input->getCmd('view');
        $this->input->set('view', 'product');
        $form = $this->getForm($data, false, 'form.form');
        $this->input->set('view', $view);
        return $form;
    }

    protected function _setFlagMediaFolders($id)
    {
        if (!$id) $this->_buildMediaFlag = true;
    }

    protected function _buildMediaFolders()
    {
        $this->_loadNeededLibraries();
        $pid = $this->getTable()->meiadmin_product_id;
        $path = $this->_getRequiredPath($pid);
        $this->_checkDirectoryStructure($path);
    }

    protected function _getRequiredPath($pid)
    {
        $slugs = $this->_getSlugsAssociatedWithFile($pid);
        if (array_search(array('../','./','/'), $slugs)) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_PATH_ERROR'));
        $slugPath = implode('/', $slugs) . '/';
        $path = JPATH_BASE . '/images/' . $slugPath;
        return $path;
    }

    protected function _getSlugsAssociatedWithFile($pid)
    {
        $query = $this->_db->getQuery(true);
        $query->select("fa.slug AS 'FamilySlug', p.slug AS 'ProductSlug'")
            ->from("#__meiadmin_products AS p")
            ->innerJoin("#__meiadmin_categories AS fa ON(p.cat_id = fa.meiadmin_category_id)")
            ->where("p.meiadmin_product_id = " . $this->_db->quote($pid));
        $this->_db->setQuery($query);
        $result = $this->_db->loadAssoc();
        if (!$result || count($result) < 2) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_SLUG_ERROR'));
        return $result;
    }

    protected function _checkDirectoryStructure($path)
    {
        $result = JFolder::create($path);
        if (!$result) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_CREATE_ERROR'));
    }

    protected function _loadNeededLibraries()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
    }

    public function buildQuery($overrideLimits = false)
    {
        $query = parent::buildQuery($overrideLimits);
        $query->clear('select');
        $query->clear('from');
        $query->select('p.*, f.title AS family')
            ->from('#__meiadmin_products AS p')
            ->innerJoin('#__meiadmin_categories AS f ON(p.cat_id = f.meiadmin_category_id)');
        return $query;
    }

    public function getAll()
    {
        return array(array('meiadmin_product_id' => '1', 'title' => 'there'));
    }

    protected function onBeforeDelete(&$id, &$table)
    {
        $table->load($id);
        if ($table->cat_id != '' && $table->slug != '') $this->_loadDeletePath($table->cat_id, $table->slug);
        if ($this->_fileToDelete()) $this->_loadDatabaseEntriesToDelete($id);
        return true;
    }

    protected function onAfterDelete($id)
    {
        $this->_loadNeededLibraries();
        if (!$this->_fileToDelete()) return true;
        if (!JFolder::delete($this->_deletePath)) throw new Exception(JText::_('COM_MEIADMIN_FILE_DIRECTORY_DELETE_ERROR'));
        $this->_deleteEntries();
    }

    protected function _loadDeletePath($categoryId, $productSlug)
    {
        $category = $this->_loadCategorySlug($categoryId);
        $this->_deletePath = dirname(JPATH_BASE) . '/productdownloads/' . $category . '/' .$productSlug;
        if (!is_dir($this->_deletePath)) $this->_deletePath = null;
    }

    protected function _loadCategorySlug($cat_id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('slug')->from('#__meiadmin_categories')->where('meiadmin_category_id = '.$this->_db->quote($cat_id));
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    protected function _fileToDelete()
    {
        if (!is_null($this->_deletePath)) return true;
        return false;
    }

    protected function _loadDatabaseEntriesToDelete($id)
    {
        $files = $this->_loadFileIds($id);
        $fileIds = $this->_extractIdForKey($files, 'meiadmin_file_id');
        $versions = $this->_loadFileVersionIds($fileIds);
        $versionIds = $this->_extractIdForKey($versions, 'meiadmin_file_version_id');
        if ($fileIds && $versionIds) $this->_entriesToDelete = array('files' => $fileIds, 'versions' => $versionIds);
    }

    protected function _loadFileIds($id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('meiadmin_file_id')->from('#__meiadmin_files')->where('fk_product_id = '.$this->_db->quote($id));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    protected function _loadFileVersionIds($fileIds)
    {
        $filesString = '("'.implode('", "', $fileIds).'")';
        $query = $this->_db->getQuery(true);
        $query->select('meiadmin_file_version_id')->from('#__meiadmin_file_versions')->where('fk_file_id IN '.$filesString);
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    protected function _extractIdForKey($objectList, $key)
    {
        foreach($objectList as $object){
            $ids[] = $object->$key;
        }
        if (!isset($ids)) return false;
        return $ids;
    }

    protected function _deleteEntries()
    {
        $files = BBDFOFModel::getAnInstance('files');
        $files->setIds($this->_entriesToDelete['files']);
        $files->delete();
        $versions = BBDFOFModel::getAnInstance('file_versions');
        $versions->setIds($this->_entriesToDelete['versions']);
        $versions->delete();
    }
}