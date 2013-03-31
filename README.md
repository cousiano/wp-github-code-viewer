wp-github-code-viewer
=====================

Wordpress github code viewer. That use geshi.

Usage
-----
[gcv url='myGithubUrl' lang='geshiLang']

*Example* 
[gcv url='https://github.com/cousiano/wp-github-code-viewer/blob/master/wp-github-code-viewer.php' lang='php']


Troubleshooting
---------------
Fatal error: Cannot redeclare class GeSHi in (...)
You must desactivate others plugin who use GeSHi first.

If you want to keep both plugin, you can comment the following line:
```php
include_once( 'geshi/geshi.php' );
```
