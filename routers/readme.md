# Posts REST API

## Description
This project is a REST API for managing posts. It is developed in PHP and uses XAMPP as a local server. The database for the project is managed using phpMyAdmin.

## Features
- Retrieve a list of posts
- Retrieve a single post by ID
- Create a new post
- Update an existing post
- Delete a post

## Technologies
- **Backend:** PHP (Vanilla PHP)
- **Server:** Apache (via XAMPP)
- **Database:** MySQL (managed through phpMyAdmin)

## Project Structure
```
www/
├── controllers/        # Business logic
│   ├── authController.php
│   ├── postController.php
│   └── userController.php
├── modules/            # Database models
│   ├── postModel.php
│   ├── tokenModel.php
│   └── userModel.php
├── routers/            # API routes
│   ├── authRouter.php
│   ├── postRouter.php
│   ├── rootRouter.php
│   └── userRouter.php
├── sql/                # Database schema
│   └── posts_rest_api.sql
├── utils/              # Utility functions and configuration
│   ├── config.php
│   ├── connect.php
│   └── functions.php
├── vendor/             # Composer dependencies
├── .gitignore          # Git ignore rules
├── .htaccess           # Apache configuration
├── composer.json       # Composer configuration
├── composer.lock       # Composer lock file
└── index.php           # Entry point for the application
```

## API Endpoints

### Auth Routes
1. **Login**
    - **POST** `/login`
    - Body parameters: `email`, `password`

2. **Register**
    - **POST** `/register`
    - Body parameters: `email`, `password`, `name`

3. **Logout**
    - **POST** `/logout`

### Post Routes
1. **Get All Posts**
    - **GET** `/posts`

2. **Get a Post by ID**
    - **GET** `/posts/{id}`

3. **Create a New Post**
    - **POST** `/posts`
    - Body parameters: `title`, `body`

4. **Update a Post**
    - **PUT** `/posts/{id}`
    - Body parameters: `title`, `body`

5. **Delete a Post**
    - **DELETE** `/posts/{id}`

### User Routes
1. **Get All Users**
    - **GET** `/users`

2. **Get a User by ID**
    - **GET** `/users/{id}`

