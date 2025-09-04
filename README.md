Tech Blog Post System
This is a straightforward, functional blog application built with PHP and a MySQL database. It allows users to create, view, edit, and delete blog posts. The system also includes a simple tagging feature to categorize posts.

This project is an excellent starting point for learning the fundamentals of web development with PHP, including database interaction (CRUD), form handling, and basic application structure.

Features
Create Posts: Write and publish new blog posts with a title and content.

Read Posts: View a list of all published posts on the main page.

Update Posts: Edit the title, content, and tags of existing posts.

Delete Posts: Remove posts from the blog.

Tagging System: Assign one or more predefined tags to each post for categorization.

Clean UI: A simple, clean user interface styled with CSS.

Technologies Used
Backend: PHP

Database: MySQL

Frontend: HTML, CSS

Web Server: Apache (via MAMP, XAMPP, or similar)

Requirements
A local web server environment (e.g., MAMP, XAMPP).

PHP 7.4 or newer.

MySQL or MariaDB.

A web browser.

Installation and Setup
Follow these steps to get the project running on your local machine.

1. Set Up the Database
You need to create a database and the required tables for the application to store its data.

Start your MAMP/XAMPP servers (Apache and MySQL).

Open phpMyAdmin from your MAMP/XAMPP control panel.

Create a new database named blog_app.

Select the blog_app database and navigate to the SQL tab.

Run the following SQL commands to create the tables:

-- Create the 'posts' table to store blog articles
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the 'tags' table to store available tags

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Create the 'post_tags' linking table for the many-to-many relationship

CREATE TABLE post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
-- Insert some data into tags table
INSERT INTO tags (name) VALUES ('PHP'), ('HTML'), ('CSS'), ('JavaScript'), ('Technology');


2. Configure the PHP Connection
Place all the project files (index.php, post.php, etc.) into your web server's root directory (e.g., C:/MAMP/htdocs/blog or /Applications/MAMP/htdocs/blog).

Open the db_connect.php file in a code editor.

Update the database credentials to match your MAMP/XAMPP setup. The defaults for MAMP are often:

3. Run the Application
Open your web browser and navigate to the project directory. For example:
http://localhost/blog or http://localhost:8888/blog

You should now see the blog's main page.

Usage
View Posts: The main page (index.php) lists all posts, sorted by the newest first.

Create a Post: Click the "Create New Post" button. Fill out the title, content, select tags, and click "Post".

Edit a Post: Click the "Edit" link on any post. You can update its details and change its tags.

Delete a Post: Click the "Delete" link. A confirmation prompt will appear before the post is permanently removed.