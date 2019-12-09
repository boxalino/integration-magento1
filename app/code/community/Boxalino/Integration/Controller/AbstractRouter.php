<?php

/**
 * Narrative use-case #3
 *
 * Custom router for the Boxalino Narrative dynamically rendered pages
 * Manages the default match logic
 * Must be extended and completed with the desired logic for matching routes and actions
 */
abstract class Boxalino_Integration_Controller_AbstractRouter extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * Frontend router defined in config for blog module
     */
    const BOXALINO_INTEGRATION_FRONTEND_ROUTER = 'narrative';
    
    /**
     * Module name
     */
    const BOXALINO_INTEGRATION_MODULE = 'Boxalino_Integration';


    /**
     * parameter key to identify narrative mapping
     */
    const BOXALINO_NARRATIVE_REQUEST_PARAMETER = 'campaign';


    /**
     * Request path: from base url to query string
     *
     * @var null|string
     */
    protected $_requestPath = null;
    
    /**
     * entity record id
     *
     * @var int|null
     */
    protected $_entityId = null;
    
    /**
     * Match the request
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {        
        // checking before even try to find out that current module
        // if forward to noroute ... no match should take place
        if (!$this->_beforeModuleMatch() || $request->getActionName() == 'noroute') {
            return false;
        }

        // set defaults
        $this->fetchDefault();
        $realModule = self::BOXALINO_INTEGRATION_MODULE;
        $module     = self::BOXALINO_INTEGRATION_FRONTEND_ROUTER;
        $controller = $this->getController();
        $action     = $this->getAction();                
        
        // get the path form base to query string
        $path = trim($request->getPathInfo(), '/');
        $path = ($path) ? $path : $this->_getDefaultPath();
        $p = $p = explode('/', $path);
        
        $this->_requestPath = array_filter($p);
        
        // if path is empty, there's no need to continue
        if (empty($this->_requestPath) || false === $this->matchPath()) {
            return false;
        }      
          
        // get controller class name
        $controllerClassName = $this->_validateControllerClassName($realModule, $controller);
        if (!$controllerClassName) {
            return false;
        }

        // instantiate controller class
        $front = $this->getFront();
        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $front->getResponse());

        if (!$controllerInstance->hasAction($action)) {
            return false;
        }
        
        // set values only after all the checks are done
        $request->setModuleName($module);
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setControllerModule($realModule);
        
        // set params
        foreach ($this->getParams() as $key => $value) {
            $request->setParam($key, $value);
        }
        
        $request->setRouteName($realModule);
      
        // dispatch action
        $request->setDispatched(true);
        $controllerInstance->dispatch($action);

        return true;
    }
       
     /**
     * Implement abstract method that will return the controller name that
     * will be dispatched
     * 
     * @return string
     */
    protected function getController()
    {
        $callingClass = get_called_class();
        
        return $callingClass::BOXALINO_INTEGRATION_ROUTER_CONTROLLER;
    }
    
    /**
     * Implement abstract method that will return the controller action that 
     * will be dispatched.
     * 
     * @return string
     */
    protected function getAction()
    {
        $callingClass = get_called_class();
        
        return $callingClass::BOXALINO_INTEGRATION_ROUTER_ACTION;
    }

    /**
     * Return the params that has to be set on the request
     * 
     * @return []
     */
    abstract protected function getParams();
    
    /**
     * Match the router path against the logic the url are being generated
     * 
     * @return boolean
     */
    abstract protected function matchPath();
}
