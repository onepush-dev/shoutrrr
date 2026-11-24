#!/bin/bash
# Runs after serversideup's Laravel automations (migrations). Creates the instance owner once.
cd /var/www/html || exit 0
php /railway/create-owner.php || echo "[railway] owner bootstrap failed (continuing)"
