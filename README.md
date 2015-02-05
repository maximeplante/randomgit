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
* Set a cron job to execute `src/job/updateCache.php` every 5 minutes

### ToDo
* Optimize database transactions in `src/lib/RepoCache.php`
* Beautify index.php
* Repository filtering (number of stars/forks, ...)
* Your idea!

## How does is work?
Each 5 minutes, `updateCache.php` generates a random alphanumeric string of 2 characters. Then it uses the GitHub Search API to find repositories by using the random string as the query and it stores them (with their readme) in the database. Then, `index.php` does a request to `ajax/random.php` to get a list of random repositories from the database and displays their readme to the user. When the database becomes really big, `updateCache.php` removes a bunch repositories from the database.

## Questions
I you have any question, create an issue with the `question` tag.

## License
See `LICENSE`