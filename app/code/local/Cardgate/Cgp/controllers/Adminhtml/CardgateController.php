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


	public function resendPaymentAction() {
        $this->loadLayout();
        $this->_title($this->__('Cardgate'))
             ->_title($this->__('Resend Payment link'));

        /**
		 * @var Mage_Sales_Model_Order $order
		 */
        $order = Mage::getModel( 'sales/order' )->load( $this->getRequest()->get( 'orderid' ) );
		$order = Mage::getModel( 'sales/order' )->load( $this->getRequest()->get( 'orderid' ) );
		if ( empty( $order ) ) {
			$this->addText( Mage::helper('cgp')->__('Error loading order #%s'), $this->getRequest()->get( 'orderid' ) );
		}
		$payment = $order->getPayment();
		if ( empty( $payment ) ) {
			$this->addText( Mage::helper('cgp')->__('Error loading payment info for order #%s'), $this->getRequest()->get( 'orderid' ) );
		}
		try {
			$title = $payment->getMethodInstance()->getTitle();
		} catch ( Exception $e ) {
			/* ignore */
		}
		if ( empty( $title ) ) {
			$title = $payment->getMethod();
		}


		/**
		 * @var Cardgate_Cgp_Model_Paymentlink $paymentlink
		 */
	    $paymentlink = Mage::getModel( 'cgp/paymentlink');
        $paymentlink->queueNewPaymentEmail($order, true, 'payment');

		Mage::getSingleton( 'core/session' )->addSuccess( Mage::helper( 'cgp' )->__( "Paymentlink mail sent using method \'%s\'", $title ) );
		$this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
	}


	public function resendCheckoutAction() {
        $this->loadLayout();
        $this->_title($this->__('Cardgate'))
             ->_title($this->__('Resend Payment link'));

        /**
		 * @var Mage_Sales_Model_Order $order
		 */
        $order = Mage::getModel( 'sales/order' )->load( $this->getRequest()->get( 'orderid' ) );

		/**
		 * @var Cardgate_Cgp_Model_Paymentlink $paymentlink
		 */
	    $paymentlink = Mage::getModel( 'cgp/paymentlink');
        $paymentlink->queueNewPaymentEmail($order, true, 'checkout');

		Mage::getSingleton( 'core/session' )->addSuccess( Mage::helper( 'cgp' )->__( "Paymentlink mail sent allowing all available paymentmethods" ) );
		$this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
	}

}