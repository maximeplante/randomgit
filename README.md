RandomGit.com
=========

[RandomGit.com](http://randomgit.com) - A website to discover new GitHub repositories

## How to contribute
I need you help to make a better RandomGit.com!

### Setup
All you need is in the `setup` folder

#### Step by Step
* Install an HTTP server with PHP and MySQL
* Create a database and execute `schema.sql`
* Fill `config.php` with the database credentials and move it to `src/config.php`
* `src/html` is the public folder

#### ToDo
* Add more comments to the code
* Beautify index.php
* Support repository filtering (programming language, number of stars/forks, ...)
* Your idea!

## How does is work?
Each 5 minutes, `updateRepo.php` generates a random alphanumeric string of 2 characters. Then it uses the GitHub Search API to find repositories by using the random string as the query and it stores them in a database. Then, when a user visits `random.php`, the script redirects the user to a random repository in the database. When the database becomes really big, `updateRepo.php` empties it.

## Questions
I you have any question, create an issue with the `question` tag. Having a question should not be a wall to contribution.

## License
Read `LICENSE`