Vanilla Forums by default is English-only. You can install [locale packs](http://vanillaforums.org/addon/browse/locales/recent/2/) to make it something-else-only. You can also install [Multilingual](http://vanillaforums.org/addon/multilingual-plugin), which lets users choose which language they see.

I believe the best way to handle localization is to have different languages at different URLs. That's what this plugin does. `/en/forum/` will load the forum in English, `/fr/forum/` will load it in French.

This plugin has been tested with nginx. Configuration for other web servers is not provided. If you get it working with other web servers, please let me know and I can add sample configuration to this document.

## Installation

1. Install and enable all the languages you want to support.
2. Grab the plugin and put it in the `plugins` folder. Enable it.
3. Assuming a forum at the path `/forum/`, and the intention is to route to `/(locale)/forum/`, add this to your nginx config:
  ```nginx
  # Add trailing slash to paths like /forum and /en/forum
  rewrite ^(/[a-z][a-z](\-[A-Z][A-Z])?)?/forum$ $request_uri/ permanent;
  
  # Rewrite locale path segment to be a request parameter
  location ~* ^(?:/([a-z][a-z](?:\-[A-Z][A-Z])?))/forum/ {
    set $args $args&locale=$1;
    rewrite ^(?:/[a-z][a-z](?:\-[A-Z][A-Z])?)/forum/(.*) /forum/$1 last;
    location ~ /forum/.*\.php {
      # include locale as path segment
      fastcgi_param PHP_VALUE "auto_prepend_file=$document_root/forum/plugins/LanguageByPath/prepend.php";
    }
  }
  ```
4. Edit `config.php` in LanguageByPath's directory. The only configuration available is to map from your locale code to Vanilla's.

## Acknowledgements

Thanks to x00 on the Vanilla Community Forum for help with the prepend PHP.
