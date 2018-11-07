<?php

/**
 * Magento CardGate payment extension
 *
 * @category Mage
 * @package Cardgate_Cgp
 */
class Cardgate_Cgp_StandardController extends Mage_Core_Controller_Front_Action {

	private $_gatewayModel;

	private $_base;

	public function _construct () {

		$data = $this->getRequest()->getPost();
		// Determine storeId
		$storeId = null;
		try {
			if ( $this->getRequest()->has('ref') ) {
				$storeId = Mage::getModel( 'sales/order' )->loadByIncrementId( $this->getRequest()->get('ref') )->getStoreId();
			}
			if ( $storeId == null && $this->getRequest()->has('extra') ) {
				$storeId = Mage::getModel( 'sales/quote' )->load( $this->getRequest()->get('extra') )->getStoreId();
			}
		} catch ( Exception $e ) { }

		$this->_base = Mage::getSingleton( 'cgp/base', array( 'store_id' => $storeId ) );

	}

	/**
	 * Verify the callback
	 *
	 * @param array $data
	 * @return boolean
	 */
	protected function validate ( $data ) {

		$hashString = ( ( $data['is_test'] || $data['testmode'] ) ? 'TEST' : '' )
			. $data['transaction_id']
			. $data['currency']
			. $data['amount']
			. $data['ref']
			. $data['status']
			. $this->_base->getConfigData( 'hash_key' );

		if ( md5( $hashString ) == $data['hash'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if within the URL is param model
	 * if not, return default gateway model
	 *
	 * @return string
	 */
	protected function getGatewayModel () {
		if ( $this->_gatewayModel ) {
			return $this->_gatewayModel;
		}

		$model = $this->getRequest()->getParam( 'model' );
		$model = preg_replace( '/[^[[:alnum:]]]+/', '', $model );

		if ( ! empty( $model ) ) {
			return 'gateway_' . $model;
		} else {
			return 'gateway_default';
		}
	}

	/**
	 * Redirect customer to the gateway using his prefered payment method
	 */
	public function redirectAction () {
		$paymentModel = 'cgp/' . $this->getGatewayModel();
		Mage::register( 'cgp_model', $paymentModel );

		$session = Mage::getSingleton( 'checkout/session' );
		$session->setCardgateQuoteId( $session->getQuoteId() );

		$this->loadLayout();
		$block = $this->getLayout()->createBlock( 'Cardgate_Cgp_Block_Redirect' );

		$this->getLayout()
			->getBlock( 'content' )
			->append( $block );
		$this->renderLayout();
	}

	/**
	 * After a failed transaction a customer will be send here
	 */
	public function cancelAction () {
		$message = $this->__( 'Your payment has failed. If you wish, you can try using a different payment method or try again.' );
		Mage::getSingleton( 'core/session' )->addError( $message );

		$session = Mage::getSingleton( 'checkout/session' );
		$quote = Mage::getModel( 'sales/quote' )->load( $session->getCardgateQuoteId() );

		if ( $quote->getId() ) {
			$quote->setIsActive( true );
			if ( $quote->getReservedOrderId() ) {
				$quote->setOrigOrderId( $quote->getReservedOrderId() );
				$quote->setReservedOrderId();
			}
			Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
			Mage::getSingleton('checkout/session')->setQuoteId( $quote->getId() );
			$quote->save();
		}

		$this->_redirect( 'checkout/cart' );
	}

	/**
	 * After a successful transaction a customer will be send here
	 */
	public function successAction () {
		$session = Mage::getSingleton( 'checkout/session' );
		$quote = Mage::getModel( 'sales/quote' )->load( $session->getCardgateQuoteId() );
		if ( $quote->getId() ) {
			$quote->setIsActive( false );
			$quote->delete();
		} else {
			/**
			 *
			 * @var Mage_Checkout_Model_Session $session
			 */
			$session = Mage::getSingleton( 'checkout/session' );
			if ($this->getRequest()->getParam('code', false) == '200') {
				$session->addSuccess( $this->__( 'Transaction successfully completed.' ) );
			}
		}

		$this->_redirect( 'checkout/onepage/success', array(
			'_secure' => true
		) );
	}

	/**
	 * Control URL called by gateway
	 */
	public function controlAction () {
		$data = $this->getRequest()->getPost();

		// Verify callback hash
		if ( ! $this->getRequest()->isPost() || ! $this->validate( $data ) ) {
			$message = 'Callback hash validation failed!';
			$this->_base->log( $message );
			echo $message;
			exit();
		}

		// Process callback
		if ( intval( $data['amount'] ) < 0 ) {
			$this->_base->setCallbackData( $data )->processRefundCallback();
		} else {
			$this->_base->setCallbackData( $data )->processCallback();
		}

		// Obtain quote and status
		$status = ( int ) $data['status'];
		$quote = Mage::getModel( 'sales/quote' )->load( $data['extra'] );

		if ( 200 <= $status && $status <= 299 ) {
			// Set Mage_Sales_Model_Quote to inactive and delete
			if ( $quote->getId() ) {
				$quote->setIsActive( false );
				$quote->delete();
			}
		} elseif ( 400 <= $status && $status <= 499 ) {
			// Refund callback - do nothing
		} else {
			// Set Mage_Sales_Model_Quote to active and save
			if ( $quote->getId() ) {
				$quote->setIsActive( true );
				if ( $quote->getReservedOrderId() ) {
					$quote->setOrigOrderId( $quote->getReservedOrderId() );
					$quote->setReservedOrderId();
				}
				$quote->save();
			}
		}

		// Display transaction_id and status
		echo $data['transaction_id'] . '.' . $data['status'];
	}

	public function testAction () {
		switch ( $this->getRequest()->getParam('action') ) {
			case "restful":
				echo '<pre>';
				if ( $this->getRequest()->getParam('hash') != md5( $this->_base->getConfigData( 'site_id' ) . $this->_base->getConfigData( 'hash_key' ) ) ) {
					die ( 'HASHKEY ERROR' );
				}

				/**
				 * @var Cardgate_Cgp_Model_Gateway_Default $gateway
				 */
				$gateway = Mage::getModel( 'cgp/gateway_default');
				$result = $gateway->doApiCall( 'billingoptions/' . $gateway->getConfigData( 'site_id' ));
				if ( $result['code'] == 200 ) {
					echo "OK.\nRESTful API call for API user '" . $gateway->getConfigData( 'api_id' ) . "' successfull.\n\n-------------\nBilling options for site " . $gateway->getConfigData( 'site_id' ) . ":\n-------------\n\n";
					foreach ($result['result']['billing_options'] as $option) {
						echo "{$option['name']}\n";
					}
				} else {
					echo "ERROR.\nRESTful API call for API user '" . $gateway->getConfigData( 'api_id' ) . "' did not return status 200.\n\n-------------\nResult:\n-------------\n\n";
					print_r( $result );
				}
				break;
			default:
				echo "NOT IMPLEMENTED";
				break;
		}
	}

	public function versionAction () {
		if ( $this->getRequest()->getParam('hash') != md5( $this->_base->getConfigData( 'site_id' ) . $this->_base->getConfigData( 'hash_key' ) ) ) {
			die ( json_encode( array ( 'error'=>true, 'message'=>'Hash error' ) ) );
		}
		/**
		 * @var Cardgate_Cgp_Model_Gateway_Default $gateway
		 */
		$gateway = Mage::getModel( 'cgp/gateway_default');
		die ( json_encode( array ( 'plugin_version'=>$gateway->getPluginVersion(), 'magento_version'=>Mage::getVersion() ) ) );
	}

	public function resumeAction () {
		/**
		 * @var Mage_Checkout_Model_Session $session
		 */
		$session = Mage::getSingleton( 'checkout/session' );

		$order_id = $this->getRequest()->getParam( 'order' );
		
		if ( !$order_id ) {
			$session->addError( $this->__( 'An error occurred' ) );
			$this->_redirect( 'checkout/cart' );
			$this->getResponse()->sendHeadersAndExit();
			exit;
		}

		/**
		 *
		 * @var Mage_Sales_Model_Order $order
		 */
		$order = Mage::getSingleton( 'sales/order' )->loadByIncrementId( $order_id );

		if ( $this->getRequest()->getParam('hash') != md5( $order->getCustomerEmail() . $this->_base->getConfigData( 'site_id' ) . $this->_base->getConfigData( 'hash_key' ) . $this->getRequest()->getParam( 'action' ) ) ) {
			$session->addError( $this->__( 'A security error occurred' ) );
		}

		if ( !$order->getPayment() ) {
			$this->resumeOrder( $order, true ); // This redirects the client.
			exit; // We won't reach this statement.
		}

		if ( $order->getPayment() && $order->getPayment()->getAmountPaid() > 0 ) {
			$session->addError( $this->__( 'This order is already paid.' ) );
			$this->_redirect( 'checkout/cart' );
			$this->getResponse()->sendHeadersAndExit();
			exit;
		}
		
		if ($order->getState() == 'canceled'){
		    $order = $this->uncancel($order);
		}

		switch ( $this->getRequest()->getParam( 'action' ) ) {
			case "payment":
				/**
				 *
				 * @var Cardgate_Cgp_Model_Gateway_Abstract $method
				 */
				$method = $order->getPayment()->getMethodInstance();
				if (!$method || substr( $method->getCode(), 0, 4 ) != 'cgp_' ) {
					$session->addWarning( $this->__( 'Payment method is not available. Please choose another method.' ) );
					$this->resumeOrder( $order, true ); // This redirects the client.
					exit; // We won't reach this statement.
				}
				$result = $method->register( $order );
				if ( isset( $result['result'] ) && isset( $result['result']['payment'] ) && isset( $result['result']['payment']['issuer_auth_url'] ) ) {
					$this->_redirectUrl( $result['result']['payment']['issuer_auth_url'] );
					$this->getResponse()->sendHeadersAndExit();
					exit; // We won't reach this statement.
				} else {
					$session->addWarning( $this->__( 'An exception occurred registering the transaction. Please try again.' ) );
					$this->resumeOrder( $order, true ); // This redirects the client.
					exit; // We won't reach this statement.
				}

				break;

			case "checkout":

				/**
				 *
				 * @var Cardgate_Cgp_Model_Gateway_Abstract $method
				 */
				$method = Mage::getModel( 'cgp/gateway_default');
				$result = $method->register( $order, true );
				if ( isset( $result['result'] ) && isset( $result['result']['payment'] ) && isset( $result['result']['payment']['issuer_auth_url'] ) ) {
					$this->_redirectUrl( $result['result']['payment']['issuer_auth_url'] );
					$this->getResponse()->sendHeadersAndExit();
					exit; // We won't reach this statement.
				} else {
					$session->addWarning( $this->__( 'An exception occurred registering the transaction. Please try again.' ) );
					$this->resumeOrder( $order, true ); // This redirects the client.
					exit; // We won't reach this statement.
				}

			break;
		}

		$this->_redirect( 'checkout/cart' );
	}

	/**
	 *
	 * @param Mage_Sales_Model_Order $order
	 * @param bool $bDoLogin
	 */
	private function resumeOrder( $order, $bDoLogin=false ) {
		/**
		 *
		 * @var Mage_Checkout_Model_Session $session
		 */
		$session = Mage::getSingleton( 'checkout/session' );
		/**
		 *
		 * @var Mage_Sales_Model_Quote $quote
		 */
		$quote = Mage::getModel( 'sales/quote' )->load( $order->getQuoteId() );
		if ( $quote ) {
			$quote->setIsActive( true );
			$quote->save();
			/**
			 *
			 * @var Mage_Checkout_Model_Session $session
			 */
			$session->replaceQuote( $quote );
			$session->getQuote()->setOrigOrderId(  $order->getIncrementId() );

			if ( $bDoLogin && $order->getCustomerId() ) {
				$customer = Mage::getModel( 'customer/customer' )->load( $order->getCustomerId(), 'entity_id' );
				/**
				 *
				 * @var Mage_Customer_Model_Session $custsession
				 */
				$custsession = Mage::getSingleton( 'customer/session' );
				$custsession->setCustomer( $customer );
			}

			$session->addSuccess( $this->__( 'Your order is resumed.' ) );
		}

		$this->_redirect( 'checkout/cart' );
		$this->getResponse()->sendHeadersAndExit();
	}
	private function uncancel($order)
	{
	    if ($order->getId()) {
	        $order->setData('state', 'pending_payment')
	        ->setData('status', 'pending')
	        ->setData('base_discount_canceled', 0)
	        ->setData('base_shipping_canceled', 0)
	        ->setData('base_subtotal_canceled', 0)
	        ->setData('base_tax_canceled', 0)
	        ->setData('base_total_canceled', 0)
	        ->setData('discount_canceled', 0)
	        ->setData('shipping_canceled', 0)
	        ->setData('subtotal_canceled', 0)
	        ->setData('tax_canceled', 0)
	        ->setData('total_canceled', 0);
	        $items          = $order->getItemsCollection();
	        $productUpdates = array();
	        foreach ($items as $item) {
	            $canceled = $item->getQtyCanceled();
	            if ($canceled > 0) {
	                $productUpdates[$item->getProductId()] = array('qty' => $canceled);
	            }
	            $item->setData('qty_canceled', 0);
	        }
	        try {
	            Mage::getSingleton('cataloginventory/stock')->registerProductsSale($productUpdates);
	            $items->save();
	            $currentState  = $order->getState();
	            $currentStatus = $order->getStatus();
	            $order->setState(
	                $currentState, $currentStatus, Mage::helper('adminhtml')->__('Order uncanceled'), false
	                )->save();
	                $order->save();
	        } catch (Exception $ex) {
	            Mage::log('Error uncancel order: ' . $ex->getMessage());
	            return false;
	        }
	        return $order;
	    }
	    return false;
	}

}
