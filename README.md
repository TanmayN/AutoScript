AutoScript is a shell script management program written in PHP. It can pull sources from remote origins.

Usages
========
AutoScript's main function is to run scripts. You can run scripts with the following command
`php autoscript.php run <scriptname>`

If you get an error that the script doesn't exist, you can check the list.
`php autoscript.php list`

If you don't understand a command, you can run help.
`php autoscript.php help`

Sources File
===============
The sources file is formatted very simply.
	Script Name <-> Script Description <-> Script URL <-> 
	Script Two <-> Script Two's Description <-> Script Two's URL...
	
A few things to note are, an extra space must be given at the end of every line except the last.
The last line should not have a <-> seperator.

Script Specifications
======================
There aren't really any rules for the scripts, they just need to be written in bash. Fully automated scripts are preferred.
Be sure to write your script in a way that all systems can read it.

Requests
=========
If you'd like me to add your script or your sources list to the official GitHub/my server, feel free to let me know with an email at tanmayn429@gmail.com or a message on IRC: Tanmay@irc.rizon.net.

Feel free to contact me for other needs or bug submissions. 