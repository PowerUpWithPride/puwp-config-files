# NodeCG Layout Config

### nodecg.json
Config file for NodeCG itself.  We're using the Twitch authentication feature of NodeCG.  Substitute your own values for Twitch ID and secret key, along with the list of allowed usernames.  You can also change the host and port, and note that the port must be different than the port for the submissions app.

### nodecg-speedcontrol.json
Config file for the Speedcontrol bundle.  We're using the Twitch dashboard and Horaro schedule import integrations.  Substitute your own values for Twitch ID and secret key, and put your own Horaro event schedule URL if using this.

### wp-2018-layouts.json
Config file for our PUWP-specific layout bundle.  This includes the donation tracker base URL and the event ID to pull the donation total and current bid list for.  We use the GDQ donation tracker, so if you aren't also using it then this feature won't be of use to you.  This bundle may still be useful as an example of building a layout with NodeCG and Speedcontrol however.

### layout_controller.nginx
The `nginx` config file that connects the hostname to the `nodejs` process running on the default port on localhost.  Note that this must be different than the submissions app.

You should add an HTTP redirect to the HTTPS site using LetsEncrypt via the `certbot` tool.  HTTP section is blank because this is intended to have the `certbot` tool perform the configuration automatically.  Run this tool after putting this config file in place!
