FROM node:22-alpine
WORKDIR /app
COPY .output /app/.output
COPY server/mail/layouts /app/server/mail/layouts
EXPOSE 3000
CMD ["node", "/app/.output/server/index.mjs"]