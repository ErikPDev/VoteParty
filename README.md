# VoteParty
[![VoteParty](https://i.imgur.com/515YfKH.png)](https://github.com/antbag-pm-pl/VoteParty/)

[![](https://poggit.pmmp.io/shield.state/VoteParty)](https://poggit.pmmp.io/p/VoteParty)

Give rewards to all online players when the server gets a certain amount of votes! 

Read setup for more info!

# Why choose this plugin?
This plugin will encourage players to vote for your server to get rare items.

# Setup
The setup is really easy, you'll just need to download the phar file at the plugins folder.

After that reboot/start your server up.

Modify the config.yml file located at plugin_data/VoteParty.

If the voting plugin you use isn't supported by this plugin, then your voting plugin should execute the command `voteparty` everytime somebody votes.

Once you've setted up the plugin make sure to run `/votepartyreset` to reset the counter to the default value on the Config.yml, also do this when you modify the config.

# Supported Plugins
Voting38 is supported plugin.

How to use Voting38 with this plugin?

Change `Voting38Support` to true for Voting38 Support

# ScoreHud
This plugin also supports ScoreHud variables.
Example: §a{voteparty.totalVotes} §c/ §d{voteparty.maxVotes}
| Tag                  | Description                     |
|----------------------|---------------------------------|
| voteparty.totalVotes | Shows the votes for voteparty    |
| voteparty.maxVotes   | Default votes for voteparty      |
<img src="https://raw.githubusercontent.com/ErikPDev/VoteParty/main/assets/ScoreboardExample.png">

# ScoreBoard
This plugin supports ScoreBoard variables
Example: §a{votes} §c/ §d{maxvotes}
| Tag        | Description                     |
|------------|---------------------------------|
| votes      | Shows the votes for voteparty    |
| maxvotes   | Default votes for voteparty      |
<img src="https://raw.githubusercontent.com/ErikPDev/VoteParty/main/assets/ScoreboardExample.png">

# Permissions & Commands
| Commands       | Permission Nodes | Default |
|----------------|------------------|---------|
| voteparty      | voteparty.use    | OP      |
| votepartyreset | voteparty.reset  | OP      |
