FROM nginx:1.19.10

# copy config nginx
ADD vhost.conf /etc/nginx/conf.d/default.conf

# copy code of app (necessary for deploy)
WORKDIR /var/www
COPY ./public /var/www/public