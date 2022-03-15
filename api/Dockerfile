FROM node:alpine

WORKDIR /api

COPY package.json /api/

RUN npm install

COPY . /api/

ENV PORT=9103

EXPOSE 9103

CMD ["node", "index.js"]
