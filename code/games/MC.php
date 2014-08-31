<?php
//------------------------------------------------------------------------------------------------------------+
//
// Name: MC.php
//
// Description: Code to parse Minecraft servers
// Initial author: momo5502 <MauriceHeumann@googlemail.com>
//
//------------------------------------------------------------------------------------------------------------+

if ( !defined( "BANNER_CALL" ) ) {
	exit( "DIRECT ACCESS NOT ALLOWED" );
}

//------------------------------------------------------------------------------------------------------------+

include( 'dependencies/MinecraftQuery.class.php' );

//------------------------------------------------------------------------------------------------------------+
//Query minecraft servers

function query( $ip, $port )
{
	$Query = new MinecraftQuery();
	
	try {
		$Query->Connect( $ip, $port, 1 );
		$Info = $Query->GetInfo();
		
		if ( !$hostname = $Info[ 'HostName' ] )
			$hostname = "-";
		
		if ( !$gametype = $Info[ 'GameType' ] )
			$gametype = "-";
		
		if ( !$mapname = $Info[ 'Map' ] )
			$mapname = "-";
		
		if ( !$players = $Info[ 'Players' ] )
			$players = "0";
		
		if ( !$maxplayers = $Info[ 'MaxPlayers' ] )
			$maxplayers = "-";
	
		if ( count( $newplayers ) )
			$players = count( $newplayers );
			
		$data = array(
			 "value" => "1",
			"hostname" => cleanHostname( $hostname ),
			"gametype" => $gametype,
			"protocol" => "MC",
			"clients" => $players,
			"maxclients" => $maxplayers,
			"mapname" => $mapname,
			"server" => $ip . ":" . $port,
			"unclean" => $hostname 
		);
	}
	
	catch ( MinecraftQueryException $e ) {
		$data = getErr( $ip, $port );
	}
	
	return $data;
}

//------------------------------------------------------------------------------------------------------------+
//Clean hostname from color codes

function cleanHostname( $name )
{
	$hostname = $name;
	
	for ( $i = 0; $i < 10; $i++ )
		$hostname = str_replace( "&{$i}", "", $hostname );
		$hostname = str_replace( "§{$i}", "", $hostname );
		$letter_array = array("a", "b", "c", "d", "e", "f", "k", "l", "m", "n", "o", "r");
		foreach ($letter_array as $key) {
			$hostname = str_replace( "&".$key."", "", $hostname );
			$hostname = str_replace( "§".$key."", "", $hostname );	
		}
	
	return $hostname;
}

//------------------------------------------------------------------------------------------------------------+
?>
