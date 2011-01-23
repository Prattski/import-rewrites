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
 * Import/Export URL Rewrites Custom Adminhtml Block
 *
 * @category    Prattski
 * @package     Prattski_ImportRewrites
 */
class Prattski_ImportRewrites_Block_Importexport
    extends Mage_Adminhtml_Block_Widget
{
    /**
	 * Class Constructor
	 *
	 * @return void
	 */
    public function __construct()
    {
        parent::__construct();

        // Specify which adminhtml template file to use
        $this->setTemplate('importrewrites/index.phtml');
    }

    /**
     * Getter for import results stored in the adminhtml session by type
     * Options: 'rewriteSuccess', 'rewriteErrors'
     * 
     * @param string $type
     * @return string
     */
    public function getResults($type)
    {
        return Mage::getSingleton('adminhtml/session')->getData($type);
    }

    /**
     * Clears results stored in the adminhtml session by type
     * Options: 'rewriteSuccess', 'rewriteErrors'
     * 
     * @param string $type
     * @return void
     */
    public function clearResults($type)
    {
        Mage::getSingleton('adminhtml/session')->unsetData($type);
    }
}