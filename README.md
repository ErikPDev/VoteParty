# VoteParty
[![VoteParty](https://i.imgur.com/515YfKH.png)](https://github.com/ErikPDev/VoteParty/)

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
BetterVoting and PocketVote are supported plugins.

How to use BetterVoting or PocketVote with this plugin?

Change `PocketVoteSupport` to true for PocketVote Support

Change `BetterVotingSupport` to true for BetterVoting Support

Notice: You can only use PocketVote or BetterVoting at a time.

# ScoreHud
This plugin also supports ScoreHud variables.
§a{voteparty.totalVotes} §c/ §d{voteparty.maxVotes}
| Tag                  | Description                     |
|----------------------|---------------------------------|
| voteparty.totalVotes | Shows the votes to voteparty    |
| voteparty.maxVotes   | Default votes to voteparty      |

# Permissions & Commands
| Commands       | Permission Nodes | Default |
|----------------|------------------|---------|
| voteparty      | voteparty.use    | OP      |
| votepartyreset | voteparty.reset  | OP      |