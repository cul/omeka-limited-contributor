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

		$formOptions = array('type' => 'limited_contributor_settings', 'hasPublicPage' => true);
		$form = new LimitedContributorCollaborators_Form_Main($formOptions);

		return $form;
	}
	/**
	 * Configure a new import.
	 */
	public function indexAction() {
		$form = $this->_getMainForm();
		$this->view->form = $form;

		// Clear the list of users


		// Check if the form was submitted.
		if ($this->getRequest()->isPost()) {

			// Clear previous users in list
			$db = get_db();
			$user = current_user();
			$sharedWithList = $db->getTable("LimitedContributorList")->findBy(array('owner_id'=> $user->id ) );
				
			foreach($sharedWithList as $record)
				$record->delete();
				
			// Build new list to save
			$sharedWith = preg_replace('/\s+/', '', $_POST['lcsharewith']);

			if($sharedWith == '')
				return;
				
			$users = explode(',', $sharedWith);
				

			//         	$sharedWith .= $user;
				
			// Create a record for each user in the CSV input
			foreach($users as $user){
				// Create a lclist and find the requested user
				$list = new LimitedContributorList;
				$isUser = $list->getTable("user")->findBy(array('username'=>$user), 1);

				// If the user given is actually a user add that to the list
				if($isUser[0])
				{
					$list->owner_id = current_user()->id;
					$list->user_id = $isUser[0]->id;
						
					$list->save();
				}

			}
			return;
		}

	}


}
