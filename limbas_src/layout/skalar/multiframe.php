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
?>


<SCRIPT LANGUAGE="JavaScript">

browserType();

var icon = new Array();
icon['pfeil_r']=new Image();icon['pfeil_r'].src="pic/outliner/pfeil_r.gif";
icon['pfeil_u']=new Image();icon['pfeil_u'].src="pic/outliner/pfeil_u.gif";


var noMoreRefresh = 0;
function limbasMultiframePreview(id,type,manual,dropitem,gtabid,params){
	if(!noMoreRefresh || manual){
		ajaxGet(0,"main_dyns.php","multiframePreview&limbasMultiframeItem="+type+"&id="+id+"&gtabid="+gtabid+"&dropitem="+dropitem+"&"+params,null,"limbasMultiframePreviewPost");
		window.clearInterval(eval("refreshPreview" + id));
		var fct = "limbasMultiframePreview(" + id + ",'" + type + "',0,0,'"+gtabid+"','"+params+"')";
		var rate = document.getElementById("autorefreshPreviewWorkflow_"+id).value;
		if(rate){
			eval("refreshPreview" + id + " = window.setInterval(fct,rate*60*1000);");
		}
	}
}

function limbasMultiframePreviewPost(string){
	var ldmfpstring = document.getElementById("limbasDivMultiframePreview"+string.split("#L#")[0]);
	var string_ = string.split("#L#");
	var string_type = string_[0];
	var string_value = string_[1];
	if(string != "" && ldmfpstring){
		ldmfpstring.innerHTML = string_value;
		ldmfpstring.style.visibility = "visible";
	}
}

function hideShow(evt,id,name,gtabid,params,type,manual){
	
	var el = document.getElementById('CONTENT_' + id);
	if(document.getElementById('CONTENT2_' + id)){var el2 = document.getElementById('CONTENT2_' + id);}
	if(el.style.display=='none'){
		$(el).show('fast');
		//el.style.display = '';
		if(document.getElementById('CONTENT2_' + id)){el2.style.display = '';}
		pic = document.getElementById('HS' + id);
		if(pic){
			pic.className = 'lmb-icon lmb-angle-up';
		}
		if(evt){ajaxGet(null,'main_dyns.php','layoutSettings&menu='+name+'&show=1',null);}
		if(type){limbasMultiframePreview(id,type,manual,0,gtabid,params);}
	}else if(!evt.shiftKey && !evt.ctrlKey){
		$(el).hide('fast');
		//el.style.display = 'none';
		if(document.getElementById('CONTENT2_' + id)){el2.style.display = 'none';}
		pic = document.getElementById('HS' + id);
		if(pic){
			pic.className = 'lmb-icon lmb-angle-down';
		}
		if(type){clearInterval(eval("refreshPreview" + id));}
		if(evt){ajaxGet(null,'main_dyns.php','layoutSettings&menu='+name+'&show=0',null);}
	}else if(evt.shiftKey || evt.ctrlKey){
		limbasMultiframePreview(id,type,manual,0,gtabid,params);
	}
}


function hideShowSub(div,elt){
	eltDiv = document.getElementById(div);

	if(!eltDiv) return;

	eltTr = eltDiv.getElementsByTagName("TR");
	if(!eltTr) return;

	arrow = document.getElementById("arrowSub" + elt);
	if(!arrow) return;

        if($( arrow ).hasClass('lmb-caret-down')) {
            $( arrow ).removeClass('lmb-caret-down');
            $( arrow ).addClass('lmb-caret-right');
            
		for(i=0;i<eltTr.length;i++){
		if(eltTr[i].id == "subElt_" + elt) {
				eltTr[i].style.display='none';
		}
            }
        } else {
            $( arrow ).removeClass('lmb-caret-right');
            $( arrow ).addClass('lmb-caret-down');
            
		for(i=0;i<eltTr.length;i++){
                if(eltTr[i].id == "subElt_" + elt) {
				eltTr[i].style.display='';
		}
	}
}
}

// window open
function open_quickdetail(gtabid,ID,form_id){
	newwin=open("main.php?action=gtab_change&gtabid="+gtabid+"&ID="+ID+"&form_id="+form_id ,"quickdetail","toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1,width=850,height=500");
}

var activ_menu = null;
function divclose() {
	if(!activ_menu){
	document.getElementById("frmlist").style.visibility='hidden';
	}
	activ_menu = 0;
}

// Schließe alle Context-Menüs bei Klick auf Hintergrund 
function body_click(){
	window.setTimeout("divclose()", 50);
}

// anderes Fenster wählen
function change_frame(file){
	if(file != '<?$session["multiframe"]?>'){
		document.location.href="main.php?<?SID?>&action=multiframe&change_frame="+file;
	}
}

