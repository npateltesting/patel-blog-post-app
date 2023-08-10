# Blog Post Api App


## Features

>Created an API endpoint (GET /posts) that retrieves a list of blog posts.
>Each post object should include its comments.
>Implement pagination for the list of posts, with a default of 10 posts per page.
>Allow filtering posts by a specific keyword in the title or content.

# System Requirements
- php >= 8.1
- composer
- Symfony
- lamp for linex or wamp or xamp for windows
- postman (for testing API)
## Development


```sh
cd ./to-your-project-dir
```

```sh
composer install
```
- update database connection info in .env file

```sh
php bin/console app:generate-posts
```
- run above commmad to generate 100 dummy records of post with comments
- once data is generated run below command
```sh
symfony server:start
```
- once server started start postman and test api with GET request in postman with this request 127.0.0.1:8000/api/posts

