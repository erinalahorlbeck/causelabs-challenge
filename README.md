# CauseLabs - Programming Challenge

The following challenge has two pieces, a simple backend and a small frontend. Each section will be tested independently and together.

# Backend

    <?php
    $people =
    '{"data":[{"first_name":"cody","last_name":"duder","age":38,"email":"codyduder@causelabs.com ","secret":"VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="},{ "first_name":"ladee","last_name":"linter","age":99,"email": "lindaladee@causelabs.com","secret":"cmVzb3VyY2UgdmlvbGF0aW9u"},]}'​;


## Instructions:

Given the above JSON, build a Laravel app that:

* accepts JSON POSTed to one of its routes
* expects the JSON to be structured like the above code
* parses the JSON into two variables
    * one variable should contain a comma-separated list of email addresses
    * the other variable should contain the original data but sorted by age descending and with a
new field on each record called "name" which is the first and last name joined
* persists the two variables described above into a database table; each POST should be a single row,
with one JSON column for each of the variables you generated, as well as columns for the time it was
POSTed and the source IP address
* has at least one test associated with it

Please deliver your code in a public GitHub, Bitbucket, or GitLab repo.

# Frontend

Create a simple Vue frontend that allows a user to dynamically add to the records included in the backend section. It can be a separate application, or sitting alongside the backend section. It should:

* Prepopulate the page with the two records noted above
* Allow the user to specify the first_name, last_name, age, email, and secret fields
* The secret should allow specification in plain-text, but submit in an encoded fashion
* The user should be able to delete a record they don’t want, except for the required two
* The data should POST to the route set up in the section above without issue

# Demonstration

Please set up a working demo site and send your application’s URL to us alongside your public Git repo.

  
   