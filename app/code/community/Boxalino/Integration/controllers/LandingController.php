<?php

/**
 * Narrative use-case #3
 *
 * Sample controller to load the narrative view attached to a certain choice (ex:landing page)
 *
 * @author Boxalino AG <support@boxalino.com>
 */
class Boxalino_Integration_LandingController extends Mage_Core_Controller_Front_Action
{

    /**
     * Initializing defaults
     */
    protected function _initAction()
    {
        Mage::register('is_narrative', true);

        return $this;
    }

    /**
     */
    public function viewAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

}
