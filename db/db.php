<?php
/***************************************************************************
 *                                 db.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: db.php,v 1.10 2002/03/18 13:35:22 psotfx Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *   This file is part of the phpBB2 port to Nuke 6.0 (c) copyright 2002
 *   by Tom Nitzschner (tom@toms-home.com)
 *   http://bbtonuke.sourceforge.net (or http://www.toms-home.com)
 *
 *   As always, make a backup before messing with anything. All code
 *   release by me is considered sample code only. It may be fully
 *   functual, but you use it at your own risk, if you break it,
 *   you get to fix it too. No waranty is given or implied.
 *
 *   Please post all questions/request about this port on http://bbtonuke.sourceforge.net first,
 *   then on my site. All original header code and copyright messages will be maintained
 *   to give credit where credit is due. If you modify this, the only requirement is
 *   that you also maintain all original copyright messages. All my work is released
 *   under the GNU GENERAL PUBLIC LICENSE. Please see the README for more information.
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/


global $site_admin, $dir, $db_prefix;
if ($site_admin == 1) {
    $the_include = "../db";
} else {
    $the_include = "db";
}
$the_include = $dir.$the_include;


include("".$the_include."/mysqli.php");	

$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, $dbport);

//
if(!$db->db_connect_id) {
    message_die(CRITICAL_ERROR, "Could not connect to the database");
}

 // ################################
 // Carga de configuraci�n del sitio
 // ################################
//
//	$opciones_carga = "select id_opcion, opcion, valor from ".$db_prefix."opciones";
//	$resultado = $db->sql_query($opciones_carga);
//	while ($row = $db->sql_fetchrow($resultado)) {
//		$config_data[$row[opcion]] = $row[valor];
//	}

 // ################################

function message_die($tipo, $texto)
{
	echo "<br><br>Se ha producido un error en la base de datos<br>
	<br>
	".$texto."";
	exit;
}

?>