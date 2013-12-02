<?php
/**
 * Limited Contributor
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Limited Contributor index controller class.
 *
 * @package LimitedContributor
 */
class LimitedContributor_IndexController extends Omeka_Controller_AbstractActionController
{    
    /**
     * Initialization actions (optional)
     */
    public function init()
    {
        // Set the model class so this controller can perform some functions, 
        // such as $this->findById()
        $this->_helper->db->setDefaultModelName('LimitedContributorPage');
    }
    
    /**
     * Plugin base index page
     * @return null
     */
    public function indexAction()
    {
        // Always go to browse.
        // $this->_helper->redirector('browse');
        // 

        $form = $this->_getMainForm();
        return;
    }
    
    /**
     * Get the main Csv Import form
     * @return CsvImport_Form_Main
     */
    protected function _getMainForm()
    {
        require_once LIMITED_CONTRIBUTOR_DIR . '/forms/Main.php';
        $form = new LimitedContributorCollaborators_Form_Main();
        return $form;
    }

}
