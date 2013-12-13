<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=76; */

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

require_once('/Users/pololu/Sites/Vki/Vki.php');

class LimitedContributor_Acl_Assert_RecordOwnership implements Zend_Acl_Assert_Interface
{
	public function assert(
			Zend_Acl $acl,
			Zend_Acl_Role_Interface $role = null,
			Zend_Acl_Resource_Interface $resource = null,
			$privilege = null)
	{

		if ($role instanceof User && $resource instanceof Item) {
			//             Vki::vox(get_class_methods($resource) );
			if ($resource->getOwner() == $role->id)
				return true;
			else
				return false;
		}
		else{
			//             Vki::vox(get_class($resource), "I am not an Item\n");
			//             Vki::vox(get_class(), 'This class: ');
			return false;
		}
	}
}
