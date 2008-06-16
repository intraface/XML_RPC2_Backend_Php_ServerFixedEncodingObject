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
abstract class XML_RPC2_BackendFixedEncodingObject extends XML_RPC2_Backend
{
    
    /**
     * Include the relevant php files for the server class, and return the backend server
     * class name.
     *
     * @return string The Server class name
     */
    public static function getServerClassname() {
        require_once(sprintf('XML/RPC2/Backend/%s/ServerFixedEncodingObject.php', self::getBackend()));
        return sprintf('XML_RPC2_Backend_%s_ServerFixedEncodingObject', self::getBackend());
    }
    
}