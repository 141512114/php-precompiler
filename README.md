# PHP Precompiler/Caching
The "PHP Precompiler/Caching" project explores efficient ways to manage, optimize, and serve PHP files with improved performance.\
While not aiming to revolutionize the market, the project focuses on experimenting with practical techniques such as precompilation, smart caching, and minimal runtime overhead.
Its primary goal is to serve as a testing ground for personal development and to better understand how PHP performance can be enhanced in controlled environments.

Over time, the project may evolve as insights and challenges emerge.

## Planned
A concise list of things planned for this project and how it might be used.

- It should be possible to generate ready-to-use php files via the command line with a command such as ```phppc compile```.
- The program should automatically generate those files on demand (e.g. by a user triggering it through visiting the page xyz).
- The program should be lightweight and easy-to-use. To enable on-demand-caching you just throw in a
  
  ```php
  require_once('[project_dir]/php_precompiler/auto_cache.php');
  ```
  somewhere near the top of your project and it automatically does its job with no further configuration (**!!! highly unlikely !!!**).

This project will essentially be a more primitive form of caching (I believe), so it will be inferior to its "competitors".
