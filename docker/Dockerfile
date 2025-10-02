FROM webdevops/php-nginx:8.4

COPY price_load_cron /etc/cron.d/price_load_cron
RUN chmod 0644 /etc/cron.d/price_load_cron
RUN crontab /etc/cron.d/price_load_cron

CMD ["tail", "-f", "/dev/null"]
