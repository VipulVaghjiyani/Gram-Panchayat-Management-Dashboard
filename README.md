# Gram Panchayat Management Dashboard  

A Laravel-based web application designed to streamline the management of Gram Panchayat operations. This dashboard provides secure, efficient tools for handling data, finances, and user roles.  

## Features  
- **CRUD Operations**: Manage households, members, income, and expenses.  
- **Water Charge Management**: Track payments and outstanding balances.  
- **Financial Ledger**: Monitor income and expenses in real-time.  
- **Role-Based Permissions**: Secure access levels for admins and accountants.  
- **Database Integration**: Built with **MySQL** for reliable data storage.  

## Technology Stack  
- **Backend**: Laravel  
- **Database**: MySQL  
- **Frontend**: HTML, CSS, Bootstrap  
- **Version Control**: Git  

## Installation  

1. Clone the repository:
 ```bash
   git clone https://github.com/VipulVaghjiyani/Gram-Panchayat-Management-Dashboard.git
```

2. Navigate to the project directory:
   ```bash
   cd Gram-Panchayat-Management-Dashboard
   ```

3. Install dependencies
 ```bash
composer install
npm install
```

4. Configure the environment:
   Copy the .env.example file to .env.
   Update database credentials and other configurations in .env.
   
6. Run migrations:
   ```bash
   php artisan migrate  
   ```
7. Start the development server:
```bash
php artisan serve  
```

8. Usage
Access the application in your browser at http://localhost:8000 and log in using your assigned credentials.






