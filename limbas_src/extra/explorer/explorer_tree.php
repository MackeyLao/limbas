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
 * ID: 20
 */
?>

<div id="lmbAjaxContainer" class="ajax_container" style="visibility:hidden;" OnClick="activ_menu=1;"></div>

<DIV ID="filemenu" class="lmbContextMenu" style="position:absolute;visibility:hidden;z-index:2" OnClick="activ_menu = 1;">
<form name="form_menu" ACTION="main.php" METHOD="post">
<input type="hidden" name="<?echo $_SID;?>" value="<?echo session_id();?>">
<input type="hidden" name="action" VALUE="<?=$action?>">
<input type="hidden" NAME="add_file">
<input type="hidden" NAME="del_file">
<input type="hidden" NAME="LID">
<?
pop_top('filemenu');
pop_left();
echo "&nbsp;<span id=\"menu_name\" style=\"font-weight:bold;width:100px;overflow:hidden;\">-</span>";
pop_right();
pop_line();
pop_menu(119,'','');pop_line();
pop_submenu(116,'','');pop_line();
if($action != "message_tree"){
pop_menu(130,'','');
pop_menu(129,'','');
pop_line();
}
pop_menu(171,'','');
pop_bottom();
?>
</form></DIV>

<DIV ID="cachelist" class="lmbContextMenu" style="visibility:hidden;z-index:3" OnClick="activ_menu = 1;">
<?
pop_top('cachelist');
pop_left();
?><span ID="cachelist_area"></span><?
pop_right();
pop_bottom();
?>
</DIV>

<DIV ID="rename" class="lmbContextMenu" style="visibility:hidden;z-index:2" OnClick="activ_menu = 1;">
<form name="form_rename" ACTION="main.php" METHOD="post">
<input type="hidden" name="<?echo $_SID;?>" value="<?echo session_id();?>">
<input type="hidden" name="action" VALUE="<?=$action?>">
<INPUT TYPE="hidden" NAME="LID">
<?
pop_top('rename');
pop_input(0,'document.form_rename.submit();','rename_file','',0);
pop_bottom();
?>
</form></DIV>



<script language="JavaScript">

var LmEx_edit_id = null;

// ----- Js-Script-Variablen --------
var jsvar = new Array();
jsvar["copycache"] = "<?=$umgvar[copycache]?>";
jsvar["WEB7"] = "<?=$farbschema[WEB7]?>";
jsvar["WEB4"] = "<?=$farbschema[WEB4]?>";

img3=new Image();img3.src="pic/outliner/plusonly.gif";
img4=new Image();img4.src="pic/outliner/minusonly.gif";

icon1 = 'lmb-folder2-closed';
icon2 = 'lmb-folder2-open';
icon7 = 'lmb-folder3-closed';
icon8 = 'lmb-folder3-open';
icon9 = 'lmb-folder4-closed';
icon10 = 'lmb-folder4-open';

//----------------- mark copy/paste symbols -------------------
function LmEx_ajaxCopyPasteEvent(typ) {
	var getstring = "&typ="+typ
	ajaxGet(null,'main_dyns.php','saveCopyPasteEvent' + getstring,null,'LmEx_ajaxResultCopyPasteEvent');
}

//----------------- mark copy/paste symbols -------------------
function LmEx_ajaxResultCopyPasteEvent(result) {
	if(browser_ns5){
		parent.explorer_main.document.getElementById("conextIcon_191").style.opacity = '1.0';
	}else{
		parent.explorer_main.document.getElementById("conextIcon_191").style.filter = 'Alpha(opacity=100)';
	}
}

function LmEx_delfile(){
	var del = confirm('<?=$lang[821]?>');
	if(del){
		// ---- cookie setzten ------
		dspl["id" + document.form_menu.LID.value] = '';
		var cookTab=new Array();
		// ---- cookie setzten ------
		for(key in dspl){
			if(dspl[key] == key.substring(2,key.length)){
				cookTab.push(key.substring(2,key.length));
			}
		}
		setzeCookie("limbas_explorer",cookTab.join("_"));

		document.form_menu.del_file.value = document.form_menu.LID.value;
		parent.explorer_main.location.href='main.php?action=explorer_main&LID=0';
		document.form_menu.submit();
	}
}

function LmEx_newfile(){
	var filename = prompt('<?=$lang[813]?>');
	if(name){
		document.form_menu.add_file.value = filename;
		document.form_menu.submit();
	}
}

function closefiles(){
	var elid = document.form2.LID.value;
	if(elid){
                var icon = $('#p'+elid);
                if(icon.hasClass(icon2)){
                        icon.removeClass(icon2);
                        icon.addClass(icon1);
                }else if(icon.hasClass(icon8)){
                        icon.removeClass(icon8);
                        icon.addClass(icon7);
                }else if(icon.hasClass(icon10)){
                        icon.removeClass(icon10);
                        icon.addClass(icon9);
                }
	}
}

