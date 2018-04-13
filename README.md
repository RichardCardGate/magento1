![CardGate](https://cdn.curopayments.net/thumb/200/logos/cardgate.png)

# CardGate module for Magento 1

## Support

This module supports Magento version **1.7 to 1.9.3.6**.

## Preparation

The usage of this module requires that you have obtained CardGate RESTful API credentials.  
Please visit [My CardGate](https://my.cardgate.com/) and retrieve your credentials, or contact your accountmanager.

## Installation

1. Download the **Cardgate_Cgp.tgz** file to your desktop.

2. Go to the **Admin Panel** of your webshop.
(Example **http://mywebshop.com/index.php/admin**)

3. Select **System**, **Magento Connect**, **Connect Manager**.
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-1.png)

4. Under **Direct package file upload**, follow the instructions to upload the CardGate package.
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-2.png)

5. Now click on the <b>Proceed</b> button.  
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-7.png)

6. The module is installed.

## Configuration

1. Go to the **Admin Panel** of your webshop.

2. In the **Admin Panel** click on **System** and then click on **Configuration**.
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-8.png)

3. Click on **CardGate** in the **Sales** section of the configuration menu.
   (When **CardGate** is not yet visible, log out from the **Admin Panel** and login again.)  
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-9.png)

4. Enter your settings here. (The settings shown below are examples.)
   Activate the payment methods you wish to use and then click on **Save Config**.
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-10.png)

5. Enter the **site ID**, and the **hash key** which you can find at **Sites** on My CardGate.

6. Enter the **merchant ID** and **API key**, which has been given to you by your CardGate account manager.

7. If you want to use multiple **Store views**, for example in a multilingual webshop,   
   it is possible that you do not use the default **return URL**.  
   The **return URL** you wish to use you need to specify separately on [My CardGate](https://my.cardgate.com/)   
   as will be further explained below.  
   When using multiple **Store views** you can configure all the settings for each **Store view** separately.

8. Now set the **Callback URL** on [My CardGate](https://my.cardgate.com/). This URL is used by CardGate 
   to pass on successful transactions to your webshop.  
   Go to [My CardGate](https://my.cardgate.com/), choose **Sites** and select the appropriate site.  
   Go to **Connection to the website** and enter the **Callback URL**.  
   When the Magento webshop is located in the **root** of your website,  
   for example **http://mywebsite.com**, then the Callback URL is:  
   **http://mywebsite.com/cgp/standard/control/**  
   When the Magento webshop is **not located in the root** of your website,  
   for example here **http://mywebsite.com/shop/**, then the Callback URL is: **http://mywebsite.com/shop/cgp/standard/control/**  
   When you are using **storeview code** in the URL, like with a multilingual webshop,  
   for example **http://mywebsite.com/index.php/main_en/**,  
   then the Callback URL is: **http://mywebsite.com/index.php/main_en/cgp/standard/control/**  
   In this case you need to apply a **unique** **site ID** and **hash key** for **each storeview**.  

9. If desired, you can apply a unique **Return URL** and **Return URL failed**.  
   **Attention:** The **Return URL** and **Return URL failed**, by default,  
   are automatically filled in by the Magento module.  
   You **only** need to enter them manually when you are using the **multi store view** option of Magento.  
   In this case, make sure that the option **“Use back-­office URLs”** is set to **Yes** in Magento!  
   Example URL's for using multiple **Store views**:  
   Return URL: **http://www.mywebshop.com/index.php/main_nl/cgp/standard/success/**  
   Return URL failed: **http://www.mywebshop.com/index.php/main_nl/cgp/standard/cancel/**  
   
10. The setup is now complete.

## Requirements

No further requirements.
