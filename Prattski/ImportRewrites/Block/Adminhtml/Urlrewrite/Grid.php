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
 * Import/Export URL Rewrites Grid
 * Used for setting up export csv format
 *
 * @category    Prattski
 * @package     Prattski_ImportRewrites
 */
class Prattski_ImportRewrites_Block_Adminhtml_Urlrewrite_Grid extends Mage_Adminhtml_Block_Urlrewrite_Grid
{

    /**
     * Prepare collection for use
     *
     * @return object Prattski_ImportRewrites_Block_Adminhtml_Urlrewrite_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('core/url_rewrite_collection');

        // Get only 'Custom' rewrites.  Avoid exporting 'System' rewrites
        $collection->addFieldToFilter('is_system', array('eq' => 0));

        $this->setCollection($collection);
        return $this;
    }

    /**
     * Setup specific columns for csv export
     *
     */
    protected function _prepareColumns()
    {
        // Store ID
        $this->addColumn('store_id', array(
            'header' => $this->__('Store View'),
            'width' => '200px',
            'index' => 'store_id',
            'store_view' => true,
        ));

        // This column only needed if I decide to allow the import/export
        // of 'System' rewrites as well.
//        $this->addColumn('is_system', array(
//            'header'    =>$this->__('Type'),
//            'width'     => '50px',
//            'index'     => 'is_system',
//            'type'      => 'options',
//            'options'   => array(
//                1 => $this->__('System'),
//                0 => $this->__('Custom')
//            ),
//        ));

        // ID Path
        $this->addColumn('id_path', array(
            'header' => $this->__('ID Path'),
            'width' => '50px',
            'index' => 'id_path'
        ));

        // Request Path
        $this->addColumn('request_path', array(
            'header' => $this->__('Request Path'),
            'width' => '50px',
            'index' => 'request_path'
        ));

        // Target Path
        $this->addColumn('target_path', array(
            'header' => $this->__('Target Path'),
            'width' => '50px',
            'index' => 'target_path'
        ));

        // Redirect (Options)
        $this->addColumn('options', array(
            'header' => $this->__('Redirect'),
            'width' => '50px',
            'index' => 'options'
        ));

        // Description
        $this->addColumn('description', array(
            'header' => $this->__('Description'),
            'width' => '50px',
            'index' => 'description'
        ));

        // Add CSV export type
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        return $this;
    }
}