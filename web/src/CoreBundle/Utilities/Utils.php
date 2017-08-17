<?php

namespace CoreBundle\Utilities;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Utils
{

    public static function getUserDetails($info, Controller $class)
    {
        $user = $class->getUser();

        if(strtolower($info)=='id')
            return $user->getId();
        if(strtolower($info)=='email')
            return $user->getEmail();
    }

    public static function getDate()
    {
        return date('Y-m-d H:i:s');
    }

    public static function getForbiddenResponse()
    {
        return array('error' => 'Access denied!');
    }

    public static function getSuccessResponse()
    {
        return array('success' => 'Successfully saved!');
    }

    public static function getError()
    {
        return array('code' => Constant::CODE_ERROR, 'message' => 'Oops! Something went wrong.');
    }

    public static function getForbid()
    {
        return array('code' => Constant::CODE_FORBIDDEN, 'message' => 'Oops! Access forbidden.');
    }

    public static function getSuccess()
    {
        return array('code' => Constant::CODE_SUCCESS, 'message' => 'Successful');
    }

    public static function getNoChange()
    {
        return array('code' => Constant::CODE_NO_CHANGE, 'message' => 'Successful');
    }
}