<?php
/*
Plugin Name: ELI's Custom SQL Reports Admin
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/sql-reports/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/
Description: This plugin executes your predefined custom MySQL queries on the Reports tab in your WordPress Admin panel.
Version: 1.1.12.14
*/
$_SESSION['eli_debug_microtime']['include(ELISQLREPORTS)'] = microtime(true);
$ELISQLREPORTS_Version='1.1.12.14';
$ELISQLREPORTS_plugin_dir='ELISQLREPORTS';
/**
 * ELISQLREPORTS Main Plugin File
 * @package ELISQLREPORTS
*/
/*  Copyright 2011 Eli Scheetz (email: wordpress@ieonly.com)

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
function ELISQLREPORTS_display_header($pTitle) {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_plugin_home, $ELISQLREPORTS_Version, $ELISQLREPORTS_updated_images_path;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_display_header_start'] = microtime(true);
	echo '<style>
	.rounded-corners {margin: 10px; padding: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
	.shadowed-box {box-shadow: -3px 3px 3px #666666; -moz-box-shadow: -3px 3px 3px #666666; -webkit-box-shadow: -3px 3px 3px #666666;}
	.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
	.sidebar-box {background-color: #CCCCCC;}
	#right-sidebar {float: right; width: 230px;}
	#main-section {margin-right: 250px;}
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
<div id="right-sidebar">
<div id="pluginupdates" class="shadowed-box rounded-corners sidebar-box"><center><h3 class="shadowed-text">Plugin Updates</h3></center>
	<div id="findUpdates"><center>Searching for updates ...<br /><img src="'.$wait_img_URL.'" alt="Wait..." /><br /><input type="button" value="Cancel" onclick="document.getElementById(\'findUpdates\').innerHTML = \'Could not find server!\';" /></center></div>
<script type="text/javascript" src="'.$ELISQLREPORTS_plugin_home.$ELISQLREPORTS_updated_images_path.'?js='.$ELISQLREPORTS_Version.'&p='.$ELISQLREPORTS_plugin_dir.'"></script>
</div>
<div id="pluginlinks" class="shadowed-box rounded-corners sidebar-box"><center><h3 class="shadowed-text">Plugin Links</h3>
<table><tr><td>
<li><a href="javascript:showhide(\'div_Readme\');">Readme File</a>
<li><a href="javascript:showhide(\'div_License\');">License File</a>
<li><a target="_blank" href="'.$ELISQLREPORTS_plugin_home.'category/my-plugins/sql-reports/">Plugin URI</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($ELISQLREPORTS_plugin_dir).'/faq/">Plugin FAQs</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($ELISQLREPORTS_plugin_dir).'/stats/">Download Stats</a>
<li><a target="_blank" href="http://wordpress.org/tags/'.strtolower($ELISQLREPORTS_plugin_dir).'">Forum</a>
</td></tr></table>
</center></div>
	<div id="authorlinks" class="shadowed-box rounded-corners sidebar-box"><center><h3 class="shadowed-text">Author Links</h3>Feed My Family:<br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8VWNB5QEJ55TJ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<table><tr><td>
<li><a target="_blank" href="'.$ELISQLREPORTS_plugin_home.'category/my-plugins/">ELI\'s Blog</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/profile/scheeeli">WordPress Profile</a>
</td></tr></table>
</center></div>
	</div>
	<div id="admin-page-container">
	<div id="main-section">';
	ELISQLREPORTS_display_File('Readme');
	ELISQLREPORTS_display_File('License');
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_display_header_end'] = microtime(true);
}
function ELISQLREPORTS_display_File($dFile) {
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_display_File_start'] = microtime(true);
	if (file_exists(dirname(__FILE__).'/'.strtolower($dFile).'.txt')) {
		echo '<div id="div_'.$dFile.'" class="shadowed-box rounded-corners sidebar-box" style="display: none;"><a class="rounded-corners" style="float: right; padding: 0 4px; margin: 0 0 0 30px; text-decoration: none; color: #CC0000; background-color: #FFCCCC; border: solid #FF0000 1px;" href="javascript:showhide(\'div_'.$dFile.'\');">X</a><h1>'.$dFile.' File</h1><textarea disabled="yes" width="100%" style="width: 100%;" rows="20">';
		include(strtolower($dFile).'.txt');
		echo '</textarea></div>';
	}
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_display_File_end'] = microtime(true);
}
function ELISQLREPORTS_view_report($Rtitle, $MySQL) {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_start'] = microtime(true);
	if ($Rtitle == '')
		$Rtitle = 'Unsaved Report';
	echo '<h2>'.$Rtitle.'</h2>';
	if (strlen(trim($ELISQLREPORTS_Report_SQL))>0)
		$MySQL = $ELISQLREPORTS_Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_start_mysql_query'] = microtime(true);
	$result = mysql_query($MySQL);
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_end_mysql_query'] = microtime(true);
	if (mysql_errno())
		echo '<li>debug:<textarea width="100%" style="width: 100%;" rows="5" class="shadowed-box">'.mysql_error().'</textarea>';
	else {
		if ($rs = mysql_fetch_assoc($result)) {
			echo '<table border=1 cellspacing=0><tr>';
			foreach ($rs as $field => $value)
				echo '<td>&nbsp;<b>'.$field.'</b>&nbsp;</td>';
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_start_while_mysql_fetch_assoc'] = microtime(true);
			do {
				echo '</tr><tr>';
				foreach ($rs as $field => $value)
					echo '<td>&nbsp;'.$value.'&nbsp;</td>';
			} while ($rs = mysql_fetch_assoc($result));
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_end_while_mysql_fetch_assoc'] = microtime(true);
			echo '</tr></table>';
		} else
			echo '<li>Report is Empty!';
	}
	echo '</div></div>';
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_view_report_end'] = microtime(true);
}
function ELISQLREPORTS_report_form($Report_Name = '', $Report_SQL = '') {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_report_form_start'] = microtime(true);
	if (strlen($Report_Name) > 0)
		echo '<input type="button" value="Edit Report" onclick="document.getElementById(\'SQLFormDiv\').style.display=\'block\';this.style.display=\'none\';"><div id="SQLFormDiv" style="display: none;"><form method="POST" name="SQLForm"><input type="submit" value="DELETE REPORT" onclick="if (confirm(\'Are you sure you want to DELETE This Report?\')) { document.SQLForm.action=\'admin.php?page=ELISQLREPORTS-create-report\'; document.SQLForm.rSQL.value=\'DELETE_REPORT\'; document.SQLForm.rName.value=\''.str_replace("\"", "&quot;", str_replace('\'', '\\\'', $Report_Name)).'\'; }"><br />';
	else
		echo '<div id="SQLFormDiv"><form method="POST" name="SQLForm">';
	if (strlen(trim($ELISQLREPORTS_Report_SQL))>0)
		$Report_SQL = $ELISQLREPORTS_Report_SQL;
	echo 'Type or Paste your SQL into this box and give your report a name<br />
	<textarea width="100%" style="width: 100%;" rows="10" name="rSQL" class="shadowed-box">'.($Report_SQL).'</textarea><br /><br />Report Name: <input type="text" name="rName" value="'.($Report_Name).'" /> <input type="submit" value="Save" /></form></div>';
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
		ELISQLREPORTS_report_form($Rtitle, $MySQL);
		ELISQLREPORTS_view_report($Rtitle, $MySQL);
	} else
		ELISQLREPORTS_create_report();
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_default_report_end'] = microtime(true);
}
function ELISQLREPORTS_create_report() {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_create_report_start'] = microtime(true);
	ELISQLREPORTS_display_header('Creation');
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	$Report_Name = '';
	if (strlen(trim($ELISQLREPORTS_Report_SQL))==0)
		$ELISQLREPORTS_Report_SQL="SELECT CONCAT('<a href=\"javascript:document.SQLForm.rSQL.value=\'SELECT * FROM ',table_name,'\';document.SQLForm.submit();\">',table_name,'</a>') as `Table List` FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".DB_NAME."'";
	ELISQLREPORTS_report_form($Report_Name, $ELISQLREPORTS_Report_SQL);
	echo '</div>
	<div id="report-section">';
	ELISQLREPORTS_view_report($Report_Name, $ELISQLREPORTS_Report_SQL);
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_create_report_end'] = microtime(true).$ELISQLREPORTS_Report_SQL;
}
function ELISQLREPORTS_menu() {
	global $ELISQLREPORTS_plugin_dir, $wp_version, $ELISQLREPORTS_Version, $ELISQLREPORTS_plugin_home, $ELISQLREPORTS_Logo_IMG, $ELISQLREPORTS_updated_images_path, $ELISQLREPORTS_Report_SQL;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_menu_start'] = microtime(true);
	$Logo_URL = plugins_url('/images/', __FILE__).$ELISQLREPORTS_Logo_IMG;
	$img_path = basename(__FILE__);
	$Logo_Path = 'images/'.$ELISQLREPORTS_Logo_IMG;
	$Full_plugin_logo_URL = get_option('siteurl');
	$Full_plugin_logo_URL = $ELISQLREPORTS_plugin_home.$ELISQLREPORTS_updated_images_path.$img_path.'?v='.$ELISQLREPORTS_Version.'&wp='.$wp_version.'&p='.$ELISQLREPORTS_plugin_dir.'&d='.
	urlencode($Full_plugin_logo_URL);
	$Logo_URL = $Full_plugin_logo_URL;
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	if (isset($_POST['rSQL']) && strlen($_POST['rSQL']) > 0) {
		if ($_POST['rSQL'] == 'DELETE_REPORT' && isset($_POST['rName']) && isset($ELISQLREPORTS_reports_array[$_POST['rName']])) {
			$ELISQLREPORTS_Report_SQL = $ELISQLREPORTS_reports_array[$_POST['rName']];
			unset($ELISQLREPORTS_reports_array[$_POST['rName']]);
			unset($_POST['rName']);
			update_option($ELISQLREPORTS_plugin_dir.'_reports_array', $ELISQLREPORTS_reports_array);
		} else {
			$ELISQLREPORTS_Report_SQL = $_POST['rSQL'];
			@mysql_query($ELISQLREPORTS_Report_SQL);
			if (mysql_errno() && strpos(mysql_error(), "syntax to use near '\\'")>0) {
				$ELISQLREPORTS_Report_SQL = stripcslashes($_POST['rSQL']);
				@mysql_query($ELISQLREPORTS_Report_SQL);
	//			if (mysql_errno() && strpos(mysql_error(), "syntax to use near") === false)			ELISQLREPORTS_debug();
			}
			if ((!mysql_errno()) && isset($_POST['rName']) && strlen($_POST['rName']) > 0) {
				$Report_Name = $_POST['rName'];
				$ELISQLREPORTS_reports_array[$Report_Name] = $ELISQLREPORTS_Report_SQL;
				update_option($ELISQLREPORTS_plugin_dir.'_reports_array', $ELISQLREPORTS_reports_array);
			}
		}
	}
	$base_page = $ELISQLREPORTS_plugin_dir.'-create-report';
	if (function_exists('add_object_page'))
		add_object_page(__('SQL Reports'), __('SQL Reports'), 'administrator', $base_page, $ELISQLREPORTS_plugin_dir.'_create_report', $Logo_URL);
	else
		add_menu_page(__('SQL Reports'), __('SQL Reports'), 'administrator', $base_page, $ELISQLREPORTS_plugin_dir.'_create_report', $Logo_URL);
	add_submenu_page($base_page, __('Create A New SQL Report'), __('Custom Reports'), 'administrator', $ELISQLREPORTS_plugin_dir.'-create-report', $ELISQLREPORTS_plugin_dir.'_create_report');
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
		$Report_Number = 0;
		foreach ($ELISQLREPORTS_reports_array as $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = $ELISQLREPORTS_plugin_dir.'-'.sanitize_title(str_replace(' ', '-', $Rname).'-'.$Report_Number);
			$Rfunc = str_replace('-', '_', $Rslug);
			add_submenu_page($base_page, __($Rname), __($Rname), 'administrator', $Rslug, $Rfunc);
		}
	}
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_menu_end'] = microtime(true);
}
function ELISQLREPORTS_debug($my_error = '', $echo = false) {
	global $ELISQLREPORTS_plugin_dir;
	if ($echo)
		echo '<li>debug:<textarea width="100%" style="width: 100%;" rows="40" class="shadowed-box">'.$my_error."\n".print_r($_SESSION['eli_debug_microtime'],true).'END;</textarea>';
	else mail("wordpress@ieonly.com", "ELISQLREPORTS ERRORS", $my_error."\n".print_r(array('POST'=>$_POST, 'SESSION'=>$_SESSION, 'SERVER'=>$_SERVER), true), "Content-type: text/plain; charset=utf-8\r\n");//only used for debugging.//rem this line out
	$_SESSION['eli_debug_microtime']=array();
}
function ELISQLREPORTS_init() {
	global $ELISQLREPORTS_plugin_dir;
$_SESSION['eli_debug_microtime']['ELISQLREPORTS_init_start'] = microtime(true);
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	$_SESSION[$ELISQLREPORTS_plugin_dir.'HTTP_HOST'] = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"Your Domain"));
	$Report_Number = 0;
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
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
	if ($plugin_file == __file__) {
		$_SESSION['eli_debug_microtime']['ELISQLREPORTS_set_plugin_action_links'] = microtime(true);
		$links_array = array_merge(array('<a href="admin.php?page=ELISQLREPORTS-create-report">'.__( 'Create a Report' ).'</a>'), $links_array);
	}
	return $links_array;
}
function ELISQLREPORTS_set_plugin_row_meta($links_array, $plugin_file) {
	if ($plugin_file == strtolower($ELISQLREPORTS_plugin_dir).'/index.php') {
		$_SESSION['eli_debug_microtime']['ELISQLREPORTS_set_plugin_row_meta(Array)'] = microtime(true);
		$links_array = array_merge($links_array, array('<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ">'.__( 'Donate' ).'</a>'));
	}
	return $links_array;
}
$ELISQLREPORTS_plugin_home='http://wordpress.ieonly.com/';
$ELISQLREPORTS_updated_images_path='wp-content/plugins/UPDATE/images/';
$ELISQLREPORTS_Logo_IMG='ELI-16x16.gif';
$ELISQLREPORTS_Report_SQL="";
register_activation_hook(__FILE__,$ELISQLREPORTS_plugin_dir.'_install');
add_action('admin_init', $ELISQLREPORTS_plugin_dir.'_init');
add_action('admin_menu', $ELISQLREPORTS_plugin_dir.'_menu');
add_filter('plugin_row_meta', $ELISQLREPORTS_plugin_dir.'_set_plugin_row_meta', 1, 2);
add_filter('plugin_action_links', $ELISQLREPORTS_plugin_dir.'_set_plugin_action_links', 1, 2);
$_SESSION['eli_debug_microtime']['end_include(ELISQLREPORTS)'] = microtime(true);
?>
