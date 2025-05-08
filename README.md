# Books on Web

Students:
- Radu Stefan-Matei (2A1)
- Cucos Tudor-Mihail (2A1)

In apache config, enable:
```
<Directory "/srv/http">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```
and add:
```
LoadModule rewrite_module modules/mod_rewrite.so
```
