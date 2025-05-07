<?php

/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_Block_Form_Ideal extends Mage_Payment_Block_Form
{
	protected function _construct ()
	{
		parent::_construct();
	}

	/**
	 * Return information payment object
	 *
	 * @return Mage_Payment_Model_Info as bool
	 */
	public function getInfoInstance ()
	{
		return $this->getMethod()->getInfoInstance();
	}
}