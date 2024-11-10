## How run project

Before you begin, make sure you have the following installed on your machine:

- [Docker](https://www.docker.com/get-started) (includes Docker Compose)
- [Git](https://git-scm.com/)

## Setup and Installation
1. Clone this repository
2. cp .env.example .env
3. docker-compose up -d
4. php artisan passport:install and take PASSPORT_PERSONAL_ACCESS_CLIENT_ID ,PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET put it in .env

## Don't Forget add twilio credentials
```
TWILIO_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
```

