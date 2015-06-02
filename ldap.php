<?php
require(__DIR__ . '/ldap.class.php');

$ldap = new LdapPeople(__DIR__ . '/data/ldap-parse.txt');
$people = [];
foreach ( $ldap as $person ) {
	if(isset($person['jpegPhoto'])) {
		$person['jpegPhoto'] = base64_encode($person['jpegPhoto']);
	}
	$people[] = $person;
}
file_put_contents(__DIR__ . '/data/ldap.json', json_encode( $people, JSON_PRETTY_PRINT ));

