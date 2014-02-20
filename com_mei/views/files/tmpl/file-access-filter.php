<?php

	function checkAccessLevelsForFile($file, $tArr) {
		if (!$GLOBALS['PERMV'] /*|| $GLOBALS['admin'] == true*/) return true; 
		else {
			/*begin checking access levels...*/

			/*if user was granted access, avoid checking other criteria*/
			/*need to add check if user is granted access below*/
			if (false) return true;
			/*check channel*/
			elseif(strpos($file->channel,$GLOBALS['chanV']) === false) return false;
			/*check region*/
			elseif (strpos($file->region,$GLOBALS['regV']) === false) return false;
			/*check if user is blocked*/
			elseif (in_array($GLOBALS['user'],$tArr)) return false;
			/*check if NDA matches*/
			elseif ($file->access_nda == 1 && $GLOBALS['accessV'][0] == 0) return false; 
			/*check if level2 matches*/
			elseif ($file->access_level2 == 1 && $GLOBALS['accessV'][1] == 0) return false; 
			/*check if oem matches*/
			elseif ($file->access_oem == 1 && $GLOBALS['accessV'][2] == 0) return false;
			/*check if asc matches*/
			elseif ($file->access_asc == 1 && $GLOBALS['accessV'][3] == 0) return false;
			/*check if dist matches*/
			elseif ($file->access_dist == 1 && $GLOBALS['accessV'][4] == 0) return false;
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