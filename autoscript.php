<?php
// START CONFIGURATION, ENTER YOUR VARIABLES HERE
$scriptsFiles = array("http://tanmayn.com/scripts.txt"); //Local files also suppported
// END CONFIGURATION

// Makes a string green... yeah
function greenString($string) {
	$greenString = "\033[1;32m" . $string . "\033[0m";
	return $greenString;
}

// Downloads the scripts sources file
function getScripts() {
	global $scriptsFiles;
	// Initialize the data variable
	$data = "";
	foreach ($scriptsFiles as $file) {
		// Compile the list of scripts
		$data .= file_get_contents($file) . "\n";	
	}
	return $data;	
}

// List all the available scripts.
function listScripts($tScript = "") {
	if (!empty($tScript)) {
		echo greenString("\nCURRENT AVAILABLE SCRIPTS\n");
		echo greenString("\n-------------------------------\n");
		echo greenString("AUTOSCRIPT v1.0") . " by TanmayN\n";
		echo greenString("-------------------------------\n\n");
		// First download the script sources
		$scriptsData = getScripts();
		// Break that data into an array at every new line
		$scriptsArray = explode("\n", $scriptsData);
		// Display the name, description and URL of each script
		foreach ($scriptsArray as $script) {
			// If the line does actually exist and isn't just a newline
			if (isset($script[1])) {
				// Explode the line into an array
				$script = explode(' <-> ', $script);
				// This line is less retarded now
				$output = greenString("-------------------------------") .
				"\n" . greenString("- NAME: ") . $script[0] . 
				"\n" . greenString("- DESCRIPTION: ") . $script[1] . 
				"\n" . greenString("- URL: ") . $script[2] .
				"\n" . greenString("-------------------------------\n\n");
				echo $output;
			}
		}
		echo greenString("TO ADD MORE, EDIT YOUR SCRIPTS FILE\n\n");
	} else {
		echo greenString("\n-------------------------------\n");
		echo greenString("AUTOSCRIPT v1.0") . " by TanmayN\n";
		echo greenString("-------------------------------\n\n");
		// First download the script sources
		$scriptsData = getScripts();
		// Break that data into an array at every new line
		$scriptsArray = explode("\n", $scriptsData);
		foreach ($scriptsArray as $script) {
			if (isset($script[1])) {
				$script = explode(' <-> ', $script);
				// Display the name, description and URL of each script
				$output = greenString("-------------------------------") .
				"\n" . greenString("- NAME: ") . $script[0] . 
				"\n" . greenString("- DESCRIPTION: ") . $script[1] . 
				"\n" . greenString("- URL: ") . $script[2] .
				"\n" . greenString("-------------------------------\n\n");
				echo $output;
			}
		}
	}
}

function runScript($url) {
	// Download the script as temp.sh
	$out1 = shell_exec("wget -O temp.sh " . $url);
	// Mark it executable and run it under bash
	$out2 = shell_exec("chmod +x temp.sh && bash temp.sh");
	return $out2;
}

function downloadScript($url) {
	if (shell_exec("ls | grep .sh")) {
		shell_exec("mv *.sh backup/");
	}
	// Download the script
	$download = shell_exec("wget " . $url);
	// Mark it executable
	shell_exec("chmod +x *.sh");
	$filename = str_replace("\n", "", shell_exec("ls *.sh"));
	return $filename;
}

function help($option = "") {
	
	// List of available help topics
	$availableOptions = array( 'run' => 'runs a remote script',
							   'download' => 'download a script to pwd but don\'t run it, useful for scripts that need command line parameters.',
					           'list' => 'list all available scripts',
					           'help' => 'shows help');
	// All their syntaxes (required for now, I'll fix it later)
	$optionsSyntaxes = array( 'run' => 'php autoscript.php run <script>',
							  'download' => 'php autoscript.php download <script>',
							  'list' => 'php autoscript.php list',
							  'help' => 'php autoscript.php help [option]');
	
	echo greenString("\n-------------------------------\n");
	echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
	echo greenString("\n-------------------------------\n");
	// If the command has no extra parameter, display the list of options
	if ($option == "") {
		echo "- " . greenString("run") . " - run a script\n";
		echo "- " . greenString("download") . " - download a script but don't run it\n";
		echo "- " . greenString("list") . " -  list all current scripts\n";
		echo "- " . greenString("help") . " -  show help and version\n";
		echo greenString("\n-------------------------------\n");
		echo "- " . greenString("FOR MORE HELP ON A SPECIFIC OPTION, RUN: ") . "php autoscript.php help <option>\n\n";
		// If they passed an option and it exists, give a full description and syntax
	} elseif (array_key_exists($option, $availableOptions)) {
		echo "- " . greenString($option) . " - " . $availableOptions[$option] . "\n";
		echo "- " . greenString("SYNTAX: ") . $optionsSyntaxes[$option];
		echo greenString("\n-------------------------------\n\n");
		// If they supply an option but it doesn't exist
	} else {
		echo "- " . greenString("ERROR: ") . "Could not find help for \"" . $option . "\"\n";
		echo greenString("-------------------------------\n\n");
	}
}

