# نظام إدارة صالات رياضية (مواعيد، اشتراكات، دفع)
==============================================

## نظرة عامة وغرض المشروع
------------------------

نظام إدارة صالات رياضية هو تطبيق ويب يهدف إلى تسهيل إدارة صالات الرياضة من خلال توفير أدوات لتحديد المواعيد، إدارة الاشتراكات، وتسجيل الدفعات. يهدف المشروع إلى توفير حل متكامل وسهل الاستخدام لصالات الرياضة لتحسين كفاءة عملياتها وتعزيز رضا العملاء.

## هيكل المشروع
----------------

*   `app/`: contiene el código de la aplicación
*   `app/models/`: define los modelos de datos
*   `app/views/`: contiene las vistas de la aplicación
*   `app/controllers/`: contiene los controladores de la aplicación
*   `config/`: contiene los archivos de configuración
*   `docker/`: contiene los archivos de configuración para Docker
*   `requirements.txt`: lista de dependencias del proyecto

## تشغيل البيئة باستخدام docker-compose
--------------------------------------

1.  Clone el repositorio: `git clone https://github.com/your-username/your-repo-name.git`
2.  Acceda al directorio del proyecto: `cd your-repo-name`
3.  Construya las imágenes de Docker: `docker-compose build`
4.  Inicie los contenedores: `docker-compose up`
5.  Abra un navegador y acceda a `http://localhost:8000` para ver la aplicación en acción

## مكونات المشروع
------------------

### الوحدات

*   `users`: لإدارة المستخدمين
*   `gyms`: لإدارة صالات الرياضة
*   `schedules`: لإدارة المواعيد
*   `subscriptions`: لإدارة الاشتراكات
*   `payments`: لإدارة الدفعات

### الجداول

*   `users`: معلومات المستخدمين
*   `gyms`: معلومات صالات الرياضة
*   `schedules`: مواعيد الحجز
*   `subscriptions`: اشتراكات العملاء
*   `payments`: سجلات الدفعات

### الأدوار

*   `admin`: مدير النظام
*   `gym_owner`: مالك صالة الرياضة
*   `customer`: عميل

## معلومات مطور البرنامج
-------------------------

*   **اسم المطور:** [اسمك]
*   **البريد الإلكتروني:** [بريدك الإلكتروني](mailto:example@example.com)
*   **موقع ويب:** [موقعك على الويب](https://example.com)
*   **حسابات التواصل الاجتماعي:**
    *   [GitHub](https://github.com/your-username)
    *   [LinkedIn](https://www.linkedin.com/in/your-linkedin-profile/)
    *   [Twitter](https://twitter.com/your-twitter-handle)

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
