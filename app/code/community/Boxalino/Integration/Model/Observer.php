<?php

/**
 * Class Boxalino_Integration_Model_Observer
 *
 * @author Boxalino AG <support@boxalino.com>
 */
class Boxalino_Integration_Model_Observer
{
    /**
     * Inject handles into page display
     *
     * @scenario2 Adding Observer to apply Custom Page Layout handle updates on category view
     * @param $observer
     * @return $this
     */
    public function applyLayoutHandlesOnCategoryView($observer)
    {
        try {
            /** @var $layout Mage_Core_Model_Layout */
            $layout = $observer->getEvent()->getLayout();
            if (!Mage::registry('current_product')) {
                $category = Mage::registry('current_category');
                if ($category instanceof Mage_Catalog_Model_Category) {
                    $design = Mage::getSingleton('catalog/design');
                    $settings = $design->getDesignSettings($category);
                    if ($settings->getPageLayout()) {
                        $layout->getUpdate()->addHandle($settings->getPageLayout());
                    }
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }
}