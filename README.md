# Lomas padel

Aplicación web para la gestión de torneos de pádel. Incluye registro de torneos, calendario de partidos, visualización de la primera etapa de los brackets y gestión manual de resultados. Los jugadores podrán seleccionar en qué torneo participar y realizar pagos de inscripción.


## Tecnologias usadas en el proyecto

Describiremos las tecnologías usadas en el proyecto, la arquitectura con la que se construyo, frameworks y versiones con el que se desarrollo el proyecto

### Arquitectura general
* **Tipo de aplicación:** Monilito(Frontend + backend integrados)
* **Estructura:** Modelo-Vista-Controlador
* **Framework base:** Laravel 12+
* **Puente de comunicación entre backend y frontend:** Inertia.js
* **Sistema de autenticación:** Laravel

### Backend
* **Lenguaje:** PHP 8.4+
* **Manejador de base de datos(SGBD):** MySQL 8.4+

### frontend
* **Framework:** Vue 3
* **Puente de comunicación entre backend y frontend:** Inertia.js
* **Compilador:** Vite
* **Estilos:** Tailwind 4

### Integraciones externas
* **Pasarela de pagos:** Stripe Elements

## Arquitectura de datos

En este apartado describiremos las tablas de la base de datos de la aplicación y las funcionalidades de las columnas

### Diagrama entidad-relación

