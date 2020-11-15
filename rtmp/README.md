# RTMP Server Setup

## Installing nginx web server + nginx-http-flv-module
These instructions are for Debian/Ubuntu distributions.  We want to compile the RTMP FLV module as a dynamic module so we can use the default Ubuntu nginx packages for convenience and load only the compiled module dynamically, instead of compiling entirely from source.  This makes managing updates easier.  If you're running a different system, you can find more general compiling-from-source instructions at the [nginx-http-flv-module GitHub page](https://github.com/winshining/nginx-http-flv-module).

### 1. Install nginx, get source and install build dependencies
First install the latest nginx itself based on your distro.  As of Ubuntu 20.04, there is no longer a separate PPA for nginx.  The main Ubuntu package repository should have the latest stable version, so all you need is:

```bash
sudo apt-get install nginx
```

After installing, you can check if it's running.

```bash
sudo systemctl status nginx
sudo systemctl start nginx  # If it's not running
```

Next we need to get the source for `nginx`.

```bash
cd /usr/src
sudo apt-get install dpkg-dev
sudo apt-get source nginx
```

This will place the nginx source files in `/usr/src`.  If you're installing for the first time, you also need to build the nginx dependencies:

```bash
sudo apt-get build-dep nginx
```

### 2. Download nginx-http-flv-module and build as a dynamic module
The current release at time of this writing is `v1.2.8`.  You can clone the repository if you want the absolute latest code, but it's probably safer to use the current release. 

```bash
cd /usr/src
sudo wget https://github.com/winshining/nginx-http-flv-module/archive/v1.2.8.tar.gz
sudo tar -xvf v1.2.8.tar.gz
```

You should now have a folder `/usr/src/nginx-http-flv-module-1.2.8`.  Change the directory name accordingly if you downloaded a different version or cloned the repo.

Now we need to edit the rules file for nginx to include this directory as a dynamic module.  Change the nginx directory name if you have a different version accordingly:

```bash
cd /usr/src/nginx-1.17.10
./configure --with-compat --add-dynamic-module=../nginx-http-flv-module-1.2.8
```

Now build the dynamic modules, which shouldn't take too long...

```bash
make modules
```

### 3. Install built module

Finally we need to copy the resulting `.so` shared object file to the nginx modules directory.  On an Ubuntu system, this is located at `/usr/lib/nginx/modules/` but change your path according to your system.

```bash
sudo cp objs/ngx_http_flv_live_module.so /usr/lib/nginx/modules/
```

### 4. Install PHP fast process manager for authentication script (optional)

If you're using the same method as us for restreamer stream key validation (i.e. a simple PHP script), you should install the PHP fast process manager package in Ubuntu.

```bash
sudo apt-get install php-fpm
```

## Config Files

These files need to be added as part of your nginx configuration.  The most important one is the "ingest" file which actually loads the dynamic module we just built and defines the RTMP functionality.

### rtmp_ingest.nginx
The `nginx` config file that contains the RTMP configuration settings and actually loads the dynamic module at the top.  This contains three apps:

- `runners` is for the runners to stream to with whatever stream keys you choose for your event.  It doesn't get forwarded anywhere.  The restreamers will pull game feeds from this on the viewing page.

- `live` is for the restreamer to stream to which gets forwarded to your Twitch stream.  This has an `on_publish` trigger to provide some basic authentication for the restreamer based on the stream key.  This also gets forwarded to the `restream` app described below on the localhost.

- `restream` is only allowed to be published to on the localhost.  The `live` stream above is forwarded here on the localhost.  The commentator page where they view the restream pulls from this app in order to hide the stream key that the restreamer is actually using.

### rtmp_auth.nginx
The `nginx` config file that contains the HTTP settings for the authentication of restreamers on the live path.  This contains a simple PHP setup to execute any PHP files within the root path specified.  The only file present in this path should be the `auth_live.php` file below, so place it there.

### flv_runners.nginx
The `nginx` config file that contains the HTTP settings for making the FLV encoded stream available via the web server.  This path will be used on the actual webpages for viewing the streams.

### auth_live.php

A simple PHP script that receives the `on_publish` trigger from the RTMP live path and checks if the stream key matches the secret one chosen.  This is a very simple authentication method, but it's quick and easy.

### runner_viewer.nginx

The `nginx` config file for the game feed viewing site that restreamers will use.  This is a very basic static webpage config.  See https://github.com/PowerUpWithPride/rtmp-viewer for the actual webpages.

### restream_viewer.nginx

The `nginx` config file for the restream viewing site that commentators will use to watch the stream.  Actual page is located in the same repository mentioned above.