if (strcasecmp($argv[1], "run") == 0) {
	// if no script is specified, then die
	if (!isset($argv[2])) {
		die(greenString("ERROR: ") . "Invalid usage, try: php autoscript.php help run\n");
	}
	$selected = $argv[2];
	// Download the script sources
	$scripts = getScripts();
	// Break it into an array
	$scriptsArray = explode(" <-> ", $scripts);
	// If the script selected exists, run it
	if (in_array($selected, $scriptsArray) || in_array("\n" . $selected, $scriptsArray)) {
		// Find the position of the title in the array
		$position = array_search($selected, $scriptsArray);
		if (!$position) {
			// If it's not on the first line, it'll have a new line character
			$position = array_search("\n" . $selected, $scriptsArray);
		}
		// Add two to the array position to find the URL
		$newPosition = $position + 2;
		// The URL value
		$url = $scriptsArray[$newPosition];
		echo greenString("\n-------------------------------\n");
		echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
		echo greenString("\n-------------------------------\n");
		echo greenString("SCRIPT RUNNING\n");
		// Run the script
		echo runScript($url . "\n");
		echo greenString("SCRIPT COMPLETED\n");
		// Remove the temp script, this is good to keep things neat and tidy
		shell_exec("rm temp.sh");
		echo greenString("\n-------------------------------\n\n");
	} else {
		// If the script specified wasn't found, don't continue
		echo greenString("ERROR: ") . "Could not find that script, try: php autoscript.php list\n";
	}
} elseif (strcasecmp($argv[1], "list") == 0) {
	// List the scripts
	listScripts();
} elseif (strcasecmp($argv[1], "help") == 0) {
	// Display help
	if (!isset($argv[2])) {
		help();
	} else {
		help($argv[2]);
	}
} elseif (strcasecmp($argv[1], "download") == 0) {
	if (!isset($argv[2])) {
		die(greenString("ERROR: ") . "Invalid usage, try: php autoscript.php help download\n");
	}
	$selected = $argv[2];
	// Download the script sources
	$scripts = getScripts();
	// Break it into an array
	$scriptsArray = explode(" <-> ", $scripts);
	// If the script selected exists, run it
	if (in_array($selected, $scriptsArray) || in_array("\n" . $selected, $scriptsArray)) {
		// Find the position of the title in the array
		$position = array_search($selected, $scriptsArray);
		if (!$position) {
			// If it's not on the first line, it'll have a new line character
			$position = array_search("\n" . $selected, $scriptsArray);
		}
		// Add two to the array position to find the URL
		$newPosition = $position + 2;
		// The URL value
		$url = $scriptsArray[$newPosition];
		echo greenString("\n-------------------------------\n");
		echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
		echo greenString("\n-------------------------------\n");
		echo greenString("SCRIPT DOWNLOADING\n");
		// Download the script
		$fileName = downloadScript($url);
		echo greenString("SCRIPT SAVED AS \"" . $fileName . "\"");
		echo greenString("\n-------------------------------\n\n");
	} else {
		// If the script specified wasn't found, don't continue
		echo greenString("ERROR: ") . "Could not find that script, try: php autoscript.php list\n";
	}
} elseif (strcasecmp($argv[1], "search") == 0) {
	$out = shell_exec("php autoscript.php list | grep -i '" . $argv[2] . "'");
	echo greenString("\n-------------------------------\n");
	echo greenString("AUTOSCRIPT v1.0") . " by TanmayN";
	echo greenString("\n-------------------------------\n");
	if (empty($out)) {
		echo greenString("ERROR: ") . "Script not found\n";
	} else {
		echo $out;
		$outArray = explode("\n", $out);
		$outNum = count($outArray);
		for ($i = 0; $i == $outNum; $i + 3) {
			echo $outArray[$i];
		}
	}
	echo greenString("\n-------------------------------\n\n");
} else {
	// If the option requested does not exist, then die
	echo greenString("ERROR: ") . "Could not find that option, try: " . greenString("php autoscript.php help\n");
}

?>
