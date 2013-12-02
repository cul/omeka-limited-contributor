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
 *
 *
 * @package Omeka\Plugins\LimitedContributor
 */

class LimitedContributorAcl {

	function defineAcl($acl){

		/////////////////////////////
		// Modify Contributor role //
		/////////////////////////////

		$acl->deny(
			array('contributor', 'researcher'),
			'Items',
			array('showNotPublic'),
			new LimitedContributor_Acl_Assert_RecordOwnership
			);
	}
}
