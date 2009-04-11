<?php
/**
 * Makes it possible to fix wrong encoding object in xml response from the server
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
 * Makes it possible to fix wrong encoding object in xml response from the server
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
class XML_RPC2_ServerFixedEncodingObject extends XML_RPC2_Server
{
    /**
     * Factory method to select a backend and return a new XML_RPC2_Server based on the backend
     *
     * @param mixed $callTarget either a class name or an object instance.
     * @param array associative array of options
     * @return object a server class instance
     */
    public static function create($callTarget, $options = array())
    {
        if (isset($options['backend'])) {
            XML_RPC2_Backend::setBackend($options['backend']);
        }
        if (isset($options['prefix'])) {
            $prefix = $options['prefix'];
        } else {
            $prefix = '';
        }
        $backend = XML_RPC2_BackendFixedEncodingObject::getServerClassname();
        // Find callHandler class
        if (!isset($options['callHandler'])) {
            if (is_object($callTarget)) { // Delegate calls to instance methods
                require_once 'XML/RPC2/Server/CallHandler/Instance.php';
                $callHandler = new XML_RPC2_Server_CallHandler_Instance($callTarget, $prefix);
            } else { // Delegate calls to static class methods
                require_once 'XML/RPC2/Server/CallHandler/Class.php';
                $callHandler = new XML_RPC2_Server_CallHandler_Class($callTarget, $prefix);
            }
        } else {
            $callHandler = $options['callHandler'];
        }
        return new $backend($callHandler, $options);
    }

    public function handleCall() {
        throw new Exception('XML_RPC2_ServerFixedEncodingObject::handleCall should be extended');
    }
}
?>