# Submissions App Config

### marathon_scheduler.nginx
The `nginx` config file that connects the hostname to the `nodejs` process running on the default port on localhost.

You should add an HTTP redirect to the HTTPS site using LetsEncrypt via the `certbot` tool.  HTTP section is blank because this is intended to have the `certbot` tool perform the configuration automatically.  Run this tool after putting this config file in place!
