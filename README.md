Master Status: [![Master Status](https://secure.travis-ci.org/gquemener/github-notification-fetcher.png?branch=master)](https://travis-ci.org/gquemener/github-notification-fetcher)

Develop Status: [![Develop Status](https://secure.travis-ci.org/gquemener/github-notification-fetcher.png?branch=develop)](https://travis-ci.org/gquemener/github-notification-fetcher)

# Installation

Install dbus ext through pecl:
```
# apt-get install pear php5-dev libdbus-1-dev libxml2-dev
# pecl install channel://pecl.php.net/dbus-0.1.1
```

# Usage

You must have a valid token within the scope "notifications" (not tested with the "repo" scope).

To do that, please refer to [the official documentation](http://developer.github.com/v3/oauth/#create-a-new-authorization).

Then you case use ginof:
```
$ bin/ginof fetch {your token} {a target dir where to store notifications}
```
