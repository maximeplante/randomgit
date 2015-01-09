RandomGit.com
=========

[RandomGit.com](http://randomgit.com) - A website to discover new GitHub repositories

## How to contribute
I need your help to make a better RandomGit.com!

### Setup
All you need is in the `setup` folder

#### Step by Step
* Install a HTTP server with PHP and MySQL
* Create a database and import `schema.sql` in it
* Fill `config.php` with the database credentials and move it to `src/config.php`
* Set a cron job to execute `src/Scripts/updateRepo.php` every 5 minutes
* `src/html` is the public folder

### ToDo
* Optimize database transactions in `src/Classes/RepoCache.php`
* Beautify index.php
* Repository filtering (number of stars/forks, ...)
* New AJAX interface
* Your idea!

## How does is work?
Each 5 minutes, `updateRepo.php` generates a random alphanumeric string of 2 characters. Then it uses the GitHub Search API to find repositories by using the random string as the query and it stores them in a database. Then, when a user visits `random.php`, the script redirects the user to a random repository in the database. When the database becomes really big, `updateRepo.php` removes a bunch repositories from the cache.

## Questions
I you have any question, create an issue with the `question` tag.

## License
See `LICENSE`