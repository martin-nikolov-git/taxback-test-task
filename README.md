# Taxback Test task
This is an implementation of the library task provided by Taxback. Here we are going to show some OOP patterns, how we can write some recursion algorithms, simple SQL statements and architecture knowledge.


## 1. Instalation

To initialize the platform you need to follow these simple steps:

 - Go to the provided .env file and setup your DB variables
        DB_HOST=localhost
    	DB_PORT=5432
    	DB_USERNAME=postgres
    	DB_PASSWORD=test1234
    	DB_DATABASE=library
  -  Now to create the tables in your db run the following: 
 `php create-db.php`
  - After that all you have to do is insert the library. If you want to insert another library folder, you can change the folder from the .env file. Use the following command to scan the chosen folder
 `php insert-books.php`

And you're done! Open up the folder in xampp, and the index.php should be working.
## 2. Things to note
I like having all my constants in one place, that is why I spend some time making the EnvReader class, even if it is a simple singleton you can customize a lot with it.
The BookRepository interface is made so that later, we can change just the implementation. I had to do that in my previous project, for when we needed to change the API.
The recursive part was made a lot easier due to the RecursiveDirectoryIterator and RecursiveIteratoIterator objects which are native for PHP.
## 3. Things that can be improved
1. There are probably ways to improve the View models, from them I'm least happy, but I didn't have the time to make a proper templating script
2.  Reading over the Iterator manual from php.net there could be something to improve the recursive and reading part of the task.
3.  Config files are going to make the code a lot cleaner and remove any magic numbers from the codebase.