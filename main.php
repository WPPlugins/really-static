<?php

/*

Plugin Name: really static
Plugin URI: http://www.sorben.org/really-static/index.html
Description:  Make your Blog really static!
Author: Erik Sefkow
Version: 0.23
Author URI: http://www.sorben.org/
*/
/*  Copyright 2009  Erik Sefkow  (email : reallystatic@sorben.org)

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
global $rs_version;
$rs_version="0.23";


if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('Get it here: <a title="really static wordpress plugin" href="http://www.sorben.org/really-static/">really static wordpress plugin</a>'); }


 

			$currentLocale = get_locale();
		#	echo $currentLocale;
			if(!empty($currentLocale)) {
				$moFile = dirname(__FILE__) . "/reallystatic-" . $currentLocale . ".mo";
				#echo $moFile;
				if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('reallystatic', $moFile);
				#if(@file_exists($moFile) && is_readable($moFile))echo "LOADED";
			}

global $test;

$test=false;

$rand = substr( md5( md5( DB_PASSWORD ) ), -5 );
$logfile = ABSPATH.'wp-content/plugins/really-static/log.html';
function getothers($when){
	if($when=="everyday" ){
		return get_option('realstaticeveryday');
	}elseif($when=="everytime"){
		return get_option('realstaticeverytime');
	}elseif($when=="posteditcreatedelete"){
		return get_option('realstaticposteditcreatedelete');


	}

}
////////////////////////////////////
# for debugging
function RS_log($line)
{
	global $logfile;

	if($line===false){$fh = @fopen($logfile, "w+");@fwrite($fh,"<pre>");@fclose($fh);return;}
	$fh = @fopen($logfile, "a+");
	@fwrite($fh, current_time('mysql').": ".$line."\r\n");
	@fclose($fh);
}

function loaddaten($name) {
	if ($name == "localpath")
	$name = "realstaticlocalpath";
	if ($name == "subpfad")
	$name = "realstaticsubpfad";
	if ($name == "localurl")
	$name = "realstaticlocalurl";
	if ($name == "remotepath")
	$name = "realstaticremotepath";
	if ($name == "remoteurl")
	$name = "realstaticremoteurl";

	if (get_option ( $name ) === false) {
		$h="";
		add_option ( 'realstaticlocalpath', $_SERVER["DOCUMENT_ROOT"]."/".$h, '', 'yes' );
		add_option ( 'realstaticsubpfad', $h, '', 'yes' );
		add_option ( 'realstaticlocalurl', get_option('home').'/', '', 'yes' );

		add_option ( 'realstaticremotepath', "/", '', 'yes' );
		add_option ( 'realstaticremoteurl', "http://www.example.com/", '', 'yes' );

		add_option ( 'realstaticftpserver', "www.server.de", '', 'yes' );
		add_option ( 'realstaticftpuser', "name", '', 'yes' );
		add_option ( 'realstaticftppasswort', "pw", '', 'yes' );
		add_option ( 'realstaticdesignlocal', 'http://'.$_SERVER["HTTP_HOST"]."/".$h.'wp-content/themes/default/', '', 'yes' );
		add_option ( 'realstaticdesignremote', "http://www.example.com/design/", '', 'yes' );
		add_option('realstaticposteditcreatedelete',array("sitemap.xml","feed","comments/feed","feed/atom","feed/rss"),'','');
		add_option('realstaticpageeditcreatedelete',array("sitemap.xml"),'','');
		add_option('realstaticcommenteditcreatedelete',array(),'','');
		add_option('realstaticeveryday',array(),'','');
		add_option('realstaticeverytime',array(),'','');
	}
	return get_option ( $name );

}
function realstatic_conf_save() {
	update_option ( 'realstaticlocalpath', $_POST ['realstaticlocalpath'] );
	update_option ( 'realstaticlocalurl', $_POST ['realstaticlocalurl'] );
	update_option ( 'realstaticsubpfad', $_POST ['realstaticsubpfad'] );
	update_option ( 'realstaticremotepath', $_POST ['realstaticremotepath'] );
	update_option ( 'realstaticremoteurl', $_POST ['realstaticremoteurl'] );
	update_option ( 'realstaticftpserver', $_POST ['realstaticftpserver'] );
	update_option ( 'realstaticftpuser', $_POST ['realstaticftpuser'] );
	update_option ( 'realstaticftppasswort', $_POST ['realstaticftppasswort'] );
	update_option ( 'realstaticdesignlocal', $_POST ['realstaticdesignlocal'] );
	update_option ( 'realstaticdesignremote', $_POST ['realstaticdesignremote'] );
	update_option ( 'realstaticrefreshallac', $_POST ['refreshallac'] );
	update_option ( 'realstaticnonpermanent', $_POST ['nonpermanent'] );

}

function installrealstaic() {
	global $wpdb;
	if (get_option ( "realstaticisinstalled2" ) === false) {
		add_option ( 'realstaticisinstalled2', "1", '', 'yes' );

		$sql = "CREATE TABLE `" . $wpdb->prefix . "realstatic` (
		`url` VARCHAR( 150 ) NOT NULL ,
		`datum` INT(11) NOT NULL ) ;";

		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta ( $sql );


		include("autoinstall.php");
		// autoinstall
		if($autoinstall==true){
			add_option ( 'realstaticlocalpath', $realstaticlocalpath, '', 'yes' );
			add_option ( 'realstaticsubpfad', $realstaticsubpfad, '', 'yes' );
			add_option ( 'realstaticlocalurl', $realstaticlocalurl, '', 'yes' );

			add_option ( 'realstaticremotepath', $realstaticremotepath, '', 'yes' );
			add_option ( 'realstaticremoteurl', $realstaticremoteurl, '', 'yes' );

			add_option ( 'realstaticftpserver', $realstaticftpserver, '', 'yes' );
			add_option ( 'realstaticftpuser', $realstaticftpuser, '', 'yes' );
			add_option ( 'realstaticftppasswort', $realstaticftppasswort, '', 'yes' );
			add_option ( 'realstaticdesignlocal', $realstaticdesignlocal, '', 'yes' );
			add_option ( 'realstaticdesignremote', $realstaticdesignremote, '', 'yes' );
			add_option('realstaticposteditcreatedelete',$realstaticposteditcreatedelete,'','');
			add_option('realstaticpageeditcreatedelete',$realstaticpageeditcreatedelete,'','');
			add_option('realstaticcommenteditcreatedelete',$realstaticcommenteditcreatedelete,'','');
			add_option('realstaticeveryday',$realstaticeveryday,'','');
			add_option('realstaticeverytime',$realstaticeverytime,'','');
		}
	}
}
add_action ( 'comment_post', 'komentar2', 550	 );
add_action ( 'delete_comment', 'komentar3', 550 );
function komentar3($id){
	global $killcoment;
	global $wpdb;
	$querystr = "SELECT comment_post_ID  as outo FROM " . $wpdb->prefix . "comments WHERE comment_ID  = '$id'";
	$anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
	$killcoment=$anzneueralsdieser [0]->outo;
	return $id;
}
add_action ( 'wp_set_comment_status', 'komentar', 550 );

add_action ( 'edit_comment', 'komentar', 550 );
add_action ( 'wp_update_comment_count', 'lala', 550 );
function lala(){
	global $iscomment;
	$iscomment=true;

}
add_action('publish_post',   'renewrealstaic');
add_action('edit_post',      'renewrealstaic',999);
add_action('delete_post',    'delete_post');
add_action('deleted_post',    'delete_post');

function delete_post($id) {
	global $deleteingpost;
	$deleteingpost[]=get_page_link ( $id ); #seite selber
}
function komentar2($id) {
	#	echo "KOMENTAR";
	$pid = komentar ( $id );
	header ( "Location: " . nonpermanent(str_replace ( loaddaten ( "localurl" ), loaddaten ( "remoteurl" ), get_permalink($pid) ) ."#comment-$id"));
	exit ();
}

function komentar($id) {
	#echo "kommentar $id";
	global $notagain;
	if(isset($notagain[$id]))return;
	$notagain[$id]=1;
	global $wpdb;
#spamtest
#        $querystr = "SELECT comment_post_ID  as outo FROM " . $wpdb->prefix . "comments WHERE comment_ID  = '$id'";
#        $anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
 
#        $li=$anzneueralsdieser [0]->outo;

####
	$querystr = "SELECT comment_post_ID  as outo, comment_approved as wixer FROM " . $wpdb->prefix . "comments	WHERE comment_ID  = '$id'";
	$anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
	global $killcoment;
if($anzneueralsdieser [0]->wixer!=1)return;
	$li=$anzneueralsdieser [0]->outo;
	if(isset($killcoment)){
		$li=$killcoment;
		unset($killcoment);
	}
	#echo "<u>$killcoment $li ".get_page_link ( $li )."</u>";
	if(loaddaten ( "realstaticrefreshallac" )==true){
		global $iscomment;
		$iscomment=false;
		renewrealstaic($li);
	}else nurdieseseite (  ( $li )   );
	return $li;
}

function nurdieseseite($id) {

	getnpush ( loaddaten ( "localurl" ).str_replace ( array(loaddaten ( "localurl" ),loaddaten ( "remoteurl" )), array("",""), get_permalink($id) ), str_replace ( array(loaddaten ( "localurl" ),loaddaten ( "remoteurl" )), array("",""), get_permalink($id) ) );

}
/**
gibt die anzahl neuerer posts vor dem eigentlichen zurück
*/
function getinnewer($erstell, $pageposts, $id,$typ,$muddicat="") {

	global $wpdb;

	$querystr = "SELECT term_taxonomy_id as outo FROM " . $wpdb->prefix . "term_taxonomy where taxonomy='$typ' and term_id='$id'";
	#echo $querystr;
	$anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
	$id=$anzneueralsdieser [0]->outo;



	if(isset($muddicat)){
		$addition="(`term_taxonomy_id` = '$id' $muddicat)";

	}else $addition="`term_taxonomy_id` = '$id'";


	$querystr = "SELECT count(ID) as outo
FROM " . $wpdb->prefix . "posts, " . $wpdb->prefix . "term_relationships
	WHERE post_status = 'publish' AND object_id = ID AND $addition AND post_date>'$erstell'";
	#echo $querystr;
	$anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
	#print_r($querystr);
	#echo 1 + floor ( $anzneueralsdieser [0]->outo / $pageposts );
	return 1 + floor ( $anzneueralsdieser [0]->outo / $pageposts );

}
function get_url123($url) {

	if (function_exists ( 'file_get_contents' )) {
		$file = @file_get_contents ( $url );
	} else {
		$curl = curl_init ( $url );
		curl_setopt ( $curl, CURLOPT_HEADER, 0 );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		$file = curl_exec ( $curl );
		curl_close ( $curl );
	}

	$file = preg_replace_callback ( array ('#<link>(.*?)</link>#', '#<wfw:commentRss>(.*?)</wfw:commentRss>#', '#<comments>(.*?)</comments>#', '# RSS-Feed" href="(.*?)" />#', '# Atom-Feed" href="(.*?)" />#' ), create_function (
	'$treffer', 'return str_replace(loaddaten("localurl"),loaddaten("remoteurl"),$treffer[0]);' ), $file );
global $rs_version;
	$file = preg_replace ( '#<generator>http://wordpress.org/?v=(.*?)</generator>#', '<generator>http://www.sorben.org/really-static/version.php?v=$1-RS'.$rs_version.'</generator>', $file );



	#$file = str_replace ( '<a href="http://wordpress.org/">WordPress</a>', '<a href="http://www.sorben.org/really-static/">Realstatic WordPress</a>', $file );
	$file = preg_replace( '#(Powered by)(\s+)<a(.*?)href=("|\')(.*?)("|\')(.*?)>WordPress</a>#is', '$1$2<a$3href=$4http://www.sorben.org/really-static/$6$7>Realstatic WordPress</a>', $file );

	$file = preg_replace( '#(Powered by)(\s+)<a(.*?)href=("|\')(.*?)("|\')(.*?)>WordPress MU</a>#si', '$1$2<a$3href=$4http://www.sorben.org/really-static/$6$7>Realstatic WordPress</a>', $file );

	$file = preg_replace ( '#<link rel="EditURI"(.*?)>#', "", $file );
	$file = preg_replace ( '#<link rel="wlwmanifest"(.*?)>#', "", $file );
	$file = preg_replace ( '#<link rel="pingback"(.*?)>#', "", $file );
	$file = preg_replace ( '#<meta name="generator" content="WordPress (.*?)" />#', '<meta name="generator" content="WordPress $1 - Realstatic '.$rs_version.'" />', $file );
	$file = preg_replace_callback ( '#<a(.*?)href=("|\')(.*?)("|\')(.*?)>(.*?)</a>#si', "urlrewirte", $file );
	$file = preg_replace_callback ( '#<img(.*?)src=("|\')(.*?)("|\')(.*?)>#si', "imgrewirte", $file );

	$file = preg_replace_callback ( '#<link rel="canonical" href="(.*?)" />#si', "canonicalrewrite", $file );
	$file = str_replace ( loaddaten ( "realstaticdesignlocal" ), loaddaten ( "realstaticdesignremote" ), $file );
	if(substr($url,-11)=="sitemap.xml"){
		$file = preg_replace_callback ( '#<loc>(.*?)</loc>#si', "sitemaprewrite", $file );


	}
	return $file;

}
function canonicalrewrite($array) {
	$path_parts = pathinfo( $array[1]);
	if ($path_parts["extension"] == "") {
		if (substr ($$array[1], - 1 ) != "/")
		$array[1] .= "/";
	}
	return '<link rel="canonical" href="'.$array[1].'" />';

}
function sitemaprewrite($array) {
	$path_parts = pathinfo( $array[1]);
	if ($path_parts["extension"] == "") {
		if (substr ($$array[1], - 1 ) != "/")
		$array[1] .= "/";
	}
	return '<loc>'.$array[1].'</loc>';
}
function imgrewirte($array) {
	installrealstaic ();

	#echo $array [3]."<br>";
	$array [3] = str_replace(loaddaten ( "localurl" ),loaddaten ( "remoteurl" ),$array [3]);//altlastenabfangen
	$a=$array [3];
	$l = strlen ( loaddaten ( "remoteurl" ) );
	$ll = strrpos ( $a, "/" );


	if (substr ( $a, 0, $l ) != loaddaten ( "remoteurl" ))
	return "<img" . $array [1] . "src=" . $array [2] . $array [3] . $array [4]. $array [5] . ">";
	#echo "!!";
	$a = str_replace ( loaddaten ( "remoteurl" ), "", $a );
	$ppp = loaddaten ( "localpath" );
	$l = strlen ( loaddaten ( "subpfad" ) );
	if ($l!=0 && substr ( $a, 0, $l ) == loaddaten ( "subpfad" ))
	$ppp = substr ( $ppp, 0, - $l );

	$fs = @filemtime ( $ppp . $a );
	#if($fs===false)echo "FEHLER:  filetime für $ppp . $a       $a, 0, $l".loaddaten ( "localpath" );;
	//echo $fs;

	require_once ("ftp-client-class.php");

	$ftp_host = loaddaten ( "realstaticftpserver" );
	$ftp_user = loaddaten ( "realstaticftpuser" );
	$ftp_pass = loaddaten ( "realstaticftppasswort" );
	$ftp = new ftp ( );
	$ftp->debug = FALSE;

	if (! $ftp->ftp_connect ( $ftp_host )) {
		die ( "Cannot connect\n" );
	}
	if (! $ftp->ftp_login ( $ftp_user, $ftp_pass )) {
		$ftp->ftp_quit ();
		die ( "Login failed\n" );
	}
	if ($pwd = $ftp->ftp_pwd ()) {
	} else {
		$ftp->ftp_quit ();
		die ( "Error!!\n" );
	}
	if ($sys = $ftp->ftp_systype ()) {
	} else {
		$ftp->ftp_quit ();
		die ( "Error!!\n" );
	}
	global $wpdb;

	$table_name = $wpdb->prefix . "realstatic";
	$querystr = "    SELECT datum  FROM 	$table_name where url='$a'";

	$ss = $wpdb->get_results ( $querystr, OBJECT );

	if ($ss [0]->datum == $fs)
	return "<img" . $array [1] . "src=" . $array [2] . str_replace ( loaddaten ( "localurl" ), loaddaten ( "remoteurl" ), $array [3] ) . $array [4]. $array [5] . ">";
	$wpdb->query ( "  Delete FROM $table_name where url='$a'" );
	$wpdb->query ( "INSERT INTO `" . $wpdb->prefix . "realstatic` (
`url` ,
`datum`
)
VALUES (
'$a', '$fs'
);" );

	##$ftp->put(loaddaten("remotepath").$a,loaddaten("localpath").$a);
	global $internalrun;
	if($internalrun==true)echo "<b>Pushe datei: $a</b><br>";
	global $test;
	if($test!==true)$ftp->ftp_put ( loaddaten ( "remotepath" ) . $a, $ppp . $a );
	$ftp->ftp_quit ();
	##$ftp->close();
	#$ftpconn


	return "<img" . $array [1] . "src=" . $array [2] . str_replace ( loaddaten ( "localurl" ), loaddaten ( "remoteurl" ), $array [3] ) . $array [4] . $array [5]. ">";
}
function urlrewirte($array) {

	$url=str_replace ( loaddaten ( "localurl") , loaddaten ( "remoteurl" ), $array [3] ) ;

	if(strpos($url,loaddaten ( "remoteurl" ))!==false){

		//internlink
		$exts = array(
		'.jpg' =>1,
		'.png'=>1 ,
		'.jpeg'=>1 ,
		'.gif'=>1 ,
		'.swf'=>1 ,
		'.gz'=>1,
		'.tar'=>1 ,
		);
		$ext = strrchr($url,'.');

		if (1==$exts[$ext]){

			$l=str_replace(loaddaten ( "remoteurl" ),"",$url);
			uploadlocalfilelocalremote( loaddaten ( "localpath" ).$l, loaddaten ( "remotepath" ).$l);

		}else {
			if(loaddaten ( "realstaticnonpermanent" ) ==true){
				$url=nonpermanent($url) ;
			}

		}

	}

	return "<a" . $array [1] . "href=" . $array [2] . $url. $array [4] . $array [5] . ">" . $array [6] . "</a>";
}
function uploadlocalfilelocalremote($local,$remote){
	$fs = @filemtime ( $local );
	#if($fs===false)echo "FEHLER:  filetime für $ppp . $a       $a, 0, $l".loaddaten ( "localpath" );;
	global $wpdb;

	$table_name = $wpdb->prefix . "realstatic";
	$querystr = "    SELECT datum  FROM 	$table_name where url='$local'";

	$ss = $wpdb->get_results ( $querystr, OBJECT );

	if ($ss [0]->datum == $fs)return false;

	require_once ("ftp-client-class.php");

	$ftp_host = loaddaten ( "realstaticftpserver" );
	$ftp_user = loaddaten ( "realstaticftpuser" );
	$ftp_pass = loaddaten ( "realstaticftppasswort" );
	$ftp = new ftp ( );
	$ftp->debug = FALSE;

	if (! $ftp->ftp_connect ( $ftp_host )) {
		die ( "Cannot connect\n" );
	}
	if (! $ftp->ftp_login ( $ftp_user, $ftp_pass )) {
		$ftp->ftp_quit ();
		die ( "Login failed\n" );
	}
	if ($pwd = $ftp->ftp_pwd ()) {
	} else {
		$ftp->ftp_quit ();
		die ( "Error!!\n" );
	}
	if ($sys = $ftp->ftp_systype ()) {
	} else {
		$ftp->ftp_quit ();
		die ( "Error!!\n" );
	}
	RS_log("pushelokale datei");
	global $internalrun;
	if($internalrun==true)echo "<b>Pushe datei: $local</b><br>";
	global $test;
	if($test!==true)$ftp->ftp_put ( $remote, $local );
	$ftp->ftp_quit ();
	$wpdb->query ( "  Delete FROM $table_name where url='$local'" );
	$wpdb->query ( "INSERT INTO `" . $wpdb->prefix . "realstatic` (`url` ,`datum`)VALUES ('$local', '$fs');" );
	return true;
}
function getnpush($get, $push,$allrefresh=false) {
	#$push=str_replace(loaddaten ( "remoteurl" ),"",$push);
	#echo loaddaten ( "remoteurl" );
	#exit;
	global $notagain,$test,$wpdb;
	if(loaddaten ( "realstaticnonpermanent" ) ==true){
		$push=nonpermanent($push) ;
	}
	$path_parts = pathinfo( $push);


	if ($path_parts["extension"] == "") {
		if (substr ( $push, - 1 ) != "/")
		$push .= "/index.html";
		else
		$push .= "index.html";
	}

	$table_name = $wpdb->prefix . "realstatic";
	if($allrefresh==true){

		$querystr = "SELECT datum  FROM 	$table_name where url='" . md5($push) . "'";
		$ss = $wpdb->get_results ( $querystr, OBJECT );
		if ($ss [0]->datum > 0) {
			return;
		}
	}

	if($test!==true)$wpdb->query ( "Delete FROM 	$table_name where url='" . md5($push) . "'" );
	if($test!==true)$wpdb->query ( "INSERT INTO `$table_name` (`url` ,`datum`)VALUES ('" . md5($push). "', '" . time () . "');" );







	if (isset ( $notagain [$push] ))
	return;
	$notagain [$push] = 1;

	#echo "<b>$get $push</b><br>";
	#echo "wirteing $push<br>";

	RS_log("hole: $get\r\n".__('writing').": ".loaddaten ( "remotepath" )."$push");
	#echo "$get $push<hr>";
	$pre_remoteserver = loaddaten ( "remotepath" );
	$pre_localserver = loaddaten ( "localpath" );

	require_once ("ftp-client-class.php");

	$ftp_host = loaddaten ( "realstaticftpserver" );
	$ftp_user = loaddaten ( "realstaticftpuser" );
	$ftp_pass = loaddaten ( "realstaticftppasswort" );
	$ftp = new ftp ( );
	$ftp->debug = FALSE;

	if (! $ftp->ftp_connect ( $ftp_host )) {
		die ( "Cannot connect\n" );
	}
	if (! $ftp->ftp_login ( $ftp_user, $ftp_pass )) {
		$ftp->ftp_quit ();
		die ( "Login failed\n" );
	}
	if ($pwd = $ftp->ftp_pwd ()) {
	} else {
		$ftp->ftp_quit ();
		die ( "Error!!\n" );
	}
	if ($sys = $ftp->ftp_systype ()) {
	} else {
		$ftp->ftp_quit ();
		die ( "Error!!\n" );
	}
	global $internalrun;
	if($internalrun==true)	echo "<b>".__('writing').": $get</b><br>";
	##$ftp->put_string(loaddaten("remotepath").$push,get_url123($get));//put string
	global $test;
	if($test!==true)	$ftp->ftp_write ( loaddaten ( "remotepath" ) . $push, get_url123 ( $get ) );

	$ftp->ftp_quit ();
}
function writeeeverytime() {
	// immer, egal ob bei neu, edit oder grill


}
function writenew($id) {

	//index seiten
	$querystr = "SELECT count(ID) as outo FROM " . $wpdb->prefix . "posts	WHERE post_status = 'publish'";
	$normaleseiten = $wpdb->get_results ( $querystr, OBJECT );
	$normaleseiten = 1 + floor ( $normaleseiten [0]->outo / $pageposts );

}
function renewrealstaic($id,$allrefresh=false) { #
	global $iscomment;

	if($iscomment===true)return $id;
	#echo "realystatic";
	global $wpdb,$notagain;
	if(isset($notagain[$id]))return;
	//test ob es ein draft ist
	$table_name = $wpdb->prefix . "post";
	//Eintraege pro post
	$querystr = " SELECT post_status  FROM $table_name where id='".$id."'";
	$pageposts = $wpdb->get_results ( $querystr, OBJECT );
	$pageposts = $pageposts [0]->post_status;

	if($pageposts=="draft")return;

	$notagain[$id]=1;




	$a=getothers("posteditcreatedelete");
	if(is_array($a)){
		foreach ($a as $v){
			getnpush(loaddaten ( "localurl" ).$v,$v,$allrefresh);
		}
	}

	#echo "hmm";
	$table_name = $wpdb->prefix . "options";
	//Eintraege pro post
	$querystr = " SELECT option_value  FROM $table_name where option_name='posts_per_page'";
	$pageposts = $wpdb->get_results ( $querystr, OBJECT );
	$pageposts = $pageposts [0]->option_value;
	$table_name = $wpdb->prefix . "posts";
	//Eintraege pro post
	$querystr = "SELECT post_date  FROM $table_name where ID='$id'";
	$erstell = $wpdb->get_results ( $querystr, OBJECT );
	$erstell = $erstell [0]->post_date; //wann wurde post erstellt

	//indexseiten
	$querystr = "SELECT count(ID) as outo FROM " . $wpdb->prefix . "posts	WHERE post_status = 'publish' AND post_date>'$erstell'";
	$normaleseiten = $wpdb->get_results ( $querystr, OBJECT );
	$normaleseiten = 1 + floor ( $normaleseiten [0]->outo / $pageposts );
	if ($normaleseiten > 1)
	$normaleseiten = "page/$normaleseiten";
	else
	$normaleseiten = "";
	getnpush ( loaddaten ( "localurl" ) . $normaleseiten, $normaleseiten ,$allrefresh);
	//Seite selber
	getnpush ( str_replace(loaddaten ( "remoteurl" ),loaddaten ( "localurl" ),get_permalink($id)), str_replace ( array(loaddaten ( "localurl" ),loaddaten ( "remoteurl" )), array("",""), get_permalink($id) ),$allrefresh );


	//Kategorien
	global $wpdb;
	foreach ( (wp_get_post_categories ( $id )) as $category ) {
		//cat selber
		catrefresh($erstell, $pageposts, $category,$allrefresh);
		$querystr = "SELECT term_taxonomy_id as outo FROM " . $wpdb->prefix . "term_taxonomy where taxonomy='category' and term_id='$category'";
		$anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
		$subcat=$anzneueralsdieser [0]->outo;
		$muddicat=" or `term_taxonomy_id` = '$subcat'";
		//muddi
		do{
			$querystr = "SELECT parent as outa FROM " . $wpdb->prefix . "term_taxonomy where taxonomy = 'category' AND term_id='$category'";
			$category = $wpdb->get_results ( $querystr, OBJECT );
			$category= $category [0]->outa;
			if($category!=0){
				catrefresh($erstell, $pageposts, $category,$allrefresh,$muddicat);


				$querystr = "SELECT term_taxonomy_id as outo FROM " . $wpdb->prefix . "term_taxonomy where taxonomy='category' and term_id='$category'";
				$anzneueralsdieser = $wpdb->get_results ( $querystr, OBJECT );
				$subcat=$anzneueralsdieser [0]->outo;


				$muddicat.=" or `term_taxonomy_id` = '$subcat'";
			}
		}while($category!=0);

	}


	//Tags
	foreach ( (wp_get_post_tags ( $id )) as $tagoty ) {

		$seite = getinnewer ( $erstell, $pageposts, $tagoty->term_id,'post_tag' );
		if ($seite > 1)
		$seite = "/page/$seite";
		else
		$seite = "";
		getnpush ( str_replace(loaddaten ( "remoteurl" ),loaddaten ( "localurl" ),get_tag_link ( $tagoty ) . $seite ), str_replace ( array(loaddaten ( "localurl" ),loaddaten ( "remoteurl" )), array("",""), get_tag_link ( $tagoty ) . $seite ) ,$allrefresh);
		#		echo "<hr>".getinnewer($erstell,$pageposts,$tagoty)."<hr>";
	}

	$e = strtotime ( $erstell );
	global $wp_rewrite;


	//Tag
	#$unten=date("Y-m-d 00:00:00",($e));
	$oben = date ( "Y-m-d 23:59:59", ($e) );
	$unten = $erstell;
	$querystr = "SELECT count(ID) as outa FROM " . $wpdb->prefix . "posts where post_status = 'publish' AND post_date>'$unten' and post_date<='$oben'";
	#ECHO $querystr;
	$tag = $wpdb->get_results ( $querystr, OBJECT );
	$tag = 1 + floor ( $tag [0]->outa / $pageposts );
	if ($tag > 1)
	$tag = "/page/$tag";
	else
	$tag = "";


	$t= str_replace(array("%day%","%monthnum%","%year%"),array(date ( "d", $e ),date ( "m", $e ),date ( "Y", $e )),substr($wp_rewrite->get_day_permastruct(),1));
	getnpush ( loaddaten ( "localurl" ) . $t.$tag, $t .$tag,$allrefresh);



	//Monat
	$t = date ( "t", $e );
	#$unten=date("Y-m-01 00:00:00",($e));
	$oben = date ( "Y-m-$t 23:59:59", ($e) );
	$querystr = "SELECT count(ID) as outa FROM " . $wpdb->prefix . "posts where post_status = 'publish' AND post_date>'$unten' and post_date<='$oben'";
	$monat = $wpdb->get_results ( $querystr, OBJECT );
	$monat = 1 + floor ( $monat [0]->outa / $pageposts );
	if ($monat > 1)
	$monat = "page/$monat";
	else
	$monat = "";
	$t= str_replace(array("%day%","%monthnum%","%year%"),array(date ( "d", $e ),date ( "m", $e ),date ( "Y", $e )),substr($wp_rewrite->get_month_permastruct(),1));
	getnpush ( loaddaten ( "localurl" ) . $t . $monat, $t . $monat ,$allrefresh);
	//Jahr
	#$unten=date("Y-01-01 00:00:00",($e));
	$oben = date ( "Y-12-31 23:59:59", ($e) );
	$querystr = "SELECT count(ID) as outa FROM " . $wpdb->prefix . "posts where post_status = 'publish' AND post_date>'$unten' and post_date<='$oben'";
	$jahr = $wpdb->get_results ( $querystr, OBJECT );
	$jahr = 1 + floor ( $jahr [0]->outa / $pageposts );
	if ($jahr > 1)
	$jahr = "page/$jahr";
	else
	$jahr = "";
	$t= str_replace(array("%day%","%monthnum%","%year%"),array(date ( "d", $e ),date ( "m", $e ),date ( "Y", $e )),substr($wp_rewrite->get_year_permastruct(),1));
	getnpush ( loaddaten ( "localurl" ) .$t . $jahr, $t . $jahr,$allrefresh );



}

