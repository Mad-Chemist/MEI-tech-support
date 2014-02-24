<?php
    
    function checkAccessLevelsForFile($file) {

        /*check if denied or granted access*/
        $allowedAccess  =   split(',',$file->access_account);
        $deniedAccess   =   split(',',$file->deny_access);

        if (!$GLOBALS['PERMV'] || $GLOBALS['admin'] == true)                    return true;

        /*check to see if user is logged in, */
        elseif (!$GLOBALS['unlogged']) {
            
            /*check if user is granted access*/
            if (in_array($GLOBALS['user'],$allowedAccess))                      return true;

            /*check if user is blocked*/
            elseif (in_array($GLOBALS['user'],$deniedAccess))                   return false;
        }

        /*check channel*/
        elseif (strpos($file->channel,$GLOBALS['chanV']) === false)             return false;
        
        /*check region*/
        elseif (strpos($file->region,$GLOBALS['regV']) === false)               return false;

        /*check user's access level against current access needed*/


        /*if file is not public, use "AND" to determine access criteria*/
        elseif ($file->public == 0) {

            /*if file has no access and isn't made public, don't display file*/
            if ($file->access_nda == 0 && $file->access_level2 == 0 && $file->access_oem == 0 && $file->access_asc == 0 && $file->access_dist == 0) return false;


            elseif ($file->access_nda == 1 && $GLOBALS['accessV'][0] == 0)      return false; 
            elseif ($file->access_level2 == 1 && $GLOBALS['accessV'][1] == 0)   return false; 
            elseif ($file->access_oem == 1 && $GLOBALS['accessV'][2] == 0)      return false;
            elseif ($file->access_asc == 1 && $GLOBALS['accessV'][3] == 0)      return false;
            elseif ($file->access_dist == 1 && $GLOBALS['accessV'][4] == 0)     return false;

            /*if all tests pass, return file*/
            else                                                                return true;
        }

        /*if file is public, use "OR" to determine access criteria*/
        elseif ($file->public == 1) {
            
            /*if file is public and no access is set, offer file*/
            if ($file->access_nda == 0 && $file->access_level2 == 0 && $file->access_oem == 0 && $file->access_asc == 0 && $file->access_dist == 0) return true;

            elseif ($file->access_nda == 1 && $GLOBALS['accessV'][0] == 1)      return true; 
            elseif ($file->access_level2 == 1 && $GLOBALS['accessV'][1] == 1)   return true; 
            elseif ($file->access_oem == 1 && $GLOBALS['accessV'][2] == 1)      return true;
            elseif ($file->access_asc == 1 && $GLOBALS['accessV'][3] == 1)      return true;
            elseif ($file->access_dist == 1 && $GLOBALS['accessV'][4] == 1)     return true;

            /*if all tests pass, return false*/
            else                                                                return false;
        }

        /*Access test should never make it this far, therefore don't give access to file if it does*/
        else                                                                    return false;
    }

?>