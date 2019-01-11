FROM debian:stretch

ENV DEBIAN_FRONTEND="noninteractive"
RUN apt-get update -qq && apt-get install -qq nginx php-fpm php-rrd

COPY --chown=www-data:www-data htdocs /var/www/air-quality-info
COPY docker/nginx-default-site /etc/nginx/sites-available/default
COPY docker/run.sh /

ENV username ""
ENV password ""
ENV sensor_id ""
VOLUME /var/www/air-quality-info/data
EXPOSE 80

CMD [ "/run.sh" ]