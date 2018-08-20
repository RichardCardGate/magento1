![CardGate](https://cdn.curopayments.net/thumb/200/logos/cardgate.png)

# CardGate module voor Magento 1

## Support

Deze module is geschikt voor Magento Commerce versie **1.7 tot 1.9.3.6** .

## Voorbereiding

Voor het gebruik van deze module zijn CardGate RESTful gegevens nodig.
Bezoek hiervoor [Mijn CardGate](https://my.cardgate.com/) en haal daar je 
gegevens op, of neem contact op met je accountmanager.

## Installatie

1. Download het **[Source Code (tar.gz)](https://github.com/cardgate/magento1/releases)** bestand en plaats het op je desktop.

2. Ga naar de **Admin Panel** van je webshop.  
   (Bijvoorbeeld **http://mijnwebshop.com/index.php/admin**)

3. Kies **System**, **Magento Connect**, **Connect Manager**.
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-1.png)

4. Volg de instructies bij **Direct package upload** en upload het CardGate bestand.  
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-2.png)

5. Klik nu op de **Proceed** knop.  
![CardGate](https://cardgate.com/wp-content/uploads/magento-install-7.png)

6. De module is geïnstalleerd.

## Configuratie

1. Ga naar de **Admin Panel** van je webshop.  

2. Klik in de **Admin Panel** op **System** en klik daarna op **Configuration**.  
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-8.png)

3. Klik op **CardGate** in het **Sales** gedeelte van het configuratie menu.  
   (Indien **CardGate** niet zichtbaar is, log dan uit de **Admin Panel** en log daarna weer in.)  
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-9.png)

4. Vul hier je instellingen in. (De getoonde instellingen hieronder zijn slechts voorbeelden.)  
   Activeer tevens de betaalmethoden die je wenst te gaan gebruiken en klik daarna op **Save Config**.  
   ![CardGate](https://cardgate.com/wp-content/uploads/magento-install-10.png)

5. De **site ID** en de **hash key** kun je vinden bij **Sites** op [Mijn CardGate](https://my.cardgate.com/).  

6. Vul de  **merchant ID** en **API key** in, die je gekregen hebt van je CardGate account manager.

7. Indien je meerdere **Store views** gebruikt, bijvoorbeeld bij een meertalige webshop,  
   dan kan het zijn dat je niet de standaard **return URL** gebruikt.  
   De **return URL** die je wenst te gebruiken, dien je dan apart op te geven in [Mijn CardGate](https://my.cardgate.com/)  
   zoals verderop wordt uitgelegd.  
   Bij het gebruik van meerdere **Store views** heb je de mogelijkheid om per **Store view** alles apart in te stellen.  

8. Stel nu de **Callback URL** in op [Mijn CardGate](https://my.cardgate.com/). Deze URL gebruikt CardGate  
   om een succesvolle transactie door te geven aan je webshop.  
   Ga naar [Mijn CardGate](https://my.cardgate.com/), kies **Sites** en selecteer de juiste site.  
   Vul nu bij **Technische Koppeling** de **Callback URL** in.  
   Wanneer de Magento webshop in de **root** van je website staat,  
   bijvoorbeeld **http://mijnwebsite.com**,  
   dan is de Callback URL: **http://mijnwebsite.com/cgp/standard/control/**   
   Wanneer je Magento webshop **niet in de root** van je website staat,  
   bijvoorbeeld in **http://mijnwebsite.com/shop/**,   
   dan is de Callback URL: **http://mijnwebsite.com/shop/cgp/standard/control/**  
   Wanneer je een **storeview code** in de URL gebruikt, zoals bij meertalige websites,  
   bijvoorbeeld **http://mijnwebsite.com/index.php/main_en/**,   
   dan is de Callback URL: **http://mijnwebsite.com/index.php/main_en/cgp/standard/control/**  
   In dit geval dien je **per storeview**, een **unieke** **site ID** en **hash key** in te vullen bij je webshop instellingen.  

9. Vul ook, indien gewenst, een unieke **Return URL** en **Return URL failed** in.   
   **Let op:** De **Return URL** en **Return URL failed** worden standaard automatisch ingevuld door de Magento module.  
   Deze hoeven **alleen** handmatig te worden opgegeven wanneer je de **multi store view** optie van Magento gebruikt.  
   Zorg er in dat geval dan voor dat de optie **“Use back-­office URLs”** op **Yes** is ingesteld in Magento!  
   Voorbeeld URL's bij meerdere **Store views**:  
   Return URL: **http://www.mijnwebshop.com/index.php/main_nl/cgp/standard/success/**  
   Return URL failed: **http://www.mijnwebshop.com/index.php/main_nl/cgp/standard/cancel/**  
   
10. De instellingen zijn nu voltooid.  

## Vereisten

Geen verdere vereisten.
