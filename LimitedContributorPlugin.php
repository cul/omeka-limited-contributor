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
			'initialize',
// 			'define_acl',
			// 'admin_items_show',
			'admin_items_browse',
			'install',
			'uninstall'
	);

	protected $_filters = array(
			'admin_navigation_main',
			'admin_items_browse',
			//'concealDescription' => array('Display', 'Item', 'Dublin Core', 'Title'),
			// 'items_browse_params'
			'items_browse'
	);

	public function concealDescription($text, $args)
	{
		if($text)
			return 'Sorry, but you\'re not allowed.';//.str_rot13($text);

		else return $text;
	}

	public function filterAdminNavigationMain($tabs) {
		// 		Vki::vox("filterAdminNavigationMain");
		$user = current_user();
		$tabs[] = array(
				'label'   => __("Share List"),
				'uri'     => url('limited-contributor'),
				'visible' => true
		);

		return $tabs;
	}

	public function filterItemsBrowseParams($params)
	{
		// 		Vki::vox('Filter item browse params');
		//always sort by title instead of order
		$params['sort_param'] = "Dublin Core,Title";

		return $params;
	}

	public function filterItemsBrowse($params) {
		Vki::vox("Filter Items Browse");
		return "Other stuff";
	}

	public function filterAdminItemsBrowse($params) {
		Vki::vox("Filter Admin Items Browse");
		return "Other stuff";
	}

	public function hookAdminItemsBrowse($params) {

		$itemOwner = get_view()->limitedcontributor(get_current_record('item') );
		$result = ($itemOwner == current_user() ) ? "The same" : "Not the users";
		Vki::vox($result, "Hook Admin Items Browse Result: ");

		return $result;
	}

	public function hookInitialize(){

		$user = current_user();
	}

	/**
	 * Create exhibit and record tables.
	 */
	public function hookInstall()
	{

		$this->_db->query(<<<SQL
        CREATE TABLE IF NOT EXISTS
            {$this->_db->prefix}limited_contributor_lists (

            id                      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
           	owner_id                INT(10) UNSIGNED NOT NULL,
            user_id                INT(10) UNSIGNED NOT NULL,

            PRIMARY KEY             (id)

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SQL
		);

	}


	/**
	 * Drop exhibit and record tables.
	 */
	public function hookUninstall()
	{
		$this->_db->query(<<<SQL
        DROP TABLE {$this->_db->prefix}limited_contributor_lists
SQL
		);
	}

	/**
	 * Define the Access Control List
	 * @param  array $args Array of arguments
	 * @return null
	 */
	public function hookDefineAcl($args){
		require_once LIMITED_CONTRIBUTOR_DIR.'/assertions/LimitedContributor_Acl_Assert_RecordOwnership.php';
		extract($args);

		$acl->deny(
				null,
				'Items',
				array('show')
		);

		$acl->allow(
				array('contributor'),
				'Items',
				array('show'),
				new LimitedContributor_Acl_Assert_RecordOwnership()
		);
	}
}