<?php
die('not used '.__FILE__);
/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_Block_Adminhtml_Sales_Order_Payment extends Mage_Core_Block_Text
{
	protected function _construct() {
		//var_dump( $this->getParentBlock() );die();
		$this->setText( '<a href="poekoe.nl">Klik hier ofzooo</a>' );
	}

	protected function _toHtml() {
		$la = parent::_toHtml();
		return $la;
	}

}