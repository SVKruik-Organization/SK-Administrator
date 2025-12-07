FROM node:22-alpine
WORKDIR /app
COPY .output /app/.output
EXPOSE 3000
CMD ["node", "/app/.output/server/index.mjs"]