# Arquitectura técnica del proyecto

## Objetivo general

Aplicación Laravel con paneles Filament orientada a la gestión documental, expedientes de obras, validación de fechas, trazabilidad de eventos y generación de documentos a partir de plantillas Word con salida final en PDF.

---

## Estructura funcional por carpetas

### app/Models

Contiene el modelo de dominio y el acceso ORM a tablas y vistas de la base de datos.

Responsabilidades habituales:

- definir relaciones Eloquent
- declarar casts y fillable
- exponer atributos calculados
- representar entidades del negocio

### app/Services

Contiene lógica de aplicación reutilizable que no debe vivir en Resources ni Controllers.

Submódulos principales:

- Assignments: visibilidad y asignación de expedientes por usuario
- DateRules: evaluación, notificación y trazabilidad de reglas de fechas
- Importes: cálculo y persistencia de importes por organismo
- Templates: generación documental desde plantillas Word y salida PDF
- Tramitador: servicios orientados al circuito de tramitación

### app/Events y app/Listeners

Implementan comunicación desacoplada entre módulos. Un evento representa algo que ha ocurrido y un listener decide qué hacer con ello.

### app/Observers

Encapsulan reacciones automáticas a cambios de modelos, por ejemplo validaciones de fechas antes y después de guardar.

### app/Notifications

Define el formato de notificaciones persistentes o enviadas a otros canales.

### app/Helpers

Agrupa utilidades estáticas heredadas o de apoyo. Su uso debe ser excepcional y siempre justificado frente a un Service.

### app/Enums

Centraliza valores cerrados y opciones de negocio para formularios, flujos y lógica condicional.

### app/DTOs

Define objetos de transferencia de datos inmutables usados para desacoplar cálculo, persistencia y presentación.

### app/Providers

Registra servicios, eventos, assets y configuración transversal durante el arranque de la aplicación.

---

## Módulos clave documentados

### Motor documental de plantillas

Ubicación: app/Services/Templates

Responsabilidades:

- localizar la plantilla Word física
- extraer variables WD\_\*
- construir el contexto de datos desde el modelo actual
- resolver valores de cada variable
- renderizar el DOCX temporal
- convertir el resultado final a PDF con LibreOffice

Clases principales:

- TemplatePathResolver: resuelve rutas de plantillas
- WordTemplatePlaceholderExtractor: extrae variables del documento Word
- TemplateContextFactory: prepara relaciones y metadatos de impresión
- TemplateVariableValueResolver: obtiene el valor de cada variable
- WordTemplateVariableSynchronizer: sincroniza la plantilla con la tabla de variables
- WordTemplatePrintService: coordina la generación DOCX y PDF

### Reglas de fechas

Ubicación: app/Services/DateRules y app/Observers

Responsabilidades:

- detectar cambios relevantes en fechas
- evaluar reglas configuradas
- bloquear guardados inválidos
- registrar ejecución y resultados
- notificar incidencias o avisos

### Notificaciones y eventos

Ubicación: app/Events, app/Listeners, app/Notifications, app/Services/NotificationService.php

Responsabilidades:

- emitir eventos de sistema desacoplados
- registrar auditoría
- resolver destinatarios
- persistir notificaciones visibles en Filament

### Asignación de expedientes

Ubicación: app/Services/Assignments

Responsabilidades:

- decidir qué expedientes puede ver cada usuario
- filtrar consultas de Resources y widgets
- soportar segregación funcional por equipo o asignación directa

---

## Convenciones recomendadas

### Services

Cada servicio debe tener una responsabilidad clara, ser fácilmente testeable y exponer métodos pequeños con nombres expresivos.

### DTOs

Se usan para transportar datos calculados entre capas sin mezclar reglas de negocio con arrays difusos.

### Enums

Se usan para sustituir cadenas mágicas y centralizar opciones de selección o estado.

### Resources de Filament

Solo deben contener configuración de UI, acciones simples y delegación a Services o Actions del dominio.

---

## Flujo de impresión de documentos

1. El usuario selecciona un documento genérico o una acción de impresión.
2. Se localiza la plantilla Word configurada.
3. Se extraen o sincronizan variables WD\_\*.
4. Se construye el contexto desde el registro actual y sus relaciones.
5. Se resuelve cada variable a texto final.
6. Se genera un DOCX temporal.
7. Se convierte a PDF.
8. Se devuelve la descarga al usuario.

---

## Mantenimiento futuro

Cuando se añada un nuevo módulo se recomienda documentar siempre:

- finalidad de la clase
- responsabilidad del método
- significado de propiedades públicas
- tipos de entrada y salida
- dependencias externas o efectos laterales
