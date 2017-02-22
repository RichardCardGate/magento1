<?php

/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_Adminhtml_CardgateController extends Mage_Adminhtml_Controller_Action {


	public function indexAction() {
        // Load the layout handle <adminhtml_example_index>
        $this->loadLayout();

        // Sets the window title to "Example / Knectar / Magento Admin"
        $this->_title($this->__('Cardgate'));

        $this->renderLayout();
	}
	public function resendAction() {
        // Load the layout handle <adminhtml_example_index>
        $this->loadLayout();

        // Sets the window title to "Example / Knectar / Magento Admin"
        $this->_title($this->__('Cardgate'))
             ->_title($this->__('Resend payment'));

		$block = $this->getLayout()->createBlock( 'Cardgate_Cgp_Block_Adminhtml_Paymentlink_Resend' );
		$this->getLayout()
			->getBlock( 'content' )
			->append( $block );
		$this->renderLayout();
	}



	public function resendCheckoutAction() {
		// Load the layout handle <adminhtml_example_index>
        $this->loadLayout();

        // Sets the window title to "Example / Knectar / Magento Admin"
        $this->_title($this->__('Cardgate'))
             ->_title($this->__('Resend Checkout link'));

        /**
		 * @var Mage_Sales_Model_Order $order
		 */
        $order = Mage::getModel( 'sales/order' )->load( $this->getRequest()->get( 'orderid' ) );

		/**
		 * @var Cardgate_Cgp_Model_Paymentlink $paymentlink
		 */
	    $paymentlink = Mage::getModel( 'cgp/paymentlink');
        $paymentlink->queueNewPaymentEmail($order, true);

		$block = $this->getLayout()->createBlock( 'Cardgate_Cgp_Block_Adminhtml_Paymentlink_Resend' );
		$this->getLayout()
			->getBlock( 'content' )
			->append( $block );
		$this->renderLayout();
	}

}