<?php

/* --- test location of main.php --- */
if(file_exists($setup_path_project."/main.php")){$msg['func_path'] = $msgOK;$msic[func_path] = "1";}else{$msg['func_path'] = $msgError;$msic[func_path] = "3";$commit = 1;}

/* --- imagedestroy --- */
if(function_exists("imagedestroy")){
    $msg[func_gd] = $msgOK;$msic[func_gd] = "1";
    $gbsupport = gd_info();
    $msg[func_gd_version] = "<font color=\"blue\">".$gbsupport['GD Version']."</font>";
    $msg[func_gd_freetype_support] = ($gbsupport['FreeType Support']) ? $msgOK : $msgError;
    $msg[func_gd_freetype_linkage] = $gbsupport['FreeType Linkage'];
    $msg[func_gd_gif_read] = ($gbsupport['GIF Read Support']) ? $msgOK : $msgError;
    $msg[func_gd_gif_create] = ($gbsupport['GIF Create Support']) ? $msgOK : $msgError;
    $msg[func_gd_jpg] = ($gbsupport['JPG Support'] OR $gbsupport['JPEG Support']) ?$msgOK : $msgError;
    $msg[func_gd_png] = ($gbsupport['PNG Support']) ? $msgOK : $msgError;
}else{
    $msg[func_gd] = $msgError;$msic[func_gd] = "3";$commit = 1;
}

/* --- imagemagick --- */
chdir($setup_path_project."/admin/install/");

$cmd = "convert --version";
$msg["func_imv"] = explode("\n",`$cmd`);
$msg["func_imv"] = $msg["func_imv"][0];

$cmd = "convert -auto-orient -thumbnail 'x30>' -gravity center -extent x30 ".
    $setup_path_project."/admin/install/test.jpg ".
    $setup_path_project."/TEMP/test.png";

$func_im = `$cmd 2>/dev/null`;

if(is_file($setup_path_project."/TEMP/test.png")){
    $msg['func_im'] = "<font color=\"blue\">".$msg['func_imv']."</FONT>";
    $msic[func_im] = "1";
} else if($msg['func_imv']){
    $msg['func_im'] = $msgWarnHeavy . " (version: ".
    $msg['func_imv'].")<br>V 6.3.x or higher needed!";
    $msic[func_im] = "4";
} else{
    $msg['func_im'] = $msgError;
    $msic[func_im] = "3";
    $commit = 1;
}

if(file_exists($setup_path_project."/TEMP/test.png")){
    unlink($setup_path_project."/TEMP/test.png");
}

/* --- imap, gzip, mimemagick, zip --- */
if(function_exists("imap_open")){$msg[func_imap] = $msgOK;$msic[func_imap] = "1";}else{$msg[func_imap] = $msgWarnHeavy;$msic[func_imap] = "4";$commit = 2;}
if(function_exists("gzopen")){$msg[func_zlib] = $msgOK;$msic[func_zlib] = "1";}else{$msg[func_zlib] = $msgWarnHeavy;$msic[func_zlib] = "4";$commit = 2;}
if(function_exists("mime_content_type")){$msg[func_mime] = $msgOK;$msic[func_mime] = "1";}else{$msg[func_mime] = $msgWarnHeavy;$msic[func_mime] = "4";$commit = 2;}

$out = exec("zip");
if($out){$msg[func_zip] = $msgOK;$msic[func_zip] = "1";}else{$msg[func_zip] = $msgWarnHeavy;$msic[func_zip] = "4";$commit = 2;}

/* --- htmldoc --- */
$cmd = "htmldoc --size 295x210mm --left 0mm --right 0mm --top 0mm --bottom 0mm --webpage --header ... --footer ... -f $setup_path_project/TEMP/test.pdf $setup_path_project/admin/install/test.html";
exec($cmd);
$cmd = "htmldoc --version";
$msg["func_htmldoc"] = `$cmd`;
if(is_file($setup_path_project."/TEMP/test.pdf")){$msg[func_htmldoc] = "<font color=\"blue\">version: ".$msg[func_htmldoc]."</FONT>";$msic[func_htmldoc] = "1";}else{$msg[func_htmldoc] = "<font color=\"red\">FALSE</FONT>";$msic[func_htmldoc] = "4";$commit = 2;}
if(file_exists($setup_path_project."/TEMP/test.pdf")){unlink($setup_path_project."/TEMP/test.pdf");}

/* --- pdftotext --- */                        
$cmd = "pdftotext ".$setup_path_project."/admin/install/test.pdf ".$setup_path_project."/TEMP/test.txt";
exec($cmd);
if(is_file($setup_path_project."/TEMP/test.txt")){$msg[func_pdftotext] .= $msgOK;$msic[func_pdftotext] = "1";}else{$msg[func_pdftotext] .= $msgWarnHeavy;$msic[func_pdftotext] = "4";$commit = 2;}
if(file_exists($setup_path_project."/TEMP/test.txt")){unlink($setup_path_project."/TEMP/test.txt");}

/* --- pdftohtml --- */
$cmd = "pdftohtml ".$setup_path_project."/admin/install/test.pdf ".$setup_path_project."/TEMP/test.html";
exec($cmd);
if(is_file($setup_path_project."/TEMP/test.html")){$msg[func_pdftohtml] .= $msgOK;$msic[func_pdftohtml] = "1";}else{$msg[func_pdftohtml] .= $msgWarnHeavy;$msic[func_pdftohtml] = "4";$commit = 2;}
if(file_exists($setup_path_project."/TEMP/test.html")){unlink($setup_path_project."/TEMP/test.html");unlink($setup_path_project."/TEMP/tests.html");unlink($setup_path_project."/TEMP/test_ind.html");}

