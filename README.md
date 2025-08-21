# Visitor Management System (VMS)

A web-based Visitor Management System for managing visitor entries, exits, and details. Built with PHP, HTML, CSS, and JavaScript.

## Features
- Visitor entry and exit forms
- Dashboard with visitor statistics
- Export data to Excel
- SMS notifications (Twilio integration)
- CRUD interface for visitor details

## Project Structure
- `php/` - Backend PHP scripts
- `assets/` - CSS, JS, and images
- `vendor/` - Composer dependencies (Twilio SDK)
- `database.sql` - Database schema
- HTML files for UI

## Setup Instructions
1. Clone the repository:
   ```sh
   git clone https://github.com/Vickyvkz7/VMS.git
   ```
2. Import `database.sql` into your MySQL server.
3. Configure database credentials in `php/connect.php`.
4. Install Composer dependencies:
   ```sh
   composer install
   ```
5. Start your local server (e.g., XAMPP) and access the project in your browser.

## Usage
- Login via `login.html`
- Use dashboard for visitor stats
- Add/edit visitor details via forms
- Export data as needed

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This project is licensed under the MIT License. See `LICENSE` for details.
