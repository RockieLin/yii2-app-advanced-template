#!/bin/bash
mkdir -p /etc/supervisord.d/conf && mkdir -p /etc/supervisord.d/log && \
supervisord -n -c /etc/supervisor/supervisord.conf