/* --- exiftool --- */
$cmd = "exiftool -ver";
$exiftool = `$cmd`;
$exiftoolVer = explode('.',$exiftool);
if($exiftool AND $exiftoolVer[0] >= 9 ){$msg[func_exiftool] .= "<font color=\"blue\">$exiftool</font> " . $msgOK;$msic[func_exiftool] = "1";}else{$msg[func_exiftool] .= $msgWarnHeavy . " (V.$exiftool < 9)";$msic[func_exiftool] = "4";$commit = 2;}

/* --- ghostscript --- */
$cmd = "cd ".$setup_path_project."/admin/install/; gs -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=".$setup_path_project."/TEMP/test.pdf test.pdf";
exec($cmd);
if(is_file($setup_path_project."/TEMP/test.pdf")){$msg[func_ghost] .= $msgOK;$msic[func_ghost] = "1";}else{$msg[func_ghost] .= $msgWarnHeavy;$msic[func_ghost] = "4";$commit = 2;}
if(file_exists($setup_path_project."/TEMP/test.pdf")){unlink($setup_path_project."/TEMP/test.pdf");}

/* --- ttf2pt1 --- */
#chdir($setup_path_project."/TEMP");
#$cmd = "ttf2pt1 -a ".$setup_path_project."/admin/install/airmole.ttf airmole";
#exec($cmd);
#if(is_file($setup_path_project."/TEMP/airmole.afm") AND is_file($setup_path_project."/TEMP/airmole.t1a")){$msg[func_ttf2pt1] .= $msgOK;$msic[func_ttf2pt1] = "1";}else{$msg[func_ttf2pt1] .= $msgWarnHeavy;$msic[func_ttf2pt1] = "4";$commit = 2;}
#if(file_exists($setup_path_project."/TEMP/airmole.afm")){unlink($setup_path_project."/TEMP/airmole.afm");}
#if(file_exists($setup_path_project."/TEMP/airmole.t1a")){unlink($setup_path_project."/TEMP/airmole.t1a");}

/* --- php odbc --- */
if(extension_loaded('odbc')) {
    $msic[func_php_odbc] = "1";
    $msg[func_php_odbc] = $msgOK;
} else {
    $msic[func_php_odbc] = "3";
    $msg[func_php_odbc] = $msgError;
    $commit = 1;
}
?>

<table class="table table-condensed">
    <thead>
        <tr>
            <th colspan="2">
                Functions
                <a href="http://www.limbas.org/wiki/Tools" target="new"><i class="lmb-icon lmb-help"></i></a>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr><?= insIcon($msic[func_php_odbc]); ?><td>php_odbc</td><td><?= $msg[func_php_odbc] ?></td></tr>
        <tr><?= insIcon($msic[func_path]); ?><td>path</td><td><?= $msg[func_path] ?></td></tr>
        <tr><?= insIcon($msic[func_gd]); ?><td>gdlib</td><td><?= $msg[func_gd] ?></td></tr>
        
        <?php if($msic[func_gd] != '3') { ?>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GD Version</td><td><?= $msg[func_gd_version] ?></td></tr>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Freetype Support for gd</td><td><?= $msg[func_gd_freetype_support] ?></td></tr>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Freetype Linkage</td><td><?= $msg[func_gd_freetype_linkage] ?></td></tr>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gif Read Support</td><td><?= $msg[func_gd_gif_read] ?></td></tr>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gif Create Support</td><td><?= $msg[func_gd_gif_create] ?></td></tr>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JPG Support</td><td><?= $msg[func_gd_jpg] ?></td></tr>
            <tr><td></td><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PNG Support</td><td><?= $msg[func_gd_png] ?></td></tr>
        <?php } ?>
        
        <tr><?= insIcon($msic[func_im]); ?><td>ImageMagick</td><td><?= $msg[func_im] ?></td></tr>
        <tr><?= insIcon($msic[func_imap]); ?><td>Imap</td><td><?= $msg[func_imap] ?></td></tr>
        <tr><?= insIcon($msic[func_zip]); ?><td>zip</td><td><?= $msg[func_zip] ?></td></tr>
        <tr><?= insIcon($msic[func_zlib]); ?><td>Zlib</td><td><?= $msg[func_zlib] ?></td></tr>
        <tr><?= insIcon($msic[func_mime]); ?><td>MimeMagic</td><td><?= $msg[func_mime] ?></td></tr>
        <tr><?= insIcon($msic[func_htmldoc]); ?><td>htmldoc</td><td><?= $msg[func_htmldoc] ?></td></tr>
        <tr><?= insIcon($msic[func_pdftotext]); ?><td>pdftotext (Xpdf)</td><td><?= $msg[func_pdftotext] ?></td></tr>
        <tr><?= insIcon($msic[func_pdftohtml]); ?><td>pdftohtml</td><td><?= $msg[func_pdftohtml] ?></td></tr>
        <tr><?= insIcon($msic[func_ghost]); ?><td>ghostscript</td><td><?= $msg[func_ghost] ?></td></tr>
        <tr><?= insIcon($msic[func_exiftool]); ?><td>exiftool</td><td><?= $msg[func_exiftool] ?></td></tr>
    </tbody>
</table>

<div>
    <button type="button" class="btn btn-default" onclick="switchToStep('<?= array_keys($steps)[1] ?>')">Back</button>
    <?php 
    $nextStep = 3;
    $text = "Next step";
    if($commit == 1) {
        $tooltip = "title=\"All required functions must work before continuing!\"";
        $disabled = "disabled";
        $nextStep--;
        $text = "Reload";
    }
    ?>
    <button type="submit" class="btn btn-info pull-right <?= $disabled ?>" <?= $tooltip ?> name="install" value="<?= array_keys($steps)[$nextStep] ?>"><?= $text ?></button>
</div>
