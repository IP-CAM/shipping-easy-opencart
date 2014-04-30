Name: ShippingEasy
Type: Module

Purpose
=======
This package provides a create shippingeasy API configuration form , Create order, Call cancellation API, Shipment callback .

Installation & usage
====================

I. To create ShippingEasy Form.

  1. Simply upload the folder and files from the 'ShippingEasy_API' directory to your 
  OpenCart server's main directory. The following OpenCart files
  are replaced by the upload:

  /admin/controller/config/api.php
  /admin/controller/module/api.php

  /admin/language/english/config/api.php
  /admin/language/english/module/api.php

  /admin/model/config/api.php

  /admin/view/template/config/api.tpl

  2. Log into shop Admin Panel. Go to System > Users > User Groups and Edit each user to check appropriate checkboxes to add permissions to this extension.
  3. Go to Extensions > Modules. Click Install link next to "ShippingEasy Setting".
  5. Go to Extensions > shippingEasy API and Edit the ShippingEasy configuration form to save the shippingeasy API credential.


II. To send order cancellation request.

  1. Simply upload the folder and files from the 'ShippingEasy_cancellation' directory to your 
  OpenCart server's main directory. The following OpenCart files
  are replaced by the upload:

  /admin/controller/checkout/shipping_easy-php
  /admin/controller/sale/order.php


III. Shipment callback functioanlity.
 
  1. Simply upload the folder and files from the 'shippingEasy_endpoint' directory to your 
  OpenCart server's main directory. The following OpenCart files
  are replaced by the upload:

  /admin/controller/shipment/callback.php
  /callback.php


IV. TO create order.

  1. Simply upload the folder and files from the 'ShippingEasy_order' directory to your 
  OpenCart server's main directory. The following OpenCart files
  are replaced by the upload:

  /catalog/controller/checkout/shipping_easy-php
  /catalog/controller/checkout/success.php

Requirements
============
1. This is an extension for Opencart. Hence you need Opencart (tested on 1.5.6.1) preinstalled. Opencart is available at http://www.opencart.com/index.php?route=download/download
