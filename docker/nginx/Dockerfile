ARG NODE_IMAGE=node:24-bookworm-slim

FROM ${NODE_IMAGE} AS frontend-builder

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

FROM nginx:1.30.4-alpine

RUN mkdir -p /var/www/html /var/cache/nginx /var/log/nginx \
    && chown -R nginx:nginx /var/www/html /var/cache/nginx /var/log/nginx

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY --from=frontend-builder --chown=nginx:nginx /var/www/html/public /var/www/html/public

RUN nginx -t \
    && chown -R nginx:nginx \
        /tmp/nginx.pid \
        /tmp/client_temp \
        /tmp/proxy_temp \
        /tmp/fastcgi_temp \
        /tmp/uwsgi_temp \
        /tmp/scgi_temp

EXPOSE 8080

USER nginx

CMD ["nginx", "-g", "daemon off;"]
