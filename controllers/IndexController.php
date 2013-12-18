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
	public function init() {
		// Set the model class so this controller can perform some functions,
		// such as $this->findById()
		$this->_helper->db->setDefaultModelName('LimitedContributorPage');
	}

	/**
	 * Get the main Csv Import form
	 * @return CsvImport_Form_Main
	 */
	protected function _getMainForm() {
		require_once LIMITED_CONTRIBUTOR_DIR . '/forms/Main.php';
		$form = new LimitedContributorCollaborators_Form_Main();
		return $form;
	}
	/**
	 * Configure a new import.
	 */
	public function indexAction() {
		$form = $this->_getMainForm();
		$this->view->form = $form;

		// Check if the form was submitted.
		if ($this->getRequest()->isPost()) {
			// Set the POST data to the record.
			$record->setPostData($_POST);
			// Save the record. Passing false prevents thrown exceptions.
			if ($record->save(false)) {
				$successMessage = $this->_getEditSuccessMessage($record);
				if ($successMessage) {
					$this->_helper->flashMessenger($successMessage, 'success');
				}
				$this->_redirectAfterEdit($record);
				// Flash an error if the record does not validate.
			} else {
				$this->_helper->flashMessenger($record->getErrors());
			}
		}

	}


}
