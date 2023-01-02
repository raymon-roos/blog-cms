### Blogging CMS website

### What is this

This is a showcase of a school assignment about creating a blogging website, where blogs
can be submitted through a web form and are stored in MySQL database, normalised to the
third normal form. 

The point of the exercise was to practice working with a properly atomised database
structure, using joins and sub-queries to retrieve desired information. 

### What I turned it into

Having a fully functional database, I started working on organising the code,
implementing separation of concerns and creating abstractions. It became a long-winded
exercise in futility, implementing OOP-like design, without actually using classes (as
that was not within the scope of the exercise). The result is a beautiful maze of functions
calling functions. It became a fun little side track about exception handling and building
a sort of "template" system. 

### Requirements

- latest PHP version (8.1)
- HTTP server (like Apache)
- MySQL server (like MariaDB)

### Set up instructions

1. Place these files in the docroot of your webserver. (on Arch linux using Apache this is
   `/srv/http`, but it could be something like `/var/www`). 
2. Run `import.sql`
3. Start HTTP and SQL servers
4. You can now browse to the website