![Diagrama entidad relación](https://drive.usercontent.google.com/download?id=1J8Wye-qesU7xmQsb2v2teZwLf-kT51qF&export=view&authuser=0)

para la versión actual del sistema, el registro de los participantes del equipo se hace de forma individual y no por equipos, esto esta como nota que en la segunda interacción con el desarrollo del sistema esto cambiara

### Entidades de la BD

|Entidad |Uso |
|------------------|---|
|Users             |Entidad de los usuarios del sistema|
|Subscriptions     |Entidad de las subscripciones a las membresias que tienen los usuarios y las membresias es una relacion n:m con atributos en la relación|
|Memberships      |Membresias ofrecidas por Lomas padel, entidad que cambia el tiempo de duración de la membresía, precio y descripción de los beneficios| 
|Places|Entidad donde registramos el lugar del jugador, el torneo al que pertenece, si esta libre el lugar o no y ID el pago del lugar |
|Payments|Entidad que registra los pagos tanto de las membresías como de los lugares del torneo|
|Torurnaments |Entidad que tiene los datos basicos del torneo, tanto los lugares disponibles, modalidad, fecha de evento, precio de admisión, los resultados y status del tornoe|


A partir de aqui, se describiran entidades no usadas en el proyecto pero que estan planeadas para extender funcionalidades del sistema, como actualización del partido, marcadores, etc

|Entidad |Uso |
|---|---|
|Teams|Entidad donde se relacionaran los jugadores con el equipo|
|Matches|Entidad que registrara los partidos, resultados, la ronda jugada y su siguiente partido, esto permitira construir un bracket con información de los partidos, es una tabla autorreferencial


### Construcción de la base de datos

Para la construcción de la base de datos, usamos el sistema de migraciones de laravel, para poder construir la base de datos en un entorno local o de producción debemos seguir los siguientes pasos:

1. Crear una base de datos en el manejador de base de datos a usar, para este caso se usa MySQL
2. Configurar las variables de entorno con las credenciales para acceder a la base de datos, las variables a configurar son

```bash
DB_CONNECTION=mysql #puede ser MySQL, PostgreSQL, SQLite o SQL Server.
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PADEL_DB #Nombre de la base de datos con el que se creo
DB_USERNAME=root #Usuario para conectarse a la base de dats
DB_PASSWORD=fdasfasd934!+ #Contraseña para acceder a la base de datos
```

3. Ejecutar las migraciones desde la raiz del proyecto en la terminal, usando el siguiente comando

```bash
php artisan migrate
```

Con estos pasos se construira la base de datos en el servidor que configuremos.

## Configuración de entrega de correos

Se requieren los siguientes datos para hacer las notificaciones del correo

* Host
* Puerto
* Usuario
* Contraseña

El esquema que usamos para la entrega de datos es SMTP, datos que tambien debemos especificar en las variables de entorno

Los puertos comunmente son 25, 587 o 465, esto depende de lo que indique el provedor del servidor de pruebas o de producción

Las variables de entorno que se deben configurar son las siguientes:

```bash
MAIL_MAILER=smtp # usaremos el esquema SMTP
MAIL_SCHEME=smtp
MAIL_HOST= # Host SMTP que indique el provedor
MAIL_PORT= # Puerto indicado por el provedor
MAIL_USERNAME= # Usuario para acceder al servicio de email, normalmente el correo que se usa para iniciar sesión de forma normal
MAIL_PASSWORD= #Contraseña para acceder al servicio de email, normalmente el correo que se usa para iniciar sesión de forma normal
MAIL_FROM_ADDRESS="" # Correo desde donde se quiere enviar las notificaciones
MAIL_FROM_NAME="${APP_NAME}" # Nombre con el que llega el correo, por defecto es el nombre de la aplicación
```


## Configuración de pasarela de pago

Usamos la pasarela Stripe y su libreria Stripe elements para tener integrado en la misma página la pasarela de págo, para que funcione correctamente debemos configurar correctamente las variables de entorno con los valores que nos proporcione stripe en su dashboard, las variables que se deben configurar en el archivo .env son:

```bash
STRIPE_KEY= #valor que se muestra al inicio del dashboard como "Clave publicable"
STRIPE_SECRET= #valor que se muestra al inicio del dashboard como "Clave secreta"
STRIPE_WEBHOOK_SECRET= #valor que se muestra al terminar de configurar el webhook
```

### Configuración de webhook en local

Para poder hacer pruebas de pago en un entorno local, debemos tener instalado el CLI de Stripe, para instalar el CLI siga la documentación de Stripe [Documentación de instalación de CLI Stripe](https://docs.stripe.com/stripe-cli/install)

Ya que tenga instalado el CLI de Stripe, inicie sesión en la terminal y despues, ejecute el siguiente comando para obtener la firma secreta stripe


```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

### Configuración de webhook en linea

Para hacer pagos en un servidor, se debe de contar con una URL con certificado SSL, ya que de otra forma stripe no escuchara ordenes de pago que no tengan un protocolo HTTP seguro. Para que stripe sepa donde enviar los datos del procesamiento de pago, debe seguir los siguientes pasos:

1. Crear un nuevo Webhook desde el dashboard de stripe y seleccionar los eventos `payment_intent.payment_failed` y `payment_intent.payment_succeeded` 

![Stripe paso 1](https://drive.google.com/file/d/10QVqxDdnPgmLiBkKPCBJcFQA0_YHVOM8/view?usp=sharing)

2. Seleccionar punto de conexión webhook para el envio de datos

![Stripe paso 2](https://drive.google.com/file/d/1lqI4_ybFSBkcvLe0h7O_3X__P-lfprjM/view?usp=sharing)


3. Dar nombre y especificar la url donde se entregaran los datos, tambien se puede colocar una descripción

![Stripe paso 3](https://drive.google.com/file/d/1J7s18wnDchUoo_HUAIVCKbpSRJUSKAVS/view?usp=sharing)

4. Al terminar nos llevara al panel de eventos del webhook y tendremos la firma secreta del webhook

![Stripe paso 4](https://drive.google.com/file/d/1Slz5uTbwUOLtN60yJOVBo7SDE0ZECylw/view?usp=sharing)


Dependiendo del ambiente que se este trabajando, debe usar el primer o segundo metodo para obtener la ultima variable de entorno para que se puedan procesar los pagos con Stripe


## Ejecución de la aplicación

Daremos los pasos para hacer la ejecución del proyecto en un entorno local y de producción

### Entorno de pruebas

Para ejecutar la aplicación en un ambiente local solod debe escribir en la raiz del proyecto el siguiente comando

```bash
composer run dev
```

### Entorno de producción

Una vez que el proyecto este cargado en el servidor, debe cambiar las siguientes variables de entorno a los siguientes valores:

```bash
APP_NAME="Lomas padel" #Nombre de la aplicación
APP_ENV=production #hace que los errores en servidor no sean visibles para el usuario
APP_DEBUG=false #desactiva el debuger de laravel
```

Ademas de que debe asegurarse que las variables de entorno para la base de datos y la entrega de email sea la configuración correcta para el servidor



