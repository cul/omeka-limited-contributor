<?php
/**
 * LimitedContributor
 *
 * @copyright Copyright 2013 Christopher Anderton
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Limited Contributor plugin.
 *
 * @package Omeka\Plugins\LimitedContributor
 */

if (!defined('LIMITED_CONTRIBUTOR_DIR')) define('LIMITED_CONTRIBUTOR_DIR', dirname(__FILE__));

// Access Control List
require_once LIMITED_CONTRIBUTOR_DIR.'/helpers/Acl.php';

class LimitedContributorPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_hooks = array(
		// 'initialize',
		'define_acl',
		// 'admin_items_show',
		// 'admin_items_browse'
		);

	// public function hookAdminItemsShow() {

	// 	Vki::vox('adminitemshow');
	// 	$itemOwner = get_view()->limitedcontributor(get_current_record('item') );
	// 	$result = ($itemOwner == current_user() ) ? "The same" : false;
	// 	// echo get_view()->limitedcontributor(get_current_record('item') );
	// 	Vki::vox($result);

	// 	return $result;
	// }

	// public function hookAdminItemsBrowse() {
	// 	// Vki::vox('Browsing Simple');
	// 	$itemOwner = get_view()->limitedcontributor(get_current_record('item') );
	// 	$result = ($itemOwner == current_user() ) ? "The same" : false;

	// 	Vki::vox($result);
	// 	return $result;
	// }

	// public function hookInitialize(){
	// 	$user = current_user();
	// }

	/**
	 * Define the Access Control List
	 * @param  array $args Array of arguments
	 * @return null
	 */
	public function hookDefineAcl($args){
		require_once LIMITED_CONTRIBUTOR_DIR.'/assertions/LimitedContributor_Acl_Assert_RecordOwnership.php';
		extract($args);

		$acl->deny(
			array('contributor', 'researcher'),
			'Items',
			null,
			new LimitedContributor_Acl_Assert_RecordOwnership
			);

		// Vki::vox($acl);
		// $limitedContributorAcl = new LimitedContributorAcl();

		// $limitedContributorAcl->defineAcl($acl);
	}

}
