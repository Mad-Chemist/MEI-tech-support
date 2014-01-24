<?php 
		//easy to edit array with file extensions
		$GLOBALS['extArr'] = array(
		"exe" => array("cfg", "exe", "app", "bat", "cgi", "com", "gadget", "jar", "pif", "vb", "wsf", "ini"),
		"zip" => array("7z", "cbr", "deb", "gz", "pkg", "rar", "rpm", "sitx", "tar.gz", "zip", "zipx"),
		"dat" => array("csv", "dat", "xml", "db", "dbf", "accdb", "dbf", "mdb", "pdb", "sql"),
		"doc" => array("doc", "dot", "docx", "docm", "dotx", "dotm", "txt", "rtf", "wps", "odt"),
		"exl" => array("xls", "xlt", "xlm", "xlsx", "xlsm", "xltx", "xltx", "xltm", "xlsb", "xla", "xlam", "xll", "xlw"),
		"ppt" => array("ppt", "pps", "pptx", "pptm", "potx", "potm", "ppam", "ppsx", "ppsm", "sldx", "sldm", "key"),
		"img" => array("jpg", "jpeg", "png", "gif"),
		"pdf" => array("pdf")
		);
		//chooses correct icon to display per file
		function assignIMG($getEXT) {
			if (in_array($getEXT, $GLOBALS['extArr']['doc'])) $icon = "doc";
			else if (in_array($getEXT, $GLOBALS['extArr']['exl'])) $icon = "exl";
			else if (in_array($getEXT, $GLOBALS['extArr']['ppt'])) $icon = "ppt";
			else if (in_array($getEXT, $GLOBALS['extArr']['pdf'])) $icon = "pdf";
			else if (in_array($getEXT, $GLOBALS['extArr']['exe'])) $icon = "exe";
			else if (in_array($getEXT, $GLOBALS['extArr']['zip'])) $icon = "zip";
			else if (in_array($getEXT, $GLOBALS['extArr']['img'])) $icon = "img";
			else if (in_array($getEXT, $GLOBALS['extArr']['dat'])) $icon = "dat";
			else $icon = "unid";
			return "images/icons/".$icon.".png";
		}

		function retrieveEXT($fileID, $fileCUR) {
			 //schubert addition -- gets extension of file
			$getEXT = mysql_query('SELECT `filename` FROM `44aae_meiadmin_file_versions` WHERE `fk_file_id`="'.$fileID.'" AND `version`="'.$fileCUR.'"');
			$getEXT = mysql_fetch_assoc($getEXT); 
			$getEXT = $getEXT['filename'];
			$getEXT = strtolower(pathinfo($getEXT, PATHINFO_EXTENSION));
			return "<img src='/".assignIMG($getEXT)."' class='extIMG'> ";
		}

?>