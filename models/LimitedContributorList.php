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
