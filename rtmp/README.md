# RTMP Server Setup

## Installing nginx web server + nginx-http-flv-module
These instructions are for Debian/Ubuntu distributions.  We want to compile from the PPA source files in order to keep the same configuration as the default Ubuntu packages for convenience.  If you're running a different system, you can find more general compiling-from-source instructions at the [nginx-http-flv-module GitHub page](https://github.com/winshining/nginx-http-flv-module).

### 1. Add PPA for latest nginx version
```bash
sudo add-apt-repository ppa:nginx/development
sudo apt-get update
```

### 2. Build nginx from source
First we need to install the build dependencies for `nginx`.

```bash
sudo apt-get install dpkg-dev
sudo apt-get source nginx
```

This will place the nginx source files in `/usr/src`.  If you're installing for the first time, you also need to build the nginx dependencies:

```bash
sudo apt-get build-dep nginx
```

### 3. Download nginx-http-flv-module and include in the config.
The current release at time of this writing is `v1.2.5`.  You can clone the repository if you want the absolute latest code, but it's probably safer to use the current release. 

```bash
cd /usr/src
sudo wget https://github.com/winshining/nginx-http-flv-module/archive/v1.2.5.tar.gz
sudo tar -xvf v1.2.5.tar.gz
```

You should now have a folder `/usr/src/nginx-http-flv-module-1.2.5`.  Change the directory name accordingly if you downloaded a different version or cloned the repo.

Now we need to edit the rules file for nginx to include this directory as a module during compilation.

```bash
cd /usr/src/nginx-1.15.5
sudo vi debian/rules
```

Find the `full_configure_flags` configuration string and add `--add-module=/usr/src/nginx-http-flv-module-1.2.5 \ ` to the list.

Now build the packages, and go grab a meal while you wait...

```bash
sudo dpkg-buildpackage -b
```

### 4. Install built packages and hold for future updates

Once it's done building, you can install the packages you just built.  Change the filenames accordingly if you built a different version of nginx.

```bash
cd ..
sudo dpkg --install nginx-common_1.15.5-0+cosmic1_all.deb nginx-full_1.15.5-0+cosmic1_amd64.deb
```

After installing, you can check if it's running.

```bash
sudo systemctl status nginx
sudo systemctl start nginx  # If it's not running
```

At this point, you may want to place a hold on the `nginx-full` package so it doesn't get overwritten when updating other packages on the system.

```bash
sudo apt-mark hold nginx-full
```

## Config Files

### rtmp_ingest.nginx
The `nginx` config file that contains the RTMP configuration settings.  This contains two apps: one for the runners to stream to that doesn't get forwarded anywhere, and one for the restreamer to stream to which gets forwarded to your Twitch stream.  The latter has an `on_publish` trigger to provide some basic authentication for the restreamer based on the stream key.

### rtmp_auth.nginx
The `nginx` config file that contains the HTTP settings for the authentication of restreamers.  This should be the path of the `on_publish` trigger in the previous file, and it validates the stream key matches the one you choose.  This is a very simple authentication method, but it's quick and easy and it works.
