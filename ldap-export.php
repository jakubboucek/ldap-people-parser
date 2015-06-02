<?php
date_default_timezone_set('Europe/Prague');
$ldap = json_decode(file_get_contents(__DIR__ . '/data/ldap.json'), TRUE);

$fields = ['dn', 'givenName', 'sn', 'cn', 'displayName', 'mail', 'uid', 'ou', 'employeeType', 'l', 'c', 'gender', 'title', 'contractStartDate', 'contractTermDate', 'dateOfBirth', 'roomNumber', 'sbksManager', 'personalMail', 'description'];

$export = [ $fields ];
foreach( $ldap as $person ) {
	$oneExport = [];
	foreach( $fields as $field ) {
		if(!isset($person[$field])) {
			$oneExport[$field] = NULL;
		}
		elseif(is_array($person[$field])) {
			$oneExport[$field] = $person[$field][0];
		}
		else {
			$oneExport[$field] = $person[$field];
		}
		//19870901000000Z date format parse
		if(preg_match('/\\d{14}Z/', $oneExport[$field])) {
			$oneExport[$field] = date('Y-m-d H:i:s', strtotime($oneExport[$field]));
		}
	}
	$export[] = $oneExport;
}

file_put_contents(__DIR__ . '/data/ldap.csv', 
	join("\n", array_map( function($item){return join("\t",$item);}, $export) )
);

