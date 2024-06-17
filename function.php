<?php 
require_once "data.php";

function sanitize($data) {
    return array_map(function ($value) {
      return htmlspecialchars(stripslashes(trim($value)));
    }, $data);
  }

  // movie_title is required | fewer than 255 characters
  // director is required | characters and spaces only
  // year is required | numeric only
  // genre is required | must be in the list of genres
  function validate($movie) {
    $fields = ['movie_title', 'director', 'year', 'genre_title'];
    $errors = [];
    global $genres;

    foreach ($fields as $field) {
      switch ($field) {
        case 'movie_title':
          if (empty($movie[$field])) {
            $errors[$field] = 'Movie title is required';
          } else if (strlen($movie[$field]) > 255) {
            $errors[$field] = 'Movie title must be fewer than 255 characters';
          }
          break;
        case 'director':
          if (empty($movie[$field])) {
            $errors[$field] = 'Director is required';
          } else if (!preg_match('/^[a-zA-Z\s]+$/', $movie[$field])) {
            $errors[$field] = 'Director must contain only letters and spaces';
          }
          break;
        case 'year':
          if (empty($movie[$field])) {
            $errors[$field] = 'Year is required';
          } else if (filter_var($movie[$field], FILTER_VALIDATE_INT) === false) {
            $errors[$field] = 'Year must contain only numbers';
          }
          break;
        case 'genre_title':
          if (empty($movie[$field])) {
            $errors[$field] = 'Genre is required';
          } else if (!in_array($movie[$field], $genres)) {
            $errors[$field] = 'Genre must be in the list of genres';
          }
          break;
      }
    }


    return $errors;
  }
//getMovie
//retrieve a single movie
//grab movie id to pass data
//global = use function
function getMovie($movie_id) {
    global $movies;
    return current(array_filter($movies, function ($movie) {
        return $movie['movie_id'] == $_GET['id'];
      }));
}

//addMovie
//add a new movie
function addMovie($movie) {
    global $movies;

    array_push($movies,[
        'movie_id' => end($movies)['movie_id'] +1,
        'movie_title' => $movie['movie_title'],
        'director' => $movie['director'],
        'year' => $movie['year'],
        'genre' => $movie['genre']
      ]);
  
      $_SESSION['movies'] = $movies;
}

//updateMovie
function updateMove($movie){
    global $movies;
    
    $new=[
        "movie_id" =>  $movie["movie_id"],
         "movie_title" => $movie["movie_title"],
        "director" =>$movie["director"],
        "year" => $movie["year"] ,
        "genre" => $movie["genre"],
    ];
      
    $movies = array_map(function($movie) use($new) {
          if($movie["movie_id"] === $new["moive_id"]){
            return $new;
          }
          return $movie;
        }, $movies);
      
    $_SESSION['movies'] = $movies;
}

//deleteMovie
function deleteMoive($movie_id) {
    global $movies;
    $index = array_key_first(array_filter($movies, function ($movie) use ($movie_id) {
        return $movie['movie_id'] == $movie_id;
      }));
      unset($movies[$index]);

    $_SESSION['movies'] = $movies;
}

//sanitize 
//clear the data of all unnecessary or dangerous characters
// function sanitize($data){
//      return array_map(function($value){
//         //trim() remove spaces
//         //stripslashes remove slashes
//         //htmlspecialchars converts html into a format// non-executable format
//         return htmlspecialchars(stripslashes(trim($value)));
//      }, $data);

// }

//validate 
//movie_title is required
//director
//year
//genre
