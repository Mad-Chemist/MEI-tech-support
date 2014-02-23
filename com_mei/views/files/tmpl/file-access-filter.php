<?php
    
    function checkAccessLevelsForFile($file) {

        /*check if denied or granted access*/
        $allowedAccess  =   split(',',$file->access_account);
        $deniedAccess   =   split(',',$file->deny_access);

        if (!$GLOBALS['PERMV'] /*|| $GLOBALS['admin'] == true*/) return true;

        /*check if user is granted access*/
        elseif (in_array($GLOBALS['user'],$allowedAccess)) return true;

        /*check if user is blocked*/
        elseif (in_array($GLOBALS['user'],$deniedAccess)) return false;
        /*check user's access level against current access needed*/
        else {
            /*check channel*/
                if(strpos($file->channel,$GLOBALS['chanV']) === false)          return false;
            /*check region*/
            elseif (strpos($file->region,$GLOBALS['regV']) === false)           return false;
            /*check if NDA matches*/
            elseif ($file->access_nda == 1 && $GLOBALS['accessV'][0] == 0)      return false; 
            /*check if level2 matches*/
            elseif ($file->access_level2 == 1 && $GLOBALS['accessV'][1] == 0)   return false; 
            /*check if oem matches*/
            elseif ($file->access_oem == 1 && $GLOBALS['accessV'][2] == 0)      return false;
            /*check if asc matches*/
            elseif ($file->access_asc == 1 && $GLOBALS['accessV'][3] == 0)      return false;
            /*check if dist matches*/
            elseif ($file->access_dist == 1 && $GLOBALS['accessV'][4] == 0)     return false;
            /*if all tests pass, return file*/
            else return true;
        }
    }


//$sqlV = mysql_query('SELECT * FROM  `44aae_user_usergroup_map` WHERE  `user_id` =314') 
// 10 == ASC
// 11 == DIST 
// 12 == LEVEL 2 
// 13 == NDA 
// 14 == OEM

?>