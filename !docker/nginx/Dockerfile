FROM nginx:latest

COPY hosts/app.local.conf /etc/nginx/conf.d/app.local.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]