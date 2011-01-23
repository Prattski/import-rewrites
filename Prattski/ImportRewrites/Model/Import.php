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
 * @package    Prattski_ImportRewrite
 * @copyright  Copyright (c) 2010-2011 Prattski (http://prattski.com/)
 * @license    http://store.prattski.com/LICENSE.txt
 */

/**
 * Import/Export URL Rewrites Model
 *
 * @category    Prattski
 * @package     Prattski_ImportRewrites
 */
class Prattski_ImportRewrites_Model_Import extends Mage_Core_Model_Abstract
{
    /**
     * Import URL Rewrites
     *
     * @return void
     */
    public function importRewrites($fileName)
    {
        $csvObject  = new Varien_File_Csv();
        $csvData    = $csvObject->getData($fileName);

        /** checks columns */
        $csvFields  = array(
            0   => Mage::helper('prattski_importrewrites')->__('Store View'),
            1   => Mage::helper('prattski_importrewrites')->__('ID Path'),
            2   => Mage::helper('prattski_importrewrites')->__('Request Path'),
            3   => Mage::helper('prattski_importrewrites')->__('Target Path'),
            4   => Mage::helper('prattski_importrewrites')->__('Redirect'),
            5   => Mage::helper('prattski_importrewrites')->__('Description')
        );

        if ($csvData[0] == $csvFields) {
            // Setup variables to store errors and successful count
            $errors = array();
            $success = 0;

            // Parse each line of the CSV
            foreach ($csvData as $k => $v) {

                // Skip the first row (headers)
                if ($k == 0) {
                    continue;
                }

                // End of file has more then one empty lines
                if (count($v) <= 1 && !strlen($v[0])) {
                    continue;
                }

                // Quit if CSV doesn't have the correct number of columns
                if (count($csvFields) != count($v)) {
                    Mage::getSingleton('adminhtml/session')
                        ->addError($this->__('Invalid file upload attempt'));
                }

                // Check to see if a redirect already exists by the 'id path'
                // If it exists, load model to edit.  If not, load fresh model
                $rewriteModel = Mage::getModel('core/url_rewrite')
                    ->loadByIdPath($v[1]);
                if (!$rewriteModel || $rewriteModel->getStoreId() != $v[0]) {
                    $rewriteModel = Mage::getModel('core/url_rewrite');
                }

                // Set values for the rewrite
                $rewriteModel->setStoreId($v[0])
                    ->setIdPath($v[1])
                    ->setRequestPath($v[2])
                    ->setTargetPath($v[3])
                    ->setOptions($v[4]) // The 'Redirect' column
                    ->setDescription($v[5])
                    ->setIsSystem(0);

                // Try to save the rewrite
                try {
                    // Check if the request path is valid
                    Mage::helper('core/url_rewrite')
                        ->validateRequestPath($v[2]);

                    // Attempt to save rewrite
                    $rewriteModel->save();

                    // If successful save, add 1 to successful rows count
                    $success++;
                } catch (Exception $e) {
                    // Store which line the exception happened
                    $line = $k + 1;

                    // Add the exception error to the errors array
                    $errors[] = "CSV Line ".$line.":  ".$e->getMessage();
                }
            }

            // Store the errors and success count in the adminhtml session
            $this->_setRewriteErrors($errors);
            $this->_setRewriteSuccess($success);

        } else {
            Mage::throwException(
                $this->__('Invalid file format upload attempt')
            );
        }
    }

    /**
     * Stores the errors in the adminhtml session
     *
     * @param array $errors
     * @return void
     */
    protected function _setRewriteErrors($errors)
    {
        Mage::getSingleton('adminhtml/session')
            ->setData('rewriteErrors', implode("<br />", $errors));
    }

    /**
     * Stores the number of successful rows imported in the adminhtml session
     *
     * @param int $success
     * @return void
     */
    protected function _setRewriteSuccess($success)
    {
        Mage::getSingleton('adminhtml/session')
            ->setData('rewriteSuccess', $success);
    }
}