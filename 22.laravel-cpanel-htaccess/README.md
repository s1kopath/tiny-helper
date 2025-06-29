# 22. laravel cpanel htaccess

### usage

```code
RewriteCond %{REQUEST_URI} !/public
RewriteRule ^(.*)$ public/$1 [L]
# Direct all requests to /public folder
```