hide_frame_size = 230;
function hide_frame(){

	var pcols = top.document.getElementById("mainset").cols;
	fcols = pcols.split(",");

	if(fcols[2] <= 30){
		lfr = hide_frame_size;
		document.getElementById("hiddenframe").style.display = 'none';
		document.getElementById("multiframe").style.display = '';
		noMoreRefresh = 0;
		fs = 0;
	}else{
		if(browser_ns5){
			lfr = 15;
		}else{
			lfr = 30;
		}
		document.getElementById("hiddenframe").style.display = '';
		document.getElementById("multiframe").style.display = 'none';
		noMoreRefresh = 1;
		fs = lfr;
	}
	var size = fcols[0]+",*,"+lfr;
	
	ajaxGet(null,'main_dyns.php','layoutSettings&frame=multiframe&size='+fs,null);
	top.document.getElementById("mainset").cols = size;

	document.onmouseup = null;
	document.onmousemove = null;
	return false;
}




var dropel = null;
var dropel_width = null;
var posx = null;
var elwidth = null;
function lmbIniDrag(evt,el) {

		dropel = el;
		document.onmouseup = lmbEndResizeFrame;
		elwidth = el.offsetWidth;
		if(browser_ns5){
			posx = evt.screenX;
		}else{
			posx = window.event.screenX;
		}
		document.onmousemove = lmbResizeFrame;
	
	return false;
}

function lmbEndResizeFrame() {
	document.onmouseup = null;
	document.onmousemove = null;

	var elw = dropel.offsetWidth;
	hide_frame_size = elw+10;
	if(elwidth > 50 && elwidth < 400 && Math.abs((elw-elwidth)) > 10){
		hide_frame_size = elw+10;
		ajaxGet(null,'main_dyns.php','layoutSettings&frame=multiframe&size='+elw,null);
	}
	
	return false;
}

function lmbResizeFrame(e) {
	
	if(browser_ns5){
		evw = e.screenX - posx;
		dw = elwidth - evw;
	}else{
		evw = window.event.screenX - posx;
		dw = elwidth - evw;
	}

	if(dw > 400 || dw < 50){return false;}
	
	if(Math.abs((dw-elwidth)) < 5){return;}
	
	var pcols = top.document.getElementById("mainset").cols;
	fcols = pcols.split(",");
	var size = fcols[0]+",*,"+dw;

	
	top.document.getElementById("mainset").cols = size;

	return false;
}

function goautoopen(i){
	eval(autoopen[i]);
}

function startautoopen(){
	var t = 0;
	for(i=0;i<autoopen.length;i++){
		if(autoopen[i]){
			t = t + 1000;
			window.setTimeout("goautoopen("+i+")", t);
		}
	}
}






var autoopen = new Array();
var jsvar = new Array();
jsvar["user_id"] = "<?=$session["user_id"]?>";

</SCRIPT>


<?php
$multfrdispl = "";
$hiddendispl = "display:none";

$menu_setting = lmbGetMenuSetting();
if($menu_setting["frame"]["multiframe"] AND $menu_setting["frame"]["multiframe"] <= 30){
	$multfrdispl = "display:none";
	$hiddendispl = "";
	?>
	<SCRIPT LANGUAGE="JavaScript">
	noMoreRefresh = 1;
	</SCRIPT>
	<?
}
?>

<div id="hiddenframe" style="height:90%;cursor:pointer;<?=$hiddendispl?>" OnClick="return hide_frame();">
    <i class="lmb-icon lmb-icon-aw lmb-caret-left" style="position:absolute;left:3px;top:4px;cursor:pointer;"></i>
</div>

<div id="multiframe" style="<?=$multfrdispl?>">

<div OnClick="activ_menu=1" id="frmlist" style="width:90%;background-color:<?=$farbschema["WEB7"]?>;border:1px solid <?=$farbschema["WEB12"]?>;font-size:12px;visibility:hidden;position:absolute;right:0px;top:3px;padding:2px;z-index:100;">
<?php

if($groupdat["multiframelist"][$session["group_id"]]){
	foreach ($groupdat["multiframelist"][$session["group_id"]] as $key => $value){
		if($value == $session["multiframe"]){
			echo "&nbsp;<span style=\"color:green;\">".lmb_substr($value,0,lmb_strlen($value)-4)."</span><BR>";
		}else{
			echo "&nbsp;<span style=\"color:".$farbschema["WEB12"].";\" OnClick=\"change_frame('".rawurlencode($value)."')\" style=\"cursor:pointer;\" onmouseout=\"this.style.fontWeight='normal';\" onmouseover=\"this.style.fontWeight='bold';\">".lmb_substr($value,0,lmb_strlen($value)-4)."</span><BR>";
		}
	}
}

