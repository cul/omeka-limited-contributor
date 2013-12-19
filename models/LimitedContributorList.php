<?php
/**
 * Limited Contributor
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Limited Contributor share list record class.
 *
 * @package LimitedContributor
 */
class LimitedContributorList extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
	public $owner_id;
	public $user_id;

	/**
	 * Validate the form data.
	 */
	protected function _validate(){
		return;
		if (empty($this->title)) {
			$this->addError('title', __('The page must be given a title.'));
		}

		if (255 < strlen($this->title)) {
			$this->addError('title', __('The title for your page must be 255 characters or less.'));
		}

		if (!$this->fieldIsUnique('title')) {
			$this->addError('title', __('The title is already in use by another page. Please choose another.'));
		}

		if (trim($this->slug) == '') {
			$this->addError('slug', __('The page must be given a valid slug.'));
		}

		if (preg_match('/^\/+$/', $this->slug)) {
			$this->addError('slug', __('The slug for your page must not be a forward slash.'));
		}

		if (255 < strlen($this->slug)) {
			$this->addError('slug', __('The slug for your page must be 255 characters or less.'));
		}

		if (!$this->fieldIsUnique('slug')) {
			$this->addError('slug', __('The slug is already in use by another page. Please choose another.'));
		}

		if (!is_numeric($this->order) || (!(strpos((string)$this->order, '.') === false)) || intval($this->order) < 0) {
			$this->addError('order', __('The order must be an integer greater than or equal to 0.'));
		}
	}

	/**
	 * Prepare special variables before saving the form.
	 */
	protected function beforeSave($args){
		
		return;
		
		$this->title = trim($this->title);
		// Generate the page slug.
		$this->slug = $this->_generateSlug($this->slug);
		// If the resulting slug is empty, generate it from the page title.
		if (empty($this->slug)) {
			$this->slug = $this->_generateSlug($this->title);
		}

		if ($this->order == '') {
			$this->order = 0;
		}

		if ($this->parent_id == '') {
			$this->parent_id = 0;
		}

		$this->modified_by_user_id = current_user()->id;
		$this->updated = date('Y-m-d H:i:s');
	}


	public function getResourceId(){
		return 'LimitedContributor_List';
	}

	public static function getSharedList(){
		$db = get_db();
		$user = current_user();
		$sharedWithList = $db->getTable("LimitedContributorList")->findBy(array('owner_id'=> $user->id ) );
		$sharedWithListCount = count($sharedWithList);
		$sharedWith = '';
		
		if($sharedWithListCount < 1)
			return null;
		 
		for($i=0; $i < $sharedWithListCount; $i++) {
			$userRecord = $db->getTable("user")->find($sharedWithList[$i]->user_id);
			$sharedWith .= ($sharedWithListCount > 1 && $i < $sharedWithListCount-1) ? $userRecord->username.', ' : $userRecord->username;
		}
		return $sharedWith;
	}
}
