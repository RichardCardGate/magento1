![CardGate](https://cdn.curopayments.net/thumb/200/logos/cardgate.png)

# CardGate Modul für Magento 1

[![Build Status](https://travis-ci.org/cardgate/magento1.svg?branch=master)](https://travis-ci.org/cardgate/magento1)

## Support

Dieses Modul ist geeignet für Magento Commerce Version **1.7 bis 1.9.4.x**

## Vorbereitung

Um dieses Modul zu verwenden sind Zugangsdate zur CardGate RESTful API notwendig.  
Gehen zu [My CardGate](https://my.cardgate.com/) und fragen Sie Ihre Zugangsdaten an, oder kontaktieren Sie Ihren Accountmanager.

## Installation

1. Downloaden Sie den aktuellsten **[Source Code (tar.gz)](https://github.com/cardgate/magento1/releases)** auf Ihrem Desktop.

2. Gehen Sie zum **Adminbereich** Ihres Webshops.
(Zum beispiel: **http://meinewebsite.com/index.php/admin**)

3. **System** wählen, **Magneto Connect**, **Connect Manager**.
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-1.png)

4. Folgen Sie unter **Direct package file upload**,  der Anleitung um die CardGate Datei hochzuladen. 
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-2.png)

5. Klicken Sie nun auf den **Proceed** Button.  
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-7.png)

6. Das Modul ist installiert.

## Configuration

1. Gehen Sie zum **Adminbereich** Ihres Webshops.

2. Klicken Sie in Ihrem **Adminbereich** auf **System** und klicken Sie danach auf **Konfiguration**.
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-8.png)

3. Klicken Sie auf **CardGate** in dem **Sales**-Bereich von dem Konfigurationsmenü.
   (Falls **CardGate** nicht sichtbar ist, loggen sich aus dem **Admin Panel** aus und wieder ein.)  
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-9.png)

4. Füllen Sie die Einstellungen ein. (Die unten angezeigten Einstellungen sind lediglich Beispiele.)  
   Aktivieren Sie Zahlungsmittel, die verwenden möchten und klicken Sie danach auf **Save Config**.
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-10.png)

5. Füllen Sie die **Site ID** und den **Hash Key** ein, Sie unter **Sites** auf [My CardGate](https://my.cardgate.com/).

6. Füllen Sie die **merchant ID** und den **API key** ein, den Sie von CardGate empfangen haben.

7. Falls Sie mehrere **Store Views** verwenden, wie z.B bei einem mehrsprachigen Webshop,  
   dann kann es sein, dass Sie nicht die Standart **Return URL** verwenden können.  
   Die **Return URL** die Sie verwenden möchten, muss dann gesondert eingefüllt werden unter [My CardGate](https://my.cardgate.com/) sowie als folgt beschrieben.   

8. Gehen Sie nun zu [**My CardGate**](https://my.cardgate.com/), klicken Sie auf **Sites** und wählen Sie die gewünschte Seite aus.  
   Füllen Sie nun bei **Technische Schnittstelle** die **Callback URL** ein.   
   Wenn der Magento Webshop in dem **Root Verzeichnis** von Ihrer Webseite steht,  
   z.B. **http://meinewebsite.com**, dann muss die folgende Callback URL verwendet werden:  
   **http://www.meinewebsite.com/cgp/standard/control/**  
   Falls der Magento Webshop **nicht im Rootverzeichnis** Ihrer Webseite steht,  
   z.B in **http://meinewebsite.com/shop/**, dann ist die Callback URL:  
   **http://meinewebsite.com/shop/cgp/standard/control/**  
   Falls Sie einen **Storeview Code** in der URL verwenden, sowie z.B bei mehrsprachigen Webseiten,  
   z.B. **http://meinewebsite.com/index.php/main_en/**,  
   dann ist die Callback URL: **http://meinewebsite.com/index.php/main_en/cgp/standard/control/**  
   In diesem Fall müssen Sie **per storeview**, eine **unique Site ID** und **Hash Key** in den Website-Einstellungen festlegen.  

9. Füllen Sie falls gewünscht eine **Return URL** und eine **Return URL failed** ein.  
   **Achtung:** Die **Return URL** und die **Return URL failed** werden automatisch durch das Magento Plugin eingestellt.  
   Diese müssen **lediglich** per Hand eingefüllt werden, wenn Sie die  **multi store view** Option van Magento verwenden.  
   Vergewissern Sie sich in diesem Fall, das die Option **"Use back-­office URLs”** auf **Ja** steht bei Magento!  
   Beispiel URL's bei mehreren **Store views**:  
   Return URL: **http://www.meinwebsite.com/index.php/main_nl/cgp/standard/success/**  
   Return URL failed: **http://www.meinwebsite.com/index.php/main_nl/cgp/standard/cancel/**   
   
10. Die Einstellungen sind nun durchgeführt.

## Anforderungen

Keine weiteren Anforderungen. 