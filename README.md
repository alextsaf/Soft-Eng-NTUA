# InterTolls

![Python](https://img.shields.io/badge/python-3670A0?style=for-the-badge&logo=python&logoColor=ffdd54)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![NodeJS](https://img.shields.io/badge/node.js-6DA55F?style=for-the-badge&logo=node.js&logoColor=white)
![Express.js](https://img.shields.io/badge/express.js-%23404d59.svg?style=for-the-badge&logo=express&logoColor=%2361DAFB)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)


Github repository of InterTolls software.

# Installation

# Docker
The app is "Dockerized" and can get installed very easily using the Docker app.

## Installation with Docker:

1. Open a terminal at the home of the repository
2. Run:
```bash
docker-compose up
```
3. Check that all the images and containers have been created. When the installation has stopped, check that all the containers exist:
```bash
docker ps
```
There have to be 4 (if not, run the compose command once again):
  - MariaDB
  - API
  - PHP-Apache
  - CLI

You can now use the app. Here you can see the port each service listens to in the `HOST`:

  | SERVICE | PORT |
  | ------- | ---- |
  | MariaDB | 3306 |
  | API | 9103 |
  | Frontend - Apache | 8000 |
  | CLI | *NO PORT NEEDED* |