// --- Limbas-Verzeichnis ---
function listlmdata(ID,LEVEL,TYP){
	closefiles();
        
        var icon = $('#p'+ID);
        if(icon.hasClass(icon1)){
                icon.removeClass(icon1);
                icon.addClass(icon2);
        }else if(icon.hasClass(icon7)){
                icon.removeClass(icon7);
                icon.addClass(icon8);
        }else if(icon.hasClass(icon9)){
                icon.removeClass(icon9);
                icon.addClass(icon10);
        }        
        
	document.form2.LID.value = ID;
	document.form2.typ.value = TYP;
		
	parent.explorer_main.location.href='main.php?action=explorer_main&LID='+ID+'&typ='+TYP;
}


var dspl = new Array();
function popup(ID,LEVEL,TABID,TYP){
	var cli;
	if(browser_ns5){cli = ".nextSibling";}else{cli = "";}
	eval("var nested = document.getElementById('f_"+ID+"').nextSibling"+cli);
	var picname = "i" + ID;
	if(document.images[picname].src == img4.src) {
		document.images[picname].src = img3.src;
		nested.style.display="none";
		dspl["id" + ID] = '';
	}else{
		document.images[picname].src = img4.src;
		nested.style.display='';
		dspl["id" + ID] = ID;
	}

	var cookTab=new Array();
	// ---- cookie setzen ------
	for(key in dspl){
		if(dspl[key] == key.substring(2,key.length)){
			cookTab.push(key.substring(2,key.length));
		}
	}
	setzeCookie("limbas_explorer",cookTab.join("_"));
}

// ----- Baumstruktur aus cookie nachbilden -----------
function rebuild_tree(){
	if(browser_ns5){var cli = ".nextSibling";}else{var cli = "";}
	var treelist = holeCookie("limbas_explorer");
	if(treelist){
		var list = treelist.split("_");
		for (var i in list){
			if(list[i]){
				var picname = "i" + list[i];
				if(document.images[picname]){
					eval("var nested = document.getElementById('f_"+list[i]+"').nextSibling"+cli);
					document.images[picname].src = img4.src;
					nested.style.display='';
					dspl["id"+list[i]] = list[i];
				}else{
					dspl["id"+list[i]] = '';
				}
			}
		}
	}
}

// Ordner in Cookie speichern
function LmEx_cache_file(todo){
	var copyfile = new Array();
	copyfile.push("f"+var_id);
	var cookiename = cache_list(todo,'1',var_typ);
	setzeCookie(cookiename, copyfile);
	LmEx_ajaxCopyPasteEvent(todo);
	LmEx_divclose();
}


// Layer positionieren und öffnen
function setxypos(evt,el) {
	if(browser_ns5){
		document.getElementById(el).style.left=20;
		document.getElementById(el).style.top=evt.pageY;
	}else{
		document.getElementById(el).style.left=20;
		document.getElementById(el).style.top=window.event.clientY + document.body.scrollTop;
	}
}

var activ_menu = null;
function LmEx_divclose() {
        if(!activ_menu){
	document.getElementById("filemenu").style.visibility='hidden';
	document.getElementById("rename").style.visibility='hidden';
	document.getElementById("cachelist").style.visibility='hidden';
	}
	activ_menu = 0;
}

function open_filemenu(evt,file,id,typ,level){
	activ_menu = 1;
	document.form_rename.rename_file.value = file;
	document.form_menu.LID.value = id;
	document.form_rename.LID.value = id;
	LmEx_edit_id = id;
	var_file = file;
	var_typ = typ;
	var_id = id;
	var_level = level;
	setxypos(evt,'filemenu');
	document.getElementById("filemenu").style.visibility='visible';
	document.getElementById("menu_name").firstChild.nodeValue = file;
}

function body_click(){
	window.setTimeout("LmEx_divclose()", 50);
}

function LmEx_open_menu(evt,el,menu){
	activ_menu = 1;

	if(jsvar["copycache"] == 1 && menu == 'cachelist'){
		LmEx_create_cachelist(evt);
	}else{
		document.getElementById(menu).style.left = el.offsetLeft + document.getElementById('filemenu').offsetLeft + 20;
		document.getElementById(menu).style.top = el.offsetTop + el.offsetHeight + document.getElementById('filemenu').offsetTop + 50;
		document.getElementById(menu).style.visibility='visible';
		LmEx_create_cachelist(evt);
	}

	LmEx_divclose();

}

</script>





<BR>
<FORM name="form1">
<INPUT TYPE="hidden" NAME="edit_id" value="1">
</FORM>

