# Student Grade Calculator

A simple student grade activity using HTML, SCSS/CSS, JavaScript, and PHP.

## Requirements
- PHP 8+ (or a local XAMPP/LAMP installation)
- Sass CLI to compile SCSS

## Run with PHP's built-in server
From the root project directory, run:

```bash
php -S localhost:8000
```

Then open http://localhost:8000 in your browser.

## SCSS
The compiled stylesheet is already included at `css/style.css`. To recompile after editing:

```bash
sass scss/style.scss css/style.css
```

## Grading logic
The three grades are averaged equally. An average of 75 or above is **Passed** and below 75 is **Failed**.
