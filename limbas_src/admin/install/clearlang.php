<?php
/*
 * Copyright notice
 * (c) 1998-2016 Limbas GmbH - Axel westhagen (support@limbas.org)
 * All rights reserved
 * This script is part of the LIMBAS project. The LIMBAS project is free software; you can redistribute it and/or modify it on 2 Ways:
 * Under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * Or
 * In a Propritary Software Licence http://limbas.org
 * The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 * Version 3.0
 */

/*
 * ID:
 */

$lpath = "/usr/local/httpd/htdocs/projekte/limbas/";
require($lpath."inc/include_db.lib");
require($lpath."lib/include.lib");


$sqlquery = "SELECT DISTINCT ELEMENT_ID FROM LMB_LANG WHERE TYP != 2 ORDER BY ELEMENT_ID";
$rs = odbc_exec($db,$sqlquery);

$bzm = 1;
while(odbc_fetch_row($rs, $bzm)) {
	
$cmd = "cd /usr/local/httpd/htdocs/projekte/limbas; grep -r \"\\\$lang\\[".odbc_result($rs, "ELEMENT_ID")."\\]\" *;";
$grep = explode(chr(10),`$cmd`);


if(!$grep[0]){
	$sqlquery1 = "DELETE FROM LMB_LANG WHERE ELEMENT_ID = ".odbc_result($rs, "ELEMENT_ID");
	$rs1 = odbc_exec($db,$sqlquery1);
	$res[] = odbc_result($rs, "ELEMENT_ID");
	$count++;
}


$bzm++;
}

echo "$count<BR><BR>";
#echo implode("<BR>",$res);



?>