<FORM ACTION="main.php" METHOD="post" name="form2" TARGET="explorer_main">
<input type="hidden" name="<?echo $_SID;?>" value="<?echo session_id();?>">
<input type="hidden" name="action" value="explorer_main">
<INPUT TYPE="hidden" NAME="LID" VALUE="<?=$filestruct["id"][0]?>">
<INPUT TYPE="hidden" NAME="typ" VALUE="<?=$typ?>">
<INPUT TYPE="hidden" NAME="move_file">
<INPUT TYPE="hidden" NAME="copy_file">
<INPUT TYPE="hidden" NAME="tabid">
<INPUT TYPE="hidden" NAME="reset">


<TABLE BORDER="0" cellspacing="0" cellpadding="0"><TR><TD WIDTH="20">&nbsp;</TD><TD>

<?php
function files1($LEVEL,$start,$only_typ){
	global $filestruct;
	global $ffilter;
	global $action;
	global $typ;

	if($start){
		if($LEVEL){$vis = "style=\"display:none\"";}else{$vis = "";}
		echo "<div id=\"foldinglist\" $vis>\n";
		echo "<TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"><TR><TD WIDTH=\"10\">&nbsp;</TD><TD>\n";
	}
	if($filestruct["id"]){
	foreach($filestruct["id"] as $key => $value){
		if($filestruct["level"][$key] == $LEVEL AND $filestruct["view"][$key] AND ($filestruct["typ"][$key] == $typ OR $typ != $only_typ)){
			if(in_array($filestruct["id"][$key],$filestruct["level"])){
				$next = 1;
				$pic = "<IMG SRC=\"pic/outliner/plusonly.gif\" NAME=\"i".$filestruct["id"][$key]."\" OnClick=\"popup('".$filestruct["id"][$key]."','$LEVEL','".$filestruct[tabid][$key]."','".$filestruct[typ][$key]."')\" STYLE=\"cursor:hand\">";
			}else{
				$next = 0;
				$pic = "<IMG SRC=\"pic/outliner/blank.gif\">";
			}
			if($filestruct["readonly"][$key] OR ($action == "explorer_tree" AND $filestruct["typ"][$key] == 2)){$filterpic = "4";}elseif($filestruct["typ"][$key] == 7){$filterpic = "3";}else{$filterpic = "2";}
			# ---- Persönlicher Ordner ----
			#if($filestruct[typ][$key] == 4){
			#	echo "<div ID=\"f_".$filestruct["id"][$key]."\"><TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"><TR TITLE=\"(".$filestruct["id"][$key].")\"><TD>$pic</TD><TD><IMG SRC=\"pic/outliner/box".$filterpic."_close.gif\" ID=\"p".$filestruct["id"][$key]."\" NAME=\"p".$filestruct["id"][$key]."\" OnClick=\"open_filemenu(event,'".$filestruct[name][$key]."','".$filestruct["id"][$key]."','".$filestruct[typ][$key]."','$LEVEL');\" STYLE=\"cursor:hand\"></TD><TD ";
			#	echo "style=\"cursor:pointer;\" OnMouseOver=\"this.style.color='blue';\" OnMouseOut=\"this.style.color='black';\" OnClick=\"listwddata('".$filestruct["id"][$key]."','".$filestruct["path"][$key]."','".$filestruct["typ"][$key]."')\"";
			#	echo ">&nbsp;".$filestruct[name][$key]."</TD></TR></TABLE></div>\n";
			#	# ---- Limbas-Verzeichnis ----
			#}else{
				echo "<div ID=\"f_".$filestruct["id"][$key]."\"><TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"><TR TITLE=\"(".$filestruct["id"][$key].")\"><TD>$pic</TD><TD><i class=\"lmb-icon lmb-folder" .$filterpic. "-closed\" ID=\"p".$filestruct["id"][$key]."\" NAME=\"p".$filestruct["id"][$key]."\" OnClick=\"open_filemenu(event,'".$filestruct[name][$key]."','".$filestruct["id"][$key]."','".$filestruct[typ][$key]."','$LEVEL');\" STYLE=\"cursor:hand\"></i></TD><TD ";
                                echo "style=\"cursor:pointer;\" OnMouseOver=\"this.style.color='blue';\" OnMouseOut=\"this.style.color='black';\" OnClick=\"listlmdata('".$filestruct["id"][$key]."','$LEVEL','".$filestruct["typ"][$key]."')\"";
				echo ">&nbsp;".$filestruct[name][$key]."</TD></TR></TABLE></div>\n";
			#}


			if($next){
				$tab = 20;files1($filestruct["id"][$key],1,$only_typ);
			}else{
				echo "<div id=\"foldinglist\" style=\"display:none\"></div>\n";
			}
		}
	}
	}
	if($start){
		echo "</TD></TR></TABLE>\n";
		echo "</div>\n";
	}
}

$lv = 0;
if($typ == 2){$lv = $session[messages][level];}
files1($lv,0,2);
?>

</TD></TR></TABLE></FORM>