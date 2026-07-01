# 📌 Sistema de Inscripción iTECH

## 🏫 Universidad Tecnológica de Panamá  
### Facultad de Ingeniería de Sistemas Computacionales  
### Licenciatura en Desarrollo y Gestión de Software  

---

## 👨‍💻 Autor  
**Nombre:** Gabriel Zambrano  
**Cédula:** 8-1019-1942  

---

## 📖 Descripción del Proyecto

El sistema **iTECH** es una aplicación web desarrollada en PHP con conexión a base de datos MySQL, diseñada para gestionar inscripciones a eventos tecnológicos de manera segura, validada y organizada.

El sistema permite registrar usuarios, validar información, asegurar integridad de datos mediante firma digital y generar reportes en pantalla y Excel.

---

## ⚙️ Tecnologías utilizadas

- PHP 8+
- MySQL
- PDO (conexión segura a base de datos)
- HTML5
- CSS3
- PhpSpreadsheet (exportación a Excel)
- OpenSSL (HMAC-SHA256 para firma digital)

---

## 🧩 Estructura del proyecto


/clases
Firmador.php
Validador.php
Sanitizador.php

/config
Conexion.php

index.php
guardar.php
reporte.php
exportar_excel.php
css/estilo.css
doc_exportados/


---

## 📝 Funcionalidades principales

### 🧾 Registro de inscripciones
Permite registrar:

- Identidad
- Nombre y apellido
- Edad
- Sexo
- País de residencia
- Nacionalidad
- Correo electrónico
- Celular
- Áreas de interés
- Observaciones

---

### 🛡️ Validación y seguridad

El sistema valida:

- Campos obligatorios
- Formato de correo electrónico
- Edad entre 12 y 100 años
- Celular de 8 a 15 dígitos

Incluye además:

- Sanitización de datos (protección XSS)
- Uso de PDO con consultas preparadas (protección SQL Injection)
- Control de duplicados en base de datos

---

### 🔐 Firma digital (integridad de datos)

Cada registro se protege con una firma digital generada con:

- HMAC-SHA256
- Clave secreta del sistema

Esto permite verificar si los datos fueron alterados.

---

### 🗃️ Base de datos

Tablas principales:

- `inscriptores`
- `paises`
- `areas_interes`
- `inscriptor_areas`

Relación:
- Un inscriptor puede seleccionar múltiples áreas de interés.

---

### 📊 Reportes del sistema

El sistema permite:

- Visualizar todos los registros
- Verificar integridad de cada registro:
  - ✔ Validado
  - ✘ Corrupto
- Ordenamiento por registro más reciente

---

### 📤 Exportación a Excel

Permite exportar todos los registros a un archivo Excel con:

- Datos completos del inscriptor
- Temas de interés
- Fechas formateadas

Archivo generado:

doc_exportados/reporte_itech.xlsx


---

## 🔄 Flujo del sistema

1. Usuario llena formulario (`index.php`)
2. Se validan y sanitizan datos
3. Se genera firma digital
4. Se guardan datos en MySQL
5. Se registran áreas de interés
6. Se muestra reporte (`reporte.php`)
7. Opcional: exportación a Excel

---

## 🚀 Instalación y ejecución

1. Copiar proyecto en `htdocs` (XAMPP)
2. Crear base de datos: `parcial_itech`
3. Importar estructura de tablas
4. Configurar conexión en:

/config/Conexion.php

5. Iniciar Apache y MySQL
6. Abrir en navegador:

http://localhost/parcial


---

## 🔒 Seguridad implementada

- PDO con prepared statements
- Sanitización de inputs
- Validación de datos
- Firma digital para integridad

---

## 📈 Posibles mejoras

- Sistema de login de administradores
- Edición y eliminación de registros
- Filtros avanzados en reportes
- API REST
- Exportación PDF

---

## 🏁 Estado del proyecto

✔ Funcional  
✔ Seguro  
✔ Con exportación a Excel  
✔ Con validación de integridad  

---

## 🎓 Universidad Tecnológica de Panamá  
**Proyecto académico desarrollado para fines educativos**