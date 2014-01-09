<?php

defined('_JEXEC') or die();

class MeiControllerFile extends BBDFOFController {

	public function display($cachable = false, $urlparams = false) {
		$view = $this->getThisView(array('tbl' => '#__meiadmin_files'));
		parent::display($cachable, $urlparams);
	}
	
	public function onBeforeBrowse(){
        if (!parent::onBeforeBrowse()) return false;
		$productId = $this->input->get('id', 0, 'alnum');
		$files = $this->getModel();
		if ($productId) $files->set('fk_product_id', $productId);
		return true;		
	}

    public function download(){
        $fid = $this->input->get('fid', 0);
        $fileModel = $this->getThisModel();
        $fileModel->id = $fid;
        try {
            $fileModel->download();
        } catch (Exception $e) {
            $this->setRedirect('index.php?option=com_mei&view=products', $e->getMessage(), 'error');
        }
    }
	
}
