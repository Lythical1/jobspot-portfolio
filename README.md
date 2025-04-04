# JobSpot Portfolio

A web application for job seekers and employers to connect and manage job applications.

## Getting Started

To set up and run the project locally:

1. Clone the repository
2. Make sure Docker is installed on your system
3. For the first time setup, run:
   ```
   docker compose up --build (-d for detached)
   ```
4. For subsequent runs, you can use:
   ```
   docker compose up (-d for detached)
   ```

## Features

- User authentication and authorization
- Job posting and application management
- Profile creation and management
- Search functionality
- Interview scheduling
- Application tracking

## Standard Accounts

The application comes with three standard accounts for testing purposes. All 3 accounts use the password: **Jobspot** but feel free to create your own accounts or change the password.

1. **Admin Account**
   - Email: admin@example.com
   - Role: Administrator
   - Access to all features and administrative controls

2. **Employer Account**
   - Email: test@example.com
   - Role: Employer
   - Can post jobs, search for people in need of a job, and manage company profile

3. **Jobseeker Account**
   - Email: testuser@example.com
   - Role: Job Seeker
   - Can create a profile, search for jobs, and submit job searches

## Database Structure

The application uses a relational database with tables for users, companies, jobs, applications, skills, interviews, and more. See the database schema in the `database/import.sql` file.

## Technologies Used

- Frontend: Tailwind
- Backend: PHP
- Database: MySQL with UUID primary keys

## Mock Data

The application includes comprehensive mock data to demonstrate functionality, including:
- Multiple user accounts with different roles
- Various job listings across different categories
- Skills, applications, and interviews

See `database/mock_data.sql` for the complete dataset.
