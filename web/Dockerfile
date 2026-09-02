FROM node:22-slim AS build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
ARG VITE_API_BASE_URL=http://localhost:4000
ENV VITE_API_BASE_URL=$VITE_API_BASE_URL
RUN npm run build

FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
# Auto-processed by the base image's docker-entrypoint.d envsubst step into conf.d/default.conf.
COPY docker/nginx.conf.template /etc/nginx/templates/default.conf.template
# NOT auto-processed (lives outside /etc/nginx/templates) — only rendered by 40-enable-https.sh
# when a cert/key pair is actually mounted in, so an empty ssl_certificate directive can
# never make nginx fail to start.
COPY docker/nginx-ssl.conf.template /etc/nginx/custom-templates/nginx-ssl.conf.template
COPY docker/40-enable-https.sh /docker-entrypoint.d/40-enable-https.sh
RUN chmod +x /docker-entrypoint.d/40-enable-https.sh
EXPOSE 80 443
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
  CMD wget -q -O- http://localhost/health || exit 1
