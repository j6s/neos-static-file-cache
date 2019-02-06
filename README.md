# Neos Static File Cache ![](https://api.travis-ci.org/j6s/neos-static-file-cache.svg?branch=development)

This plugin allows for simple static file caching by leveraging webserver
rewrite capabilities to circumvent calling PHP altogether when reading from cache.

The plugin writes all resulting pages to the disk. The webserver is configurred to search
for that file before calling PHP - if it exists the simple file is served making cache hits
as fast as statically hosted pages.

Note, that these benefits do not come without their disadvantages: Pages with dynamic content
(that is, pages that are not the same if you reload them multiple times) by definition cannot
be cached as static pages.

## Warning: Do not use in production
The plugin in it's current state is not production ready - currently it is only a development
version with a good amount of problems.

## Installation
After installing the plugin in your Neos system you will also need to configure the webserver
to load the static files.

### Nginx
```
location / {
    try_files $uri $uri/ @staticfilecache;
}

location @frontend {
    try_files $uri $uri/ /index.php$is_args$args;
}

location @staticfilecache {
    # Perform an internal redirect to Neos if any of the required
    # conditions for static file cache don't match
    error_page 405 = @frontend;
    
    # Query String needs to be empty.
    if ($args != '') {
        return 405;
    }

    # Do not attempt to server from cache when the
    # request is not a GET or HEAD request.
    if ($request_method !~ ^(GET|HEAD)$ ) {
      return 405;
    }

    try_files /_StaticFileCache/${scheme}/${host}${uri}/index.html
              /_StaticFileCache/${scheme}/${host}${uri}
              =405;
}
```

### Apache
- TODO

## TODO
- Detect if a user is logged into the backend. Logged-in users should not get cached pages.
- Add checkbox to pages to disable caching.
- Automatically detect if a node on the page is a plugin and disable caching for the page (is that even possible?).
- Hook into `cache:clear` and clear the static file cache directory.
