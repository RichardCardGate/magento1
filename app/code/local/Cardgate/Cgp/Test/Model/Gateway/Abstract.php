<?php
/**
 * This is a unit test for the CardGate payment module for Magento 1.9.
 * It uses the EcomDev_PHPUnit framework.
 *
 * To run this test, you need to have EcomDev_PHPUnit installed and configured in your Magento 1.9 environment.
 */

class Cardgate_Cgp_Test_Model_Gateway_Abstract extends EcomDev_PHPUnit_Test_Case
{

    /**
     * @var Cardgate_Cgp_Model_Gateway_Abstract
     */
    protected $_model;

    /**
     * Set up the test environment
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_model = Mage::getModel('cgp/gateway_abstract');
    }

    /**
     * Test the getPaymentMethod method.
     *
     * This test verifies that the getPaymentMethod method returns the correct payment method model
     * based on the provided payment method code.
     *
     * @test
     * @loadFixture
     * @dataProvider dataProvider
     */
    public function testGetPaymentMethod($paymentMethodCode, $expectedClass)
    {
        // Mock the Mage::getModel() method to return a specific class
        $mock = $this->getModelMock('cgp/gateway_' . $paymentMethodCode, array('getCode'));
        $mock->expects($this->any())
             ->method('getCode')
             ->will($this->returnValue($paymentMethodCode));

        $this->replaceByMock('model', 'cgp/gateway_' . $paymentMethodCode, $mock);

        // Call the method to be tested
        $paymentMethod = $this->_model->getPaymentMethod($paymentMethodCode);

        // Assert that the returned object is of the expected class
        $this->assertInstanceOf($expectedClass, $paymentMethod);
    }

    /**
     * Data provider for the testGetPaymentMethod test.
     *
     * @return array
     */
    public static function dataProvider()
    {
        return array(
            array('ideal', 'Cardgate_Cgp_Model_Gateway_Ideal'),
            array('bancontact', 'Cardgate_Cgp_Model_Gateway_Bancontact'),
            array('sofortbanking', 'Cardgate_Cgp_Model_Gateway_Sofortbanking'),
            // Add more payment methods here as needed
        );
    }

    /**
     * Tear down the test environment
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->_model = null;
    }
}
