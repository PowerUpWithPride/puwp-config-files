# Submissions App Config

### marathon_scheduler.nginx
The `nginx` config file that connects the hostname to the `nodejs` process running on the default port on localhost.  There is an HTTP redirect to the HTTPS site using LetsEncrypt via the `certbot` tool.  Remove these lines if setting up your own SSL certificate and run the `certbot` tool yourself.
