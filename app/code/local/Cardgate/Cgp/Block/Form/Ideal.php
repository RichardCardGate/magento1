<?php

/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_Block_Form_Ideal extends Mage_Payment_Block_Form
{

	protected $_banks = array( 
			'' => 'Please select',
			'ABNANL2A' => 'ABN Amro Bank',
			'ASNBNL21' => 'ASN Bank',
			'HANDNL2A' => 'Handelsbanken',
			'BUNQNL2A' => 'bunq',
			'INGBNL2A' => 'ING',
			'KNABNL2H' => 'Knab',
			'RABONL2U' => 'Rabobank',
			'RBRBNL21' => 'RegioBank',
			'SNSBNL2A' => 'SNS Bank',
			'TRIONL2U' => 'Triodos Bank',
			'FVLBNL22' => 'Van Lanschot Bankiers'
	);

	protected function _construct ()
	{
		parent::_construct();
		$this->setTemplate( 'cardgate/cgp/form/ideal.phtml' );
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

    /**
     * Return show issuers setting as bool
     * @return bool
     */
    public function showIssuers() {
        $ideal = Mage::getSingleton( 'cgp/gateway_ideal' );
        return boolval( $ideal->getConfigData("showissuers") );
    }

	/**
	 * Returns HTML options for select field with iDEAL banks
	 *
	 * @return string
	 */
	public function getSelectField ()
	{
		$a2 = array();
		$aBanks = $this->getBankOptions();
		foreach ( $aBanks as $id => $name ) {
			$a2[$id] = Mage::helper( 'cgp' )->__( $name );
		}
		$_code = $this->getMethodCode();
		
		$form = new Varien_Data_Form();
		$form->addField( $_code . '_ideal_issuer', 'select', 
				array( 
						'name' => 'payment[cgp][ideal_issuer_id]', 
						'label' => Mage::helper( 'cgp' )->__( 'Select your bank' ), 
						'values' => $a2, 
						'value' => '',
				        'required' => true,
						'disabled' => false 
				) );
		return $form->getHtml();
	}

	/**
	 * Fetch iDEAL bank options from CardGatePlus if possible and return as
	 * array.
	 *
	 * @return array
	 */
	private function getBankOptions ()
	{
		$sBanks = Mage::app()->loadCache('cgpbankissuers');

		if (($sBanks != false) && !$this->issuerModeChanged()){
			$this->_banks = unserialize($sBanks);
		} else {
	        $ideal = Mage::getSingleton( 'cgp/gateway_ideal' );
		    $client = new Varien_Http_Client( $ideal->getGatewayUrl() . '/cache/idealDirectoryCUROPayments.dat' );
		    try{
		    	$response = $client->request();
			    if ($response->isSuccessful()) {
			    	$aBanks = unserialize( $response->getBody() );
			    	if ( is_array( $aBanks) && array_key_exists("INGBNL2A",$aBanks)) {
					    unset($aBanks[0]);
					    $sBanks = serialize($aBanks);
					    $sCurrentMode = $ideal->getConfigData("test_mode");
					    $lifeTime = 24 * 60 * 60;
					    Mage::app()->saveCache($sBanks, 'cgpbankissuers', array(Mage_Core_Model_Config::CACHE_TAG), $lifeTime);
					    Mage::app()->saveCache($sCurrentMode, 'cgpissuermode', array(Mage_Core_Model_Config::CACHE_TAG), $lifeTime);
					    $this->_banks = $aBanks;
				    }
			    }
		    }catch (Exception $e) {
		    	// use the default isssuer list
		    }
		}

		$this->_banks[''] = Mage::helper( 'cgp' )->__( '--Please select--' );
		return $this->_banks;
	}

	private function issuerModeChanged(){
		$ideal = Mage::getSingleton( 'cgp/gateway_ideal' );
		$sCurrentMode = $ideal->getConfigData("test_mode");
		$sIssuerMode = Mage::app()->loadCache('cgpissuermode');
		$newMode = ($sCurrentMode == $sIssuerMode ? false : true);
		return $newMode;
	}
}