// TEMPSTARTER
add_action ( 'admin_menu', 'touchit_admin_menu2' );
function touchit_admin_menu2() {
	// Add admin page to the Options Tab of the admin section


	if (function_exists ( 'add_submenu_page' ))
	add_submenu_page ( 'options-general.php','Really Static' , 'Really Static' , 10, __FILE__, 'touchit_plugin_options2' );
	else
	add_options_page ( 'Really Static', 'Really Static', 8, __FILE__, 'touchit_plugin_options2' );
	// Check if the options exists on the database and add them if not
}
function touchit_plugin_options2() {

	if (isset ( $_POST ["realstaticlocalpath"] )) {
		realstatic_conf_save ();
	}
	if (isset ( $_POST ["go"] )) {
		if($_POST["go"]==1){
			$a=get_option("realstaticposteditcreatedelete");
			foreach ($a as $v){	if($v!=$_POST["md5"])$aa[]=$v;}
			update_option('realstaticposteditcreatedelete',$aa);

		}elseif($_POST["go"]==2){
			$a=get_option("realstaticpageeditcreatedelete");
			foreach ($a as $v){	if($v!=$_POST["md5"])$aa[]=$v;}
			update_option('realstaticpageeditcreatedelete',$aa);

		}elseif($_POST["go"]==3){
			$a=get_option("realstaticcommenteditcreatedelete");
			foreach ($a as $v){	if($v!=$_POST["md5"])$aa[]=$v;}
			update_option('realstaticcommenteditcreatedelete',$aa);

		}elseif($_POST["go"]==4){
			$a=get_option("realstaticeveryday");
			foreach ($a as $v){	if($v!=$_POST["md5"])$aa[]=$v;}
			update_option('realstaticeveryday',$aa);

		}elseif($_POST["go"]==5){
			$a=get_option("realstaticeverytime");
			foreach ($a as $v){	if($v!=$_POST["md5"])$aa[]=$v;}
			update_option('realstaticeverytime',$aa);

		}

	}
	if (isset ( $_POST ["ngo"] )) {


		if($_POST["was"]==1){
			$r=get_option('realstaticposteditcreatedelete');
			$r[]=$_POST["url"];
			sort($r);
			update_option('realstaticposteditcreatedelete',($r));
		}elseif($_POST["was"]==2){
			$r=get_option('realstaticpageeditcreatedelete');
			$r[]=$_POST["url"];
			sort($r);
			update_option('realstaticpageeditcreatedelete',($r));
		}elseif($_POST["was"]==3){
			$r=get_option('realstaticcommenteditcreatedelete');
			$r[]=$_POST["url"];
			sort($r);
			update_option('realstaticcommenteditcreatedelete',($r));
		}elseif($_POST["was"]==4){
			$r=get_option('realstaticeveryday');
			$r[]=$_POST["url"];
			sort($r);
			update_option('realstaticeveryday',($r));
		}elseif($_POST["was"]==5){
			$r=get_option('realstaticeverytime');
			$r[]=$_POST["url"];
			sort($r);
			update_option('realstaticeverytime',($r));
		}











	}
	if (isset ( $_POST ["refreshurl"] )) {
	getnpush(str_replace(loaddaten ( "remoteurl" ),loaddaten ( "localurl" ),$_POST ["refreshurl"]),str_replace(loaddaten ( "remoteurl" ),"",$_POST ["refreshurl"]));
		echo "Erledigt";
	/*
		global $wpdb;
		$table_name = $wpdb->prefix . "realstatic";
		$lastposts = get_posts ( 'numberposts=9999 ' );
		foreach ( $lastposts as $post ) {
			if ($_POST ["refreshurl"]== get_permalink( $post->ID )){
				renewrealstaic ( $post->ID  ,false);
				echo "Erledigt";
				break;
			}


		}
*/

	}
	if (isset ( $_POST ["hideme2"] )) {
		global $wpdb;

		$table_name = $wpdb->prefix . "realstatic";
		$wpdb->query ( "  Delete FROM $table_name" );

	}
	if (isset ( $_POST ["hideme"] )) {

		RS_log(false);

		global $internalrun,$test;
		$internalrun=true;
		set_time_limit ( 0 );
		global $wpdb;
		$a=getothers("everyday");
		if(is_array($a)){
			foreach ($a as $v){
				getnpush(loaddaten ( "localurl" ).$v,$v ,true);
			}
			}
			$a=getothers("posteditcreatedelete");
			if(is_array($a)){
				foreach ($a as $v){
					getnpush(loaddaten ( "localurl" ).$v,$v ,true);
				}
			}
			$table_name = $wpdb->prefix . "realstatic";

			#		 $wpdb->query("  Delete FROM $table_name");


			$lastposts = get_posts ( 'numberposts=9999 ' );
			foreach ( $lastposts as $post ) {
				#echo $post->ID . "<hr>";
				$querystr = "SELECT datum  FROM 	$table_name where url='" . get_page_link ( $post->ID ) . "'";
				$ss = $wpdb->get_results ( $querystr, OBJECT );
				if ($ss [0]->datum > 0) {
				} else {
					renewrealstaic ( $post->ID  ,true);
					#echo "!!";
					#exit;
					#if($test!==true)$wpdb->query ( "INSERT INTO `$table_name` (`url` ,`datum`)VALUES ('" . get_page_link ( $post->ID ). "', '" . time () . "');" );

				}

			}
			//Statische seitem
			$lastposts = get_pages ( 'numberposts=999' );
			foreach ( $lastposts as $post ) {
				#echo $post->ID . "<hr>";
				getnpush ( loaddaten ( "localurl" ). str_replace ( loaddaten ( "remoteurl" ), "", get_page_link ( $post->ID ) ), str_replace ( loaddaten ( "remoteurl" ), "", get_page_link ( $post->ID ) ) ,true);
				#if($test!==true)$wpdb->query ( "INSERT INTO `$table_name` (`url` ,`datum`)VALUES ('" .  str_replace ( loaddaten ( "remoteurl" ), "", get_page_link ( $post->ID ) ). "', '" . time () . "');" );
			}

			#renew(3);


			#renew(16);
			#renew(12);
	}
	#phpinfo();
	#$h="wp-admin/plugins.php";
	#$h=str_replace(array($h,$_SERVER["DOCUMENT_ROOT"]."/"),array("",""),$_SERVER["SCRIPT_FILENAME"]);
	#echo get_option('home')."<hr>";
	$h="";
	#echo $_SERVER["DOCUMENT_ROOT"] .$_SERVER["SCRIPT_FILENAME"].get_option('siteurl').get_option('home')."  - ".ABSPATH ." - ". WPINC ."<hr>";
	
	echo'
<style type="text/css">
<!--
h1.reallystatic {
background: #fff url(http://www.sorben.org/really-static/pluginbild.png) right center no-repeat;
padding: 16px 2px;
margin: 25px 0;
border: 1px solid #ddd;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
border-radius: 3px;
} 
-->
</style>
<h1 class="reallystatic">'.__("Really Static Settings", 'reallystatic').'</h1>';
	
	
	
	
	
	
	
	echo '<form action="" method="post" id="my_fieldset">';

	echo '<b>'.__('Source', 'reallystatic').':</b><br>'.__('internal filepath to wordpressinstalltion', 'reallystatic').':<input name="realstaticlocalpath" size="50" type="text" value="' . loaddaten ( "realstaticlocalpath" , 'reallystatic') . '"	/> (automatic: '.$_SERVER["DOCUMENT_ROOT"]."/".$h.'<br>';
	echo ''.__('url to wordpressinstalltion', 'reallystatic').':<input name="realstaticlocalurl" size="50" type="text" value="' . loaddaten ( "realstaticlocalurl", 'reallystatic' ) . '"	/> (automatic: '.get_option('home').'/)<br>';
	#	echo '<!--subpfad:<input name="realstaticsubpfad" size="50" type="text" value="' . loaddaten ( "realstaticsubpfad" ) . '"	/> (automatic: '.$h.')<br>-->';
	echo ''.__('path to the templatefolder', 'reallystatic').':<input name="realstaticdesignlocal" size="50" type="text" value="' . loaddaten ( "realstaticdesignlocal", 'reallystatic' ) . '"	/> (automatic: http://'.$_SERVER["HTTP_HOST"]."/".$h.'wp-content/themes/default/)<br>';

	echo '<hr><b>'.__('Destination', 'reallystatic').':</b><br>'.__('internal filepath from FTP-Root to cachedfiles', 'reallystatic').':<input name="realstaticremotepath" size="50" type="text" value="' . loaddaten ( "realstaticremotepath" , 'reallystatic') . '"	/> ('.__('the path inside your FTP account e.g. "/path/".If it should saved to maindirectory write "/" ', 'reallystatic').')<br>';
	echo ''.__('Domainprefix for your cached files', 'reallystatic').':<input name="realstaticremoteurl" size="50" type="text" value="' . loaddaten ( "realstaticremoteurl" , 'reallystatic') . '"	/> ( http://www.example.com/ )<br>';

	echo ''.__('FTP-Server IP', 'reallystatic').':<input name="realstaticftpserver" size="50" type="text" value="' . loaddaten ( "realstaticftpserver" , 'reallystatic') . '"	/><br>';
	echo ''.__('FTP-login User', 'reallystatic').':<input name="realstaticftpuser" size="50" type="text" value="' . loaddaten ( "realstaticftpuser", 'reallystatic' ) . '"	/><br>';
	echo ''.__('FTP-login Password', 'reallystatic').':<input name="realstaticftppasswort" size="50" type="text" value="' . loaddaten ( "realstaticftppasswort" , 'reallystatic') . '"	/><br>';


	echo ''.__('Path to the templatefolder').':<input name="realstaticdesignremote" size="50" type="text" value="' . loaddaten ( "realstaticdesignremote" ) . '"	/> ( http://www.example.com/design/ )<br>';

	echo '<input type="checkbox" name="refreshallac" ';
	if(loaddaten ( "realstaticrefreshallac" ) ==true)echo " checked ";
	echo' value="true"> '.__('On the category/tag page e.g. is a commentcounter (not recomended)', 'reallystatic').'<br>';
	echo '<input type="checkbox" name="nonpermanent"';
	if(loaddaten ( "realstaticnonpermanent" ) ==true)echo " checked ";
	echo' value="true"> '.__('I want that Really-Static try to handle with the ? in the url', 'reallystatic').'<br>';

	echo ' <input type="submit" value="'.__('Save', 'reallystatic').'"></form><br>';

	echo '<form action="" method="post" id="my_fieldset"><input type="hidden" name="hideme" value="hidden" />';
	echo __('If this Plugin is installed on a Blog with exsiting Posts or for example you changed your design so you shold press the "write all files" Button. If the prezess is terminatet (e.g. because of a timeout), just press this button again', 'reallystatic');
	
	echo '<br><input type="submit" value="'.__('write all files', 'reallystatic').'"></form><br>';
	echo '<form action="" method="post" id="my_fieldset"><input type="hidden" name="hideme2" value="hidden" />';
	echo __('If you want to renew all files, first press the "reset filedatabase" button and then the "write all files" button', 'reallystatic').'<br>';
	echo ' <input type="submit" value="'.__('reset filedatabase', 'reallystatic').'"></form><br>';
	
	#--------
		echo "<h2>".__('Refresh a staticsite manualy', 'reallystatic')."</h2>";
	echo ' <form method="post">';
	echo '<input name="refreshurl" size="50" type="text" value=""	/> '.__('(complete url of the static page)', 'reallystatic');
	echo ' <input type="submit" value="'.__('refresh', 'reallystatic').'"></form><br>';

	#--------
	echo '<h2>'.__('What should Really-Static do', 'reallystatic').'</h2>';
	echo __('This option allows to give Really-Static accurate information about when a speified URL should make static. This is good thing, when you use example sitemaps or pages that must refreshed every 24 hours.');
	echo '<form method="post"><input type="hidden" name="ngo" value="1" />
	'.__('URL', 'reallystatic').': '.loaddaten ( "localurl" ).'<input name="url" type="text" /> '.__('when', 'reallystatic').':
	<select name="was" style="width: 340px;">
	<option></option>
	<option value="1">'.__('when a Post is created, edited or deleted', 'reallystatic').'</option>';
	#<option value="2">'.__('when a Page is created, edited or deleted', 'reallystatic').'</option>
	#<option value="3">'.__('when a comment is created, edited or deleted', 'reallystatic').'</option>
	echo '<option value="4">'.__('every 24 hours', 'reallystatic').'</option>
	<option value="5">'.__('everytime Really-Static runs', 'reallystatic').'</option>
	</select>&nbsp; <input name="Submit1" type="submit" value="'.__('Submit', 'reallystatic').'" /></form>
';
		$a=getothers("everyday");

	if(is_array($a)and count($a)>0){
	echo "<h3>".__( 'Rewrite every 24 hours' , 'reallystatic')."</h3>";
	foreach ($a as $v){
		echo ' <form method="post">'.$v.'<input type="hidden" name="go" value="4" /><input type="hidden" name="md5" value="'.$v.'" /><input name="Submit1" type="submit" value="x" /></form>'."\n";
	}
	}
	$a=getothers("everytime");
	if(is_array($a)and count($a)>0){
	echo "<h3>".__('Rewrite on every run of Really-Static', 'reallystatic')."</h3>";
	foreach ($a as $v){
		echo ' <form method="post">'.$v.'<input type="hidden" name="go" value="5" /><input type="hidden" name="md5" value="'.$v.'" /><input name="Submit1" type="submit" value="x" /></form>'."\n";
	}
	}
	$a=getothers("posteditcreatedelete");
		if(is_array($a) and count($a)>0){
	echo "<h3>".__('Rewrite on create, edit or delete a post', 'reallystatic')."</h3>";
foreach ($a as $v){
		echo ' <form method="post">'.$v.'<input type="hidden" name="go" value="1" /><input type="hidden" name="md5" value="'.$v.'" /><input name="Submit1" type="submit" value="x" /></form>'."\n";
	}
	}

 
	
	
}

















