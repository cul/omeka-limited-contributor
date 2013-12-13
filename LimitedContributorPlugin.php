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
			'define_acl',
			// 'admin_items_show',
			'admin_items_browse'
	);

	protected $_filters = array(
			'admin_navigation_main',
			'admin_items_browse',
			'concealDescription' => array('Display', 'Item', 'Dublin Core', 'Title'),
			// 'items_browse_params'
			'items_browse'
	);

	public function concealDescription($text, $args)
	{
		return str_rot13($text);
	}

	public function filterAdminNavigationMain($tabs) {
		Vki::vox("filterAdminNavigationMain");
		$user = current_user();
		$tabs[] = array(
				'label'   => __("Hame'e Malihini"),
				'uri'     => url('/users/edit/'.$user->id),
				'visible' => true
		);

		return $tabs;
	}

	public function filterItemsBrowseParams($params)
	{
		Vki::vox('Filter item browse params');
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
		$db = $this->_helper->db->getDb();
		$elementId = 30;
		return var_dump( $db->getTable('Element')->find($elementId) );
		
// 		add_plugin_hook('item_browse_sql', 'myplugin_item_browse_sql');

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
				null,
				'Items',
				array('show'),
				new LimitedContributor_Acl_Assert_RecordOwnership()
		);
	}
}

/**
 * Experimental Section
 */

function myplugin_item_browse_sql()
{
	$sortDir = 'ASC';
	// Get the db object
	$db = get_db();
	
	// Get the request to see if "starts_with" is a parameter
	if ($request = Zend_Controller_Front::getInstance()->getRequest()) {
		$startsWithString = $request->get('starts_with');
		if(!empty($startsWithString)) {
			$startsWithData = explode(',', $startsWithString);
		}
	}
	
	// What's the deal with no $ before the var?
	if(!startsWithData) {
		$startsWithData = isset($params['starts_with']) ? explode(',', $params['starts_with']) : false;
	}
	if($startsWithData) {
		//ItemTable builds in a order by id, which we don't want
		$select->reset('order');

		//data like 'Element Set', 'Element', 'Character'
		if(count($startsWithData) == 3) {
			$startsWith = $startsWithData[2];
			$element = $db->getTable('Element')->findByElementSetNameAndElementName($startsWithData[0], $startsWithData[1]);
			return Vki::vox($element);
			if ($element) {
				$recordTypeId = $db->getTable('RecordType')->findIdFromName('Item');
				$select->joinLeft(array('et_sort' => $db->ElementText),
						"et_sort.record_id = i.id AND et_sort.record_type_id = {$recordTypeId} AND et_sort.element_id = {$element->id}",
						array())
						->where("et_sort.text REGEXP '^$startsWith'")
						->group('i.id')
						->order("et_sort.text $sortDir");
			}
		} else {
			throw new Exception("Starts With data must be like 'Element Set', 'Element', 'Character' ");
		}
	}
}
