<?php
/**
 * Import URL Rewrites
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA that can
 * be found on the web at the following URL:
 * http://store.prattski.com/LICENSE.txt
 *
 * @category   Prattski
 * @package    Prattski_ImportRewrites
 * @copyright  Copyright (c) 2010-2011 Prattski (http://prattski.com/)
 * @license    http://store.prattski.com/LICENSE.txt
 */

/**
 * Import/Export URL Rewrites Custom Controller
 *
 * @category    Prattski
 * @package     Prattski_ImportRewrites
 */
class Prattski_ImportRewrites_ImportexportController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Index Action (Loads layout for view)
     *
     * @return void
	 */
	public function indexAction()
    {
    	$this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('prattski_importrewrites/importexport')
        );
        $this->_setActiveMenu('catalog');
        $this->renderLayout();
    }

    /**
     * Export Rewrites grid to CSV format
     *
     * @return void
     */
    public function exportCsvAction()
    {
        $fileName   = 'url-rewrites.csv';
        $content    = $this->getLayout()->createBlock('prattski_importrewrites/adminhtml_urlrewrite_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Import URL Rewrites Action
     *
     * @return void
     */
    public function importRewritesAction()
    {
        if ($this->getRequest()->isPost() && !empty($_FILES['import_rewrites_file']['tmp_name'])) {
            try {
                $fileName = $_FILES['import_rewrites_file']['tmp_name'];
                Mage::log(print_r($fileName, true));
                Mage::getModel('prattski_importrewrites/import')->importRewrites($fileName);
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The URL Rewrites have been imported.'));
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid file upload attempt'));
            }
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid file upload attempt'));
        }
        $this->_redirect('*/*/index');
    }
}