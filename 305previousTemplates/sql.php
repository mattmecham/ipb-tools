<?php

/**
 * <pre>
 * Invision Power Services
 * IP.Board vVERSION_NUMBER
 * Generates Previous Templates
 * Last Updated: $Date: 2010-01-15 15:18:44 +0000 (Fri, 15 Jan 2010) $
 * </pre>
 *
 * @author 		$Author: bfarber $
 * @copyright	(c) 2001 - 2009 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/community/board/license.html
 * @package		IP.Board
 * @subpackage	Chat
 * @link		http://www.invisionpower.com
 * @version		$Rev: 5713 $
 *
 */

/**
* Script type
*
*/
define( 'IPB_THIS_SCRIPT', 'public' );

if ( file_exists( '../initdata.php' ) )
{
	require_once( '../initdata.php' );
}
else
{
	require_once( '../../initdata.php' );
}

/* Enforce access to bypass force member log in, etc */
define( 'IPS_ENFORCE_ACCESS', TRUE );

/**
 * IPB registry
 */
require_once( IPS_ROOT_PATH . 'sources/base/ipsRegistry.php' );
require_once( IPS_ROOT_PATH . 'sources/base/ipsController.php' );

$reg = ipsRegistry::instance();
$reg->init();


$data   = fetchData();
$prefix = ipsRegistry::dbFunctions()->getPrefix();

if ( $prefix )
{
	$data = str_replace( "`skin_templates_previous", "`{$prefix}skin_templates_previous", $data );
	$data = str_replace( "`skin_css_previous", "`{$prefix}skin_css_previous", $data );
}

while( preg_match( "#(CREATE TABLE|INSERT INTO)(.+?)\);\n#s", $data, $match ) )
{
	$query = $match[1].$match[2] . ')';
	
	print htmlspecialchars( substr( $query, 0, 100 ) ) . '....<br />';
	
	ipsRegistry::DB()->query( $query );
	
	$data = str_replace( $match[0], "\n", $data );
}

print "Done";
exit();

function fetchData()
{
	$file = './data.txt';
	
	/* Test for file exists */
	if ( ! file_exists( $file ) )
	{
		print "Could not load $file";
		exit();
	}
	else
	{
		/* Test commit again */
		$data = @file_get_contents( $file );
	}

	return $data;
}

?>