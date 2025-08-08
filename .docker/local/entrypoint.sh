#!/bin/bash
set -e

touch /var/log/cron.log

exec supervisord -c /etc/supervisor.conf