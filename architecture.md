│
├── app/
│   ├── Controllers/        # Handle HTTP requests, call services, return views
│   ├── Models/             # Database interaction (each = one table)
│   ├── Services/           # Business logic (the brain of the app)
│   ├── Repositories/       # Database query abstraction (used by Services)
│   └── Middleware/         # Auth guards, request filters
│
├── core/
│   ├── Router.php          # Routes requests to the right controller
│   ├── Database.php        # PDO connection singleton
│   ├── Request.php         # Wraps $_GET, $_POST, $_FILES
│   ├── Response.php        # Redirects, JSON responses 
│   ├── Session.php         # Session management 
│   └── View.php            # Renders template files
│
├── public/                 # Web root (only this is publicly accessible)
│   ├── index.php           # Single entry point (Front Controller)
│   ├── css/
│   ├── js/
│   └── uploads/
│
├── resources/
│   └── views/              # HTML templates
│       ├── layouts/        # base.php (header, footer, nav)
│       ├── auth/           # login.php, register.php
│       ├── feed/           # index.php
│       ├── posts/          # create.php, show.php
│       └── profile/        # show.php
│
├── routes/
│   └── web.php             # All route definitions
│
├── database/
│   ├── migrations/         # SQL schema files (run in order)
│   └── seeds/              # Dummy data scripts
│
├── config/
│   ├── app.php             # App name, env, debug flag
│   └── database.php        # DB credentials (loaded from .env)
│
├── storage/
│   └── logs/               # Error logs
│
├── .env                    
└── composer.json           # Autoloading (PSR-4)
