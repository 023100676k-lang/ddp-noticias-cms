# 📰 DDP Noticias - Sistema CMS

Sistema de gestión de contenidos (CMS) completo para **Diálogo y Desarrollo Perú (DDP Noticias)**. Incluye un panel de administración para gestionar todo el contenido y una página pública dinámica que lee de la base de datos.

---

## 🌐 URLs Públicas (Producción)

| Recurso | URL |
|---|---|
| **Panel de Administración** | [http://ddp-noticias.infinityfreeapp.com/login.php](http://ddp-noticias.infinityfreeapp.com/login.php) |
| **Página Pública** | [http://ddp-noticias.infinityfreeapp.com/frontend/](http://ddp-noticias.infinityfreeapp.com/frontend/) |

### 🔑 Credenciales de Prueba

| Campo | Valor |
|---|---|
| **Email** | `admin@ddp.com` |
| **Contraseña** | `temporal123` |

> ⚠️ **Nota:** Cambiar la contraseña en producción por seguridad.

---

## ✨ Características

### 🔐 Panel de Administración
- Login seguro con contraseñas encriptadas (bcrypt)
- Dashboard con estadísticas en tiempo real (Chart.js)
- CRUD completo para: Actualidad, Reportajes, Podcast, Boletín NTEP, Alianzas, Sobre D&D
- Gestión de usuarios con roles (admin, editor, redactor)
- Sistema de mensajes de contacto
- Recuperación de contraseña

### 🌐 Página Pública
- Diseño responsive (móvil, tablet, desktop)
- Secciones: Inicio, Actualidad, Reportajes, Podcast, Boletín NTEP, Alianzas, Sobre D&D
- Tarjetas uniformes con franjas rojas cóncavas
- Reproductores de YouTube/Spotify embebidos
- Formulario de contacto funcional
- **100% dinámico** (lee de la base de datos)

---

## 🛠️ Tecnologías

| Tecnología | Uso |
|---|---|
| **PHP 8.0+** | Backend |
| **MySQL / MariaDB** | Base de datos |
| **HTML5** | Estructura |
| **CSS3** | Estilos |
| **JavaScript** | Interactividad |
| **Chart.js** | Gráficos del dashboard |
| **Bootstrap 4** | Framework CSS |
| **Summernote** | Editor de texto enriquecido |
| **XAMPP** | Servidor local (desarrollo) |
| **InfinityFree** | Hosting gratuito (producción) |
| **GitHub** | Control de versiones |

---

## 📁 Estructura del Proyecto