register_activation_hook(__FILE__, 'reallystatic_activation');
add_action('reallystatic_daylyevent', 'reallystatic_cronjob');

function reallystatic_activation() {
	wp_schedule_event(mktime(0, 0, 0, date("m"), date("d"), date("Y")), 'daily', 'reallystatic_daylyevent');

}

function reallystatic_cronjob() {
	$a=getothers("everyday");
	if(is_array($a)){
	foreach ($a as $v){
		getnpush(loaddaten ( "localurl" ).$v,$v);
	}
	}
	return true;
}



register_deactivation_hook(__FILE__, 'reallystatic_deactivation');

function reallystatic_deactivation() {
	wp_clear_scheduled_hook('reallystatic_daylyevent');
}
function catrefresh($erstell, $pageposts, $category,$allrefresh,$muddicat=""){
	$seite = getinnewer ( $erstell, $pageposts, $category,'category',$muddicat );
	if ($seite > 1)
	$seite = "/page/$seite";
	else
	$seite = "";
	getnpush ( str_replace(loaddaten ( "remoteurl" ),loaddaten ( "localurl" ),get_category_link ( $category ) . $seite ),
	str_replace ( array(loaddaten ( "localurl" ),loaddaten ( "remoteurl" )), array("",""), get_category_link ( $category ) . $seite ),$allrefresh );
}
function nonpermanent($url){
	#if($url!="")echo "!!$url!!";


	$url=preg_replace("#\&amp;cpage=(\d+)#","",$url);
	$url=str_replace("?","",$url);
	$url=preg_replace("#".loaddaten ( "remoteurl" )."wp-trackback.phpp\=(\d+)#",loaddaten ( "localurl" )."wp-trackback.php?p=$1",$url);
	return $url;
}
?>
