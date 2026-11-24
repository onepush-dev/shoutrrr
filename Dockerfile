# Shoutrrr on Railway: the official ghcr.io/coollabsio/shoutrrr:1.5.9 image (Apache-2.0), application unmodified.
# Railway layer: storage and the SQLite database live on one volume at /var/www/html/storage, runs as root so it can
# write the root-owned Railway volume, trusts Railway's proxy with HTTPS URLs, and an entrypoint step creates the
# instance owner (with a verified email and a default workspace) from SHOUTRRR_ADMIN_PASSWORD on first boot, so
# nobody else can claim the instance and no SMTP is needed for verification.
FROM ghcr.io/coollabsio/shoutrrr:1.5.9
LABEL org.opencontainers.image.source="https://github.com/onepush-dev/shoutrrr"
USER root
COPY --chmod=755 railway/60-railway-owner.sh /etc/entrypoint.d/60-railway-owner.sh
COPY railway/create-owner.php /railway/create-owner.php
ENV APP_ENV=production \
    APP_DEBUG=false \
    OCTANE_HTTPS=true \
    TRUSTED_PROXIES=* \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/www/html/storage/database/database.sqlite \
    CACHE_STORE=database \
    QUEUE_CONNECTION=database \
    SESSION_DRIVER=database \
    QUEUE_WORKER_ENABLED=true \
    SCHEDULER_ENABLED=true \
    SELF_HOSTED=true \
    MAIL_MAILER=log \
    SHOUTRRR_ADMIN_EMAIL=admin@example.com \
    SHOUTRRR_ADMIN_NAME=Admin
EXPOSE 8080
