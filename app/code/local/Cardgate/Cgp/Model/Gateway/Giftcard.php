<?php

/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_Model_Gateway_Giftcard extends Cardgate_Cgp_Model_Gateway_Abstract
{

	protected $_code = 'cgp_giftcard';

	protected $_model = 'giftcard';

	protected $_canUseInternal = true;
}
