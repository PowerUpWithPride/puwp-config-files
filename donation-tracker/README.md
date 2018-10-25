# Donation Tracker Config

### local.py
This is a basic config file for the donation tracker, which is a Django app.  Substitute your own site name, database credentials, etc.

### puwp_donations.ini
This is the `uwsgi` emperor config file when launching the main process.  There's nothing sensitive about this, but you can substitute your own paths as needed.

The assumption is you have a Python virtual environment set up in `/home/<user>/Env/<project>`.  If your virtual environments are set up in a different location, modify this path.

### puwp_donations.nginx
Corresponding `nginx` config file that connects the hostname to the `uwsgi` process via a Unix socket.

You should add an HTTP redirect to the HTTPS site using LetsEncrypt via the `certbot` tool.  HTTP section is blank because this is intended to have the `certbot` tool perform the configuration automatically.  Run this tool after putting this config file in place!
