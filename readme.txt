Project criteria we met:
1. Add an actor/director: 
We implemented an 'Add Actor/Director' page where user can add a new actor/director with specified first/last name, gender, and date of birth. These are required fields to add a new person. Date of Death is an optional field for the new person added.

2. Add a movie:
We implemented an 'Add Movie' page where user can add a new movie with specified title, company, year and MPAA rating, genre. All are required fields and we check the input for 'year' so that it is a number >1877, which is consistent in our table definition in 'create.sql'.

3. Add actor-movie relation:
We implemented an 'Add Actor to Movie' page where user can add an actor to a movie with a speficied role. The user has to select the movie and actor from drop down tables and input the role for the actor in this movie. All three fields are required.

4. Add director-movie relation:
We implemented an 'Add Director to Movie' page where user can add a director to a movie. Two fields are required: the movie and actor. Both are selected from drop down tables.

5. Search a movie/actor:
We implemented a search box at the top of each of the pages so the user can search movies/actors that contains a keyword or multiple keywords. One table will show all the movies containing the keywords and another table will show all actor names containing the keywords. Each of the movie/actor name is a link to a browsing page for the movie/actor. By clicking the link, the user will be able to browse the detailed info for the movie/actor.

6. Browse a movie/actor:
We implemented 'show_actor_info.php' for browsing the actor info. There will be a table showing the actor's id, name, gender, date of birth/death. Another table will show all the movies the actor plays. The movie names in the second table is also a link to the browsing page for movies ('show_movie_info.php'). By clicking it the user can see the info for the movie.

We implemented 'show_movie_info.php' for browsing the movie info. There will be a table showing all the details about this movie and a second table showing the actors in this movie and their corresponding roles in this movie. The actor names are also links to the browsing page for actors('show_actor_info.php'). If there are some existing reviews for this movie, there is a 'Average Rating' showing the average of all the ratings by reviewers. And there will be table showing reviewer's name and their corresponding rates and comments for this movie. The reviewer's name and comments can be null. At the end there will be a link to add comments for this movie. By clicking it the user will be directed to the 'add_comment.php' page.

7. Add commments:
We implemented 'add_comment.php' page for the user to input his/her name, the rating (selected from 1-5) and the comments (input text) for the movie. The rating is required field.

Additional features:
1. We add error message beside the required fields if the input for these fields are empty when the user submit the form.

2. The inputs from the user will remain if it is directed back to the current page after a submission of a failure.

We worked on this project in team. Yuanqi implemented the UI layout and 'add_person.php', 'search.php', and 'show_actor_info.php' pages. Kaiwen implemented the 'add_comment.php', 'add_actor_to_movie.php', 'add_director_to_movie.php' and 'show_movie_info.php' pages. 
Currently we use Google Drive to update our work. In the future we may sync up our updates more efficiently using other tools like Github.