?>
</div>


<div id="lmbAjaxContainer" class="ajax_container" style="position:absolute;visibility:hidden;" OnClick="activ_menu=1;"></div>
<table id="framecontext" cellpadding="0" cellspacing="0" style="width:100%;height:100%" OnContextMenu="return hide_frame();"><tr><td valign="top" class="lmbfringeFrameMultiframe" onmousedown="lmbIniDrag(event,this,'lmbResizeFrame')">

<div style="clear:both;height:15px;"></div>

<?php


$defaultExpandMenu = "none";

$key3 = 0;
foreach($menu as $key1 => $menuType){

	echo "<div id=\"" . $key1 . "\" style=\"$name\">";

	$name = "display:none;";
	

	if($key1 == 110){
		$menuTmp["name"] = "Forms";
		$menuTmp["child"] = $menuType;
		unset($menuType);
		$menuType[] = $menuTmp;
	}elseif($key1 == 21){
		$menuTmp["name"] = "Profile";
		$menuTmp["child"] = $menuType;
		unset($menuType);
		$menuType[] = $menuTmp;
	}

	foreach ($menuType as $key2 => $firstLevel) {
		
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"lmbfringeMenuNav\">";

		if($firstLevel["preview"]){
			echo "<input type=\"hidden\" id=\"autorefreshPreviewWorkflow_".$firstLevel["id"]."\" VALUE=\"".$firstLevel["autorefresh"]."\">";
			echo "<input type=\"hidden\" name=\"multiframeType_".$firstLevel["preview"]."\" value=\"".$firstLevel["id"]."\">";
			$preview = ",'" . $firstLevel["preview"] . "'";
		}else{
			$preview = "";
		}


		if($firstLevel["link"]){
			if($firstLevel["target"]){
				$onClick = "onClick = \"parent." . $firstLevel["target"] . ".location.href = '" . $firstLevel["link"] ."'\"";
			}else{
				if(lmb_substr($firstLevel["link"],0,11)=="javascript:"){
					$onClick = "onClick=" . $firstLevel["link"];
				}else{
					$onClick = "onClick=\"parent.main.location.href = '" . $firstLevel["link"] . "'\"";
				}
			}

			$cursor = "cursor:pointer;";
		}else{
			$cursor = "";
		}


		echo "<tr><td class=\"lmbMenuHeaderNav\" $onClick>";
		
		if($firstLevel["gicon"]){
			echo "<i class=\"lmbMenuHeaderImage lmb-icon ".$firstLevel["gicon"]."\" style=\"position:absolute;margin-left:5px;\"></i>";
		}

		// default event is "hideShow"
		$event = "hideShow";
		if($firstLevel["event"] != null){
			$event = $firstLevel["event"];
		}
		
		echo "<div style=\"float:right;margin-right:0.5em;\" onclick=\"$event(event,'".$firstLevel["id"]."','".$firstLevel["name"]."','".$firstLevel["gtabid"]."','".$firstLevel["params"]."'$preview,1);var event = arguments[0] || window.event; event.stopPropagation();\">";
		if($firstLevel["child"] || $firstLevel["preview"] || $firstLevel["event"]){
			echo "<i id=\"HS" . $firstLevel["id"] . "\" class=\"lmb-icon lmb-angle-down\" valign=\"top\" style=\"cursor:pointer\"></i>";
		}
		echo "</div>
		<div class=\"lmbMenuItemHeaderNav\" style=\"$cursor\" $onClick>".$firstLevel["name"]."</div>
		<div style=\"clear:both;height:1px;overflow:hidden\"></div>
		";
		

		echo "</td></tr>
		<tr>
		<td><div class=\"lmbMenuHeaderNavContent\" id=\"CONTENT_" . $firstLevel["id"] . "\" style=\"display:none\">
		<div class=\"lmbMenuBodyNav\" id=\"limbasDivMultiframePreview" . $firstLevel["id"] . "\" style=\"width:100%;\">&nbsp;</div>
		
		<table width=\"100%\">
		<tr><td style=\"height:5px;overflow:hidden\"></td></tr>
		</table>

		</div></td>
		</tr>
		</table>";
		
		echo "\n<Script language=\"JavaScript\">\n";
		echo "var refreshPreview".$firstLevel["id"].";\n";
		if($menu_setting["menu"][$firstLevel["name"]]){echo "autoopen[$key3] = \"$event(null,'".$firstLevel["id"]."','".$firstLevel["name"]."','".$firstLevel["gtabid"]."','".$firstLevel["params"]."'$preview,'0');\";\n";}
		$key3++;
		echo "</Script>\n";
		
		
	}
	echo  "</div>";
}



echo "</td></tr></table>";
?>

</div>