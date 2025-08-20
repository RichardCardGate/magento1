<?php

/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_Model_Gateway_Bancontact extends Cardgate_Cgp_Model_Gateway_Abstract
{

	protected $_code = 'cgp_bancontact';

	protected $_model = 'bancontact';

	protected $_canUseInternal = true;
}
