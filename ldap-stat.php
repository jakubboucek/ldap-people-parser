<?php
$ldap = json_decode(file_get_contents(__DIR__ . '/data/ldap.json'), TRUE);

$stat = [];

foreach( $ldap as $person ) {
	foreach( $person as $paramName => $param ) {
		if(!isset($stat[$paramName])) { $stat[$paramName] = ['a'=>0,'s'=>0]; }
		$stat[$paramName][(is_array($param) ? 'a' : 's')]++;
	}
}

//echo json_encode( $stat, JSON_PRETTY_PRINT );
$e = [];
foreach( $stat as $key => $item ){
	$e[] = "$key\t$item[s]\t$item[a]";
}
echo join("\n", $e );
echo "\n";