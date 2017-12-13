<?php
/**
 * Created by PhpStorm.
 * User: aahmed
 * Date: 12/7/17
 * Time: 11:24 AM
 */

class CreativeChaos_AddProduct_Model_Observer
{
    public function eventObserver(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

//        die($event->getName());
        self::addProduct();

    }

    public function addProduct()
    {
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $product = Mage::getModel('catalog/product');
        //    if(!$product->getIdBySku('testsku61')):


        try{
            $product
                ->setStoreId(1) //you can set data in store scope
                //->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
                ->setAttributeSetId(4) //ID of a attribute set named 'default'
                ->setTypeId('simple') //product type
                ->setCreatedAt(strtotime('now')) //product creation time
                //    ->setUpdatedAt(strtotime('now')) //product update time
                ->setId(1) //Set Product ID
                ->setSku('akeed_product') //SKU
                ->setName('AKEED Product') //product name
                ->setWeight(4.0000)
                ->setStatus(1) //product status (1 - enabled, 2 - disabled)
                ->setTaxClassId(4) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
                ->setManufacturer(28) //manufacturer id
                ->setColor(24)
                ->setNewsFromDate('06/26/2014') //product set as new from
                ->setNewsToDate('06/30/2014') //product set as new to
                ->setCountryOfManufacture('AF') //country of manufacture (2-letter country code)

                ->setPrice(11.22) //price in form 11.22
                ->setCost(22.33) //price in form 11.22
                ->setSpecialPrice(00.44) //special price in form 11.22
                ->setSpecialFromDate('06/1/2014') //special price from (MM-DD-YYYY)
                ->setSpecialToDate('06/30/2014') //special price to (MM-DD-YYYY)
                ->setMsrpEnabled(1) //enable MAP
                ->setMsrpDisplayActualPriceType(1) //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
                ->setMsrp(99.99) //Manufacturer's Suggested Retail Price
//            ./media/tmp/catalog/product/C
                ->setMetaTitle('test meta title 2')
                ->setMetaKeyword('test meta keyword 2')
                ->setMetaDescription('test meta description 2')

                ->setDescription('This is a long description')
                ->setShortDescription('This is a short description')

                ->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
                ->addImageToMediaGallery('/home/aahmed/Pictures/Captain America.jpg', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery

                ->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock'=>1, //manage stock
                        'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                        'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                        'is_in_stock' => 1, //Stock Availability
                        'qty' => 999 //qty
                    )
                )

                ->setCategoryIds(array(3, 10)); //assign product to categories
            $product->save();
            //endif;
        }catch(Exception $e){
            Mage::log($e->getMessage());
        }
    }

}