<?php
/**
 * Fixes wrong encoding object in xml response from the server
 * http://pear.php.net/bugs/bug.php?id=12249
 * 
 * PHP Version 4 and 5
 *
 * @category Patches
 * @package  XML_RPC2_Backend_Php_ServerFixedEncodingObject
 * @author   Sune Jensen <sj@sunet.dk>
 * @author   Lars Olesen <lars@legestue.net>
 *
 */
 
/**
 * Fixes wrong encoding object in xml response from the server
 * http://pear.php.net/bugs/bug.php?id=12249
 *
 * @category Patches
 * @package  XML_RPC2_Backend_Php_ServerFixedEncodingObject
 * @author   Sune Jensen <sj@sunet.dk>
 * @author   Lars Olesen <lars@legestue.net>
 *
 */
class XML_RPC2_Backend_Php_ServerFixedEncodingObject extends XML_RPC2_Backend_Php_Server
{

    /**
     * constructor - uses parent.
     */
    function __construct($callHandler, $options = array())
    {
        parent::__construct($callHandler, $options);
    }
    
    /**
     * Get the XML response of the XMLRPC server with correct encoding object.
     * 
     * @return string XML response
     */
    public function getResponse()
    {
        try {
            set_error_handler(array('XML_RPC2_Backend_Php_Server', 'errorToException'));
            $request = @simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA']);
            // TODO : do not use exception but a XMLRPC error !
            if (!is_object($request)) throw new XML_RPC2_FaultException('Unable to parse request XML', 0);
            $request = XML_RPC2_Backend_Php_Request::createFromDecode($request);  
            $methodName = $request->getMethodName();
            $arguments = $request->getParameters();
            if ($this->signatureChecking) {
                $method = $this->callHandler->getMethod($methodName);
                if (!($method)) {
                    // see http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php for standard error codes 
                    return (XML_RPC2_Backend_Php_Response::encodeFault(-32601, 'server error. requested method not found'));
                }
                if (!($method->matchesSignature($methodName, $arguments))) {
                    return (XML_RPC2_Backend_Php_Response::encodeFault(-32602, 'server error. invalid method parameters'));     
                }
            }
            restore_error_handler();
            return (XML_RPC2_Backend_Php_Response::encode(call_user_func_array(array($this->callHandler, $methodName), $arguments), $this->encoding));              
        } catch (XML_RPC2_FaultException $e) {
            return (XML_RPC2_Backend_Php_Response::encodeFault($e->getFaultCode(), $e->getMessage(), $this->encoding));
        } catch (Exception $e) {
            return (XML_RPC2_Backend_Php_Response::encodeFault(1, 'Unhandled ' . get_class($e) . ' exception:' . $e->getMessage(), $this->encoding));
        }        
    }   
}
?>