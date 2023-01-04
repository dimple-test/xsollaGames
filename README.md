# xsollaGames
Add, Edit, Delete and many other functionalities for game list

Comes with :

- Web app: php:7.4
- Server: Nginx:1.23
- Database: Mysql: 8.0

## Getting Started By Setting Up Environment

- git clone https://github.com/dimple-test/xsollaGames.git
- cd xsollaGames
- docker-compose up -d

Now access http://localhost/app/games in your browser

Some other useful commands :

- docker-compose up -d => To build images and create containers
- docker images => To get list of images
- docker rmi <image_id> => To remove images (multiple images seperated by space)
- docker ps -a => To get list of containers with its status and other information
- docker stop <container_id> => To stop containers (multiple containers seperated by space)
- docker rm <container_id> => To remove stopped containers (multiple containers seperated by space)
- docker exec -it <container_id> bash => To run container in bash