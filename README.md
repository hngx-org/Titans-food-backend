# TEAM-TITANS-BACKEND

## Table of Contents

- [Installation-Setup](#installation-setup)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Github Team Collaboration Flow](#github-team-collaboration-flow)
  - [Setup a git repository](#setup-a-git-repository)
  - [Branching Strategy](#branching-strategy)
  - [Development Workflow](#development-workflow)
  - [Pull Requests](#pull-requests)
  - [Pulling Updates](#pulling-updates)
  - [Issue Tracking](#issue-tracking)
  - [Collaboration Best Practices](#collaboration-best-practices)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)

## Installation Setup

### Prerequisites

Before getting started, make sure you have the following prerequisites installed on your development environment

1. **PHP**: Laravel requires PHP 7.4 or higher. If you already have it installed you can check you current version by running `php -v` or `php -version` on your terminal.
2. **Composer**: Composer is a PHP package manager used for Laravel's dependency management. You can download composer from [Compoers](getcomposer.org)
3. **Web Server**: You can use any server of your choice for local development, such as [Apache](https://www.apachefriends.org/), [Nginx](https://www.nginx.com/) or Laravel built-in development server
4. **Database**: Laravel supports multiple database systems, including MySQL, SQLite, PostgreSQL and SQL Server. Ensure you have one of these databases installed and configured.
5. **Git**: Git is a version control system. It will be used for managing the project's source code.

### Installation

#### 1. Clone the Repository

- Clone the Laravery project repository for this project by copying the link below:
`https://github.com/hngx-org/Titans-food-backend.git`
- Go to you terminal, navigate to the server directory (such as apache).
- Change directory to the htdocs directory
- Clone the repository link you copied above using the command below:
    `git clone https://github.com/hngx-org/Titans-food-backend.git`

#### 2. Navigate to your cloned Project Directory

Change your current working directory to the cloned working directory using the command below:
`cd titans-food-backend`

#### 3. Install Project Dependencies

- Open your terminal or command line and navigate to the cloned project directory
- Run the following command to install all the dependencies using composer:
`composer install`

#### 4. Copy the Environment File

- Make a copy of the provided `.env.example` file and name it `.env` using the command below:
`cp .env.example .env`

#### 5. Configure the environment variables

- Go to the root directory of your laravel cloned project, you will find a file name `.env`
- Open the `.env` file in a text editor and configure your databse connnection details as follows:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
NB: The datase should be created already in your local database

#### 6: Generate an application key

- Open your terminal and navigation to the project root directory
- Run the following command to generate the application key: 
`php artisan key:generate`

#### 7: Migrate Database

- Open your terminal and navigate to the root directory
- Run the following command to create all database tables: 
`php artisan migrate`

#### 8: Start Development Server

- Open your terminal and navigate to the project root directory
- Run the following command to start the server: 
`php artisan serve`
This will start a development server at `http://127.0.0.1:8000`
- Open a browser and access the url provided (http://127.0.0.1:8000/api).

Your laravel api is now fully setup, running on your local machine.

## Github Team Collaboration Flow

### Setup a git repository

- Create a GitHub repository for the project.
- Ensure the repository is set to either public.

### Branching Strategy

- Create a new branch for each feature such as bug fix or task using the command below:
`git checkout -b branch-name`

#### Branching

##### Main Branch

- The **main** branch represents the stable version of the project
- Tasks are merged into the **main** branch after thorough testing and code reviews

##### Feature Branches

- Create **feature** branches for your task
- Branch names should be descriptive for easy identification (e.g. your slack username)

##### Bugfix Branches

- Create **bugfix** branches to address specific issues.
- Branch names should be clear (e.g., bugfix/issue-123).

### Development Workflow

- Write your code, make changes and run the necessary testing locally.
- Add and commit your changes using the following commands:
`git add .`
`git commit -m "Descriptive commit message"`
- Push your commited changes to the remote repository and set up an upstream(remote) branch at the same time as follows:
`git push -u origin branch-name`
- Subsequent push should be done to the remote repository using:
`git push origin branch-name`

### Pull Requests

- Create a pull request on Github when you are ready to merge your changes into the main branch.
- Request reviews from team members whom where assigned to perform review, to ensure quality code.
- Resolve any conflict and make necessary changes based on feedback.
- After approval and successful tests, the branch is merged into main.
- The branch is delete if it's no longer needed

### Pulling Updates

- Pull from the default (main) branch using:
`git pull origin main`

- Pull from your upstream branch using:
`git pull origin branch-name`

### Issue Tracking

- GitHub Issues is used to track and manage tasks, bugs, and feature requests.
- Issues are clearly define based on its context, and its priority.

### Collaboration Best Practices

- Communicate with your team using project management tools, slack, chat platforms or meetings
- Use meaningful commit messages and follow the project coding style to enhance code readability across the team
- Document your code and the project's architecture for better team understanding
- Keep sensitive information such as database credentials, API Keys, secure and out of version control using `.gitignore`
- Backup your code and database regularly

## API Endpoints
- API Endpoints can are documented [here](https://github.com/hngx-org/Titans-food-backend/blob/main/DOCUMENTATION.md)
    > The endpoints can be tested on the Project Swagger Docs. Open your browser and access the API docs from the url (https://titans-food-backend.onrender.com/api/docs). *NB: To access authenticated routes, Ensure to login, using the route `api/v1/auth/user/signin` then copy the `access_token` from the login response and input it in the Swagger authorization BearerAuth by clicking on the 'Authorize' button at the top right of the screen.*

## Contributing

- codelikesuraj
- Shola Harry
- theblackwrist
- Kitti Solomon
- J-Engineers
- Rahmanakorede
- Emekaenyinnia
- kayxleem
- engr state
- Favour Bassey
- Jevans Stanley
- chriswax
- edoka
- the_last_php_bender
- Ruthiejay
- GoodnewsPerfect
- Akinyooye Femi
- teerka
- mrprotocoll
- james arua
- piouskenny
- Leakey_Nyamweya
- 7j4n1
- DevMenor
- Greegman
- Apple
- Adeleke_Ogunsona
- JesseJason
- GiftieO
- Daniel Dunu
- TheMaxwellJames
- bahd_dev
- RichmanLoveday
- splendidabbey
- ruxy1212

With this guide, you have successfully installed Laravel, initialized your project, and set up a Github team collaboration work flow.
