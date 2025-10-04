FROM nginx:1.19.10

ADD vhost.conf /etc/nginx/conf.d/default.conf