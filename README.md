# Wordle Word Guesser - Laravel-Style MVC Architecture

A production-ready word guessing application built with a Laravel-like MVC architecture, featuring validation, controllers, models, and services.

## 🎮 Features

✨ **Smart Word Matching** - Find words matching your criteria  
🎯 **Position-Based Search** - Specify known letters at their positions  
❌ **Exclude Letters** - Quickly eliminate impossible letters  
⚡ **Fast Performance** - Optimized recursive algorithm  
📱 **Responsive Design** - Mobile-friendly Bootstrap 5 interface  
🔒 **Secure** - Input sanitization and security headers  
🏗️ **MVC Architecture** - Clean, maintainable code structure  
✅ **Validation** - Form request validation  
🎮 **Controllers** - Organized request handling  
📦 **Models** - Domain models for type safety  

## 📁 Project Structure

```
word-guesser/
├── app/
│   ├── Controllers/
│   │   ├── BaseController.php      # Base controller class
│   │   ├── HomeController.php      # Home page controller
│   │   └── ApiController.php       # API endpoints controller
│   ├── Models/
│   │   ├── Word.php                # Word model
│   │   └── GuessSession.php        # Session model
│   ├── Requests/
│   │   └── GuessWordRequest.php    # Form request validation
│   ├── Services/
│   │   └── WordGuesserService.php  # Business logic service
│   └── Helpers/
│       └── helpers.php             # Global helper functions
├── config/
│   ├── app.php                     # Application configuration
│   └── database.php                # Database configuration
├── resources/
│   └── views/
│       ├── home.php                # Home page view
│       └── layout.php              # Layout template
├── public/
│   ├── index.php                   # Entry point
│   └── assets/
│       ├── css/styles.css          # Stylesheets
│       └── js/app.js               # JavaScript
├── storage/
│   ├── logs/                       # Application logs
│   ├── words/                      # Word lists
│   └── database.sqlite             # SQLite database
├── .htaccess                       # Apache configuration
└── README.md                       # Documentation
```

## 🚀 Installation

### Requirements

- PHP 8.0 or higher
- Apache with mod_rewrite enabled
- Word list JSON files (a.json - z.json)

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/HariK77/word-guesser.git
   cd word-guesser
   ```

2. **Create necessary directories**
   ```bash
   mkdir -p storage/logs
   mkdir -p storage/words
   ```

3. **Add word lists**
   
   Place JSON files in `storage/words/` (a.json through z.json)
   
   Example format:
   ```json
   ["about", "above", "abuse", "actor", "acute", ...]
   ```

4. **Set permissions**
   ```bash
   chmod 755 storage/logs/
   chmod 755 storage/words/
   ```

5. **Configure web server**
   
   Point your web server to the `public/` directory

6. **Access the application**
   ```
   http://localhost/word-guesser/public
   ```

## 📚 Architecture Overview

### Models
Domain models representing core concepts:

- **Word** - Represents a word with methods for pattern matching
- **GuessSession** - Manages user session state

### Controllers
Handle HTTP requests and coordinate application logic:

- **HomeController** - Handles web UI requests
- **ApiController** - Handles JSON API requests
- **BaseController** - Provides common functionality

### Requests
Validate incoming form data:

- **GuessWordRequest** - Validates word guessing input

### Services
Encapsulate business logic:

- **WordGuesserService** - Core word guessing logic

## 💻 Usage

### Web Interface

1. Enter known letters in their positions (1-5)
2. Optionally enter excluded letters (comma-separated)
3. Click "Guess Word"
4. View matching words

### API Usage

```bash
curl -X POST http://localhost/word-guesser/public/api/guess \
  -H "Content-Type: application/json" \
  -d '{
    "l1": "",
    "l2": "A",
    "l3": "T",
    "l4": "A",
    "l5": "L",
    "excluded": "E,O,U"
  }'
```

## 🔧 Configuration

Edit `config/app.php` to customize application settings.

## 🛡️ Security

- Input validation via `GuessWordRequest`
- Output escaping with `esc_html()` and `esc_attr()`
- Security headers (CSP, X-Frame-Options, etc.)
- SQL injection prevention (prepared for database)
- XSS protection

## 📝 Helper Functions

- `config()` - Get configuration values
- `base_url()` - Get base URL
- `view()` - Render views
- `session()` - Manage sessions
- `logger()` - Log messages
- `esc_html()` - Escape HTML
- And many more...

## 📊 Performance

- Single Unknown: ~10ms
- Two Unknowns: ~50ms
- Three Unknowns: ~200ms
- Four Unknowns: ~5s
- Five Unknowns: ~130s

## 📄 License

MIT License - feel free to use and modify!

---

**Made with ❤️ by Word Guesser Team**  
Version 2.0.0 | Laravel-Style MVC Architecture
