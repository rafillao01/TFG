# Usa una imagen base de Nginx
FROM nginx:latest

# Copia el archivo Excel desde tu sistema local al contenedor
COPY ./empresas.xlsx /var/probar/empresas.xlsx