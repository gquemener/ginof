Master Status: [![Master Status](https://secure.travis-ci.org/gquemener/github-notification-fetcher.png?branch=master)](https://travis-ci.org/gquemener/github-notification-fetcher)

Develop Status: [![Develop Status](https://secure.travis-ci.org/gquemener/github-notification-fetcher.png?branch=develop)](https://travis-ci.org/gquemener/github-notification-fetcher)

# Installation

In order to use gnome notifications, install dbus ext through pecl:
```
# apt-get install pear php5-dev libdbus-1-dev libxml2-dev
# pecl install channel://pecl.php.net/dbus-0.1.1
```

# OAuth

You must have a valid token within the scope "notifications" (not tested with the "repo" scope).

To do that, please refer to [the official documentation](http://developer.github.com/v3/oauth/#create-a-new-authorization).

# Usages
## ginof fetch
```shell
Usage:
 fetch [--force] api-token [persist-at]

Arguments:
 api-token   Your Github API access token
 persist-at  Where to store notifications cache (default: "/tmp/github-notifications")

Options:
 --force     Wether or not to bypass the cache and force fetching the notifiations
```

## ginof show
```shell
Usage:
 show [persist-at]

Arguments:
 persist-at  Where to read notifications cache from (default: "/tmp/github-notifications")
```
