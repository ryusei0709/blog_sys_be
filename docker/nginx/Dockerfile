FROM nginx:1.25-alpine

# Setting the timezone
# https://wiki.alpinelinux.org/wiki/Alpine_setup_scripts#setup-timezone
RUN apk --update add alpine-conf && \
    setup-timezone -z Asia/Tokyo && \
    apk del alpine-conf && \
    rm -rf /var/cache/apk/*

COPY ./docker/nginx/conf/default.conf.ecs /etc/nginx/conf.d/default.conf

COPY ./src /var/www/html

EXPOSE 80
