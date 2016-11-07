USE CS143;

DROP TABLE IF EXISTS MovieActor;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS MovieDirector;
DROP TABLE IF EXISTS MovieGenre;

DROP TABLE IF EXISTS Movie;
CREATE TABLE Movie (
  id INT NOT NULL,
  title VARCHAR(100) NOT NULL, 
  year INT NOT NULL,
  rating VARCHAR(10),
  company VARCHAR(50) NOT NULL,
  PRIMARY KEY (id), -- PRIMARY KEY constraint #1: movie id must be unique
  CHECK(year > 1877) -- CHECK Constraint #1: movie year cannot be earlier than 1877
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS Actor;
CREATE TABLE Actor (
  id INT NOT NULL,
  last VARCHAR(20) NOT NULL,
  first VARCHAR(20) NOT NULL, 
  sex VARCHAR(6) NOT NULL, 
  dob DATE NOT NULL, 
  dod DATE,
  PRIMARY KEY (id), -- PRIMARY KEY constraint #2: actor id must be unique
  CHECK(sex='Male' OR sex='Female') -- CHECK contraint #2: gender must be either female or male
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS Director;
CREATE TABLE Director (
  id INT NOT NULL,
  last VARCHAR(20) NOT NULL,
  first VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  dod DATE,
  PRIMARY KEY (id) -- PRIMARY KEY constraint #3: director id must be unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE MovieGenre (
  mid INT NOT NULL,
  genre VARCHAR(20) NOT NULL,
  FOREIGN KEY (mid) REFERENCES Movie (id) -- Referential Constraint #1: if mid is in MovieGenre then it must in the movie id
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE MovieDirector (
  mid INT NOT NULL,
  did INT NOT NULL,
  FOREIGN KEY (mid) REFERENCES Movie (id), -- Referential Constraint #2: mid of MovieDirector must be in Movie id
  FOREIGN KEY (did) REFERENCES Director (id) -- Referential Constraint #3: did of MovieDirector must be in Director id
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE MovieActor (
  mid INT NOT NULL,
  aid INT NOT NULL,
  role VARCHAR(50),
  PRIMARY KEY (mid, aid), -- PRIMARY KEY constraint(additional): a (mid, aid) pair must be unique
  FOREIGN KEY (mid) REFERENCES Movie (id), -- Referential Constraint #4: mid of MovieActor must be in id of Movie
  FOREIGN KEY (aid) REFERENCES Actor (id) -- Referential Constraint: #5 aid of MovieActor must be in id of Actor
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Review (
  name VARCHAR(20), 
  time TIMESTAMP, 
  mid INT NOT NULL,
  rating INT NOT NULL,
  comment VARCHAR(500),
  FOREIGN KEY (mid) REFERENCES Movie (id), -- Referential Constraint #6: mid of movie being reviewed must be in the Movie id
  CHECK (rating > -1) -- CHECK contraint #3: rating must be more than -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS MaxPersonID;
CREATE TABLE MaxPersonID (
  id INT NOT NULL,
  PRIMARY KEY (id) -- PRIMARY KEY constraint(additional): MaxPersonID must be unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS MaxMovieID;
CREATE TABLE MaxMovieID (
  id INT NOT NULL,
  PRIMARY KEY (id) -- PRIMARY KEY constraint(additional): MaxMovieID must be unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
