ToDoList
========

Project #8 : Improve an existing project
https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

A todo application made with Symfony where users can sign-up to create/edit/delete tasks and mark them as complete or to complete.
A system of permissions allow users to edit and manage datas and roles of other users.  
Made during my formation with OpenClassrooms in february - april 2020. The goals was to improve the application, especially the quality of code by adding unit and functional tests, using Codacy and blackfire for performances. Also, it was requested to create documentation to have good practices to work with other developers.


Code quality reviewed by Codacy :
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/21fc7e2ee549408894f61b5e7a0ba68c)](https://www.codacy.com/manual/ClementThuet/ToDo-Co?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ClementThuet/ToDo-Co&amp;utm_campaign=Badge_Grade)

## Getting Started

### Requirements
To install the project you will need :
* An Apache server >=2.4
* PHP >= 7.1 (7.3 is needed to use the free version of blackfire)
* MySQL or another database of your choice<br> 

I recommend to use WampServer as I did.

### Installing
You can get the project by using git clone (If you don't know how to do it, more info here : https://git-scm.com/book/it/v2/Git-Basics-Getting-a-Git-Repository)
```
$ git clone https://github.com/ClementThuet/ToDo-Co.git
```
Then you need to execute `composer install` into the project folder to install the dependencies.<br>
If you don't have composer you can get it here https://getcomposer.org/doc/00-intro.md

I defined a virtualhost which point to my public folder in a way to get URLs like "http://todoco/users/create".<br>
With wampserver you can configure it by going to : Wampserver icon (in the taskbar) => Your virtualhosts => virtualhosts management. <br>
You should have this line after configuration :
```
ServerName : snowtricks - Directory : c:/wamp64/www/todoco/public
```

### Database 
Create a new database by executing the command php bin/console doctrine:database:create. Then, execute the command php bin/console doctrine:schema:update --force in order to create the different tables based on the entity mapping.

### Database configuration
Configure your database according to your personal configuration in .env. For me:

```
DATABASE_URL=mysql://root:@127.0.0.1:3306/Todoco
```

You can now load fixtures to get some tasks and some users to manage. There is a special anonymous user who is used to "own" the task which didn't had any user attached.

```
php bin/console doctrine:fixtures:load
```

That's all, you can now access to Todoco and sign in to add your first task. Enjoy !

### Tests
To run the unit tests use :
```
 ./vendor/bin/phpunit
```

To run the functionnal tests :
```
 ./vendor/bin/behat
```

## Author
**Clément Thuet**
* https://www.linkedin.com/in/cl%C3%A9ment-thuet/
* https://github.com/ClementThuet/

