<?php
//START CONFIGURATION, ENTER YOUR VARIABLES HERE
$scriptsFile = "http://tanmayn.com/scripts.txt";
//END CONFIGURATION


function greenString($string) {
	$greenString = "\033[1;32m" . $string . "\033[0m";
	return $greenString;
}

function getScripts() {
	global $scriptsFile;
	// TODO: Multiple scripts sources
	$data = file_get_contents($scriptsFile);
	return $data;	
}

function listScripts() {
	$scriptsData = getScripts();
	$scriptsArray = explode("\n", $scriptsData);
	foreach ($scriptsArray as $script) {
		$script = explode(' <-> ', $script);
		echo greenString("\n-------------------------------\n");
		echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
		$output = greenString("-------------------------------\n") . greenString("NAME: ") . $script[0] . "\n" . greenString("DESCRIPTION: ") . $script[1] . "\n" . greenString("URL: ") . $script[2] . "\n" . greenString("-------------------------------\n\n");
		echo $output;
	}
}

function runScript($url) {
	$out = shell_exec("/bin/bash -c `curl \"" . $url . "\"`");
	return $out;
}

function help($option = "") {
	
	$availableOptions = array( 'run' => 'runs a script',
					           'list' => 'list all available scripts',
					           'help' => 'shows help');
	$optionsSyntaxes = array( 'run' => 'php autoscript.php run <script>',
							  'list' => 'php autoscript.php list',
							  'help' => 'php autoscript.php help [option]');
	
	echo greenString("\n-------------------------------\n");
	echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
	echo greenString("\n-------------------------------\n");
	if ($option == "") {
		echo "- " . greenString("run") . " - run a script\n";
		echo "- " . greenString("list") . " -  list all current scripts\n";
		echo "- " . greenString("help") . " -  show help and version\n";
		echo greenString("\n-------------------------------\n");
		echo "- " . greenString("FOR MORE HELP ON A SPECIFIC OPTION, RUN: ") . "php autoscript.php help <option>\n\n";
	} elseif (array_key_exists($option, $availableOptions)) {
		echo "- " . greenString($option) . " - " . $availableOptions[$option] . "\n";
		echo "- " . greenString("SYNTAX: ") . $optionsSyntaxes[$option];
		echo greenString("\n-------------------------------\n\n");
	} else {
		echo "- " . greenString("ERROR: ") . "Could not find help for \"" . $option . "\"\n";
		echo greenString("-------------------------------\n\n");
	}
}

if (strcasecmp($argv[1], "run") == 0) {
	if (!isset($argv[2])) {
		die(greenString("ERROR: ") . "Invalid usage, try: php autoscript.php help run\n");
	}
	$selected = $argv[2];
	$scripts = getScripts();
	$scriptsArray = explode(" <-> ", $scripts);
	if (in_array($selected, $scriptsArray) || in_array("\n" . $selected, $scriptsArray)) {
		$position = array_search($selected, $scriptsArray);
		$newPosition = $position + 2;
		$url = $scriptsArray[$newPosition];
		echo greenString("\n-------------------------------\n");
		echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
		echo greenString("\n-------------------------------\n");
		echo greenString("SCRIPT RUNNING\n");
		echo runScript($url . "\n");
	} else {
		echo greenString("ERROR: ") . "Could not find that script, try: php autoscript.php list\n";
	}
} elseif (strcasecmp($argv[1], "list") == 0) {
	echo greenString("\nCURRENT AVAILABLE SCRIPTS\n");
	listScripts();
	echo greenString("TO ADD MORE, EDIT YOUR SCRIPTS FILE\n\n");
} elseif (strcasecmp($argv[1], "help") == 0) {
	if (!isset($argv[2])) {
		help();
	} else {
		help($argv[2]);
	}
}

?>