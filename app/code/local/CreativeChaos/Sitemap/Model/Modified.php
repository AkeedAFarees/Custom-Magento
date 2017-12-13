<?php
/**
 * Created by PhpStorm.
 * User: aahmed
 * Date: 12/5/17
 * Time: 4:51 PM
 *
 * Written for ProcurePod by Akeed A Farees
 */

class Csquareonline_Sitemap_Model_Sitemap extends Mage_Sitemap_Model_Sitemap
{
    const ITEM_SPLIT = 20000;
    protected $_io;
    protected $_subfiles = array();

    public function generateXml()
    {
        $split = self::ITEM_SPLIT;

        $this->fileCreate();

        $storeId = $this->getStoreId();
        $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        /**
         * Generate categories sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/category/changefreq');
        $priority   = (string)Mage::getStoreConfig('sitemap/category/priority');
        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);

        /**
         * Delete old category files
         */
        try {
            foreach(glob($this->getPath() . substr($this->getSitemapFilename(), 0, strpos($this->getSitemapFilename(), '.xml')) . '_categories_*.xml') as $f) {
                unlink($f);
            }
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                'Unable to delete old categories sitemaps' . $e->getMessage()
            );
        }

        /**
         * Write Categories to multiple sitemap pages
         */
        $FileCounter = ceil(count($collection)/$split);
        $counter = 1;

        while( $counter <= $FileCounter ) {
            $name = '_categories_' . $counter . '.xml';
            $this->subFileCreate($name);
            $subCollection = array_slice($collection, ($counter-1) * $split, $split);

            foreach ($subCollection as $item) {
                $xml = sprintf(
                    '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $changefreq,
                    $priority
                );
                $this->sitemapSubFileAddLine($xml, $name);
            }
            $this->subFileClose($name);
            /**
             * Adding link of the sitemap to the Sitemap Index file
             */
            $xml = sprintf('<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>',
                htmlspecialchars( $this->getSubFileUrl($name)), $date);
            $this->sitemapFileAddLine($xml);
            $counter++;
        }

        unset($collection);

        /**
         * Generate products sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/product/changefreq');
        $priority   = (string)Mage::getStoreConfig('sitemap/product/priority');
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);

        /**
         * Delete old product files
         */
        try {
            foreach(glob($this->getPath() . substr($this->getSitemapFilename(), 0, strpos($this->getSitemapFilename(), '.xml')) . '_products_*.xml') as $f) {
                unlink($f);
            }
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                'Unable to delete old products sitemaps' . $e->getMessage()
            );
        }

        /**
         * Write Products to multiple sitemap pages
         */
        $FileCounter = ceil(count($collection)/$split);
        $counter = 1;

        while( $counter <= $FileCounter) {
            $name = '_products_' . $counter . '.xml';
            $this->subFileCreate($name);
            $subCollection = array_slice($collection, ($counter-1) * $split, $split);

            foreach ($subCollection as $item) {
                $xml = sprintf(
                    '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $changefreq,
                    $priority
                );
                $this->sitemapSubFileAddLine($xml, $name);
            }
            $this->subFileClose($name);
            /**
             * Adding link of the sitemap to the Sitemap Index file
             */
            $xml = sprintf('<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>',
                htmlspecialchars( $this->getSubFileUrl($name)), $date);
            $this->sitemapFileAddLine($xml);
            $counter++;
        }

        unset($collection);

        /**
         * Generate cms pages sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/page/changefreq');
        $priority   = (string)Mage::getStoreConfig('sitemap/page/priority');
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);

        /**
         * Delete old cms pages files
         */
        try {
            foreach(glob($this->getPath() . substr($this->getSitemapFilename(), 0, strpos($this->getSitemapFilename(), '.xml')) . '_pages_*.xml') as $f) {
                unlink($f);
            }
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                'Unable to delete old Page sitemaps' . $e->getMessage()
            );
        }

        /**
         * Write CMS Pages to multiple sitemap pages
         */
        $FileCounter = ceil(count($collection)/$split);
        $counter = 1;

        while( $counter <= $FileCounter) {
            $name = '_pages_' . $counter . '.xml';
            $this->subFileCreate($name);
            $subCollection = array_slice($collection, ($counter-1) * $split, $split);

            foreach ($subCollection as $item) {
                $xml = sprintf(
                    '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $changefreq,
                    $priority
                );
                $this->sitemapSubFileAddLine($xml, $name);
            }
            $this->subFileClose($name);
            /**
             * Adding link of the sitemap to the Sitemap Index file
             */
            $xml = sprintf('<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>',
                htmlspecialchars( $this->getSubFileUrl($name)), $date);
            $this->sitemapFileAddLine($xml);
            $counter++;
        }

        unset($collection);

        $this->fileClose();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }



    /**
     * Create sitemap subfile by name in sitemap directory
     *
     * @param $name
     */
    protected  function subFileCreate($name)
    {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));
        $io->streamOpen(substr($this->getSitemapFilename(), 0, strpos($this->getSitemapFilename(), '.xml')) . $name);

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        $this->_subfiles[$name] = $io;
    }

    /**
     * Add line to sitemap subfile
     *
     * @param $xml
     * @param $name
     */
    public function sitemapSubFileAddLine($xml, $name)
    {
        $this->_subfiles[$name]->streamWrite($xml);
    }


    /**
     * Create main sitemap index file
     */
    protected  function fileCreate()
    {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));
        $io->streamOpen($this->getSitemapFilename());

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        $this->_io = $io;
    }

    /**
     * Add closing tag and close sitemap file
     */
    protected function fileClose()
    {
        $this->_io->streamWrite('</sitemapindex>');
        $this->_io->streamClose();
    }

    /**
     * Add closing tag and close sitemap subfile by the name
     *
     * @param $name
     */
    protected function subFileClose($name)
    {
        $this->_subfiles[$name]->streamWrite('</urlset>');
        $this->_subfiles[$name]->streamClose();
    }

    /**
     * Get URL of sitemap subfile by the name
     *
     * @param $name
     * @return $string
     */
    public function getSubFileUrl($name)
    {
        $fileName = substr($this->getSitemapFilename(), 0, strpos($this->getSitemapFilename(), '.xml')) . $name;
        $filePath = Mage::app()->getStore($this->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . $this->getSitemapPath();
        $filePath = str_replace('//','/',$filePath);
        $filePath = str_replace(':/','://',$filePath);
        return $filePath . $fileName;
    }

    /**
     * Add line to the main file
     *
     * @param $xml
     */
    public function sitemapFileAddLine($xml)
    {
        $this->_io->streamWrite($xml);
    }
}
