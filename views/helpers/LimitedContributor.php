<?php

class LimitedContributor_View_Helper_LimitedContributor extends Zend_View_Helper_Abstract {

	public function limitedcontributor($items){

		if(is_array($items) ) {
			$bar = '';
			foreach($items as $item) {
				$bar .= var_export($item->getOwner(), true);
				$bar .= $this->_getBar($item);
				release_object($item);
			}

			return $bar;
		}
		else{
			// Vki::vox($items, 'Items:');

			return $this->_getBar($items);
		}
	}

	protected function _getBar(Item $item) {

		$bar = array();

		$bar['whatsit'] = 'thingy';

		return $item->getOwner();

	}
}
