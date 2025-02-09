-- Updated JobSpot tables to use UUID instead of auto-increment IDs

-- USERS table with UUID as primary key
CREATE TABLE users (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    profession VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'employer', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- COMPANIES table with UUID for primary key and FK
CREATE TABLE companies (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    website VARCHAR(255),
    user_id CHAR(36),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- CATEGORIES table with UUID as primary key
CREATE TABLE categories (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255)
);

-- JOBS table with UUID for primary key and FKs
CREATE TABLE jobs (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    salary_range VARCHAR(50),
    location VARCHAR(100),
    company_id CHAR(36),
    category_id CHAR(36),
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- APPLICATIONS table with UUID for primary key and FKs
CREATE TABLE applications (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36),
    job_id CHAR(36),
    cover_letter TEXT,
    resume_url VARCHAR(255),
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (job_id) REFERENCES jobs(id)
);

-- Insert sample categories
INSERT INTO categories (id, name, description) VALUES
(UUID(), 'Development', 'Software development and programming positions'),
(UUID(), 'Design', 'Graphic design and UX/UI positions'),
(UUID(), 'Marketing', 'Digital marketing and social media positions');

-- Insert sample user (password: test123)
INSERT INTO users (id, first_name, last_name, email, password, role) VALUES
(UUID(), 'Admin', 'User', 'admin@example.com', '$2y$10$dEBv2tZho6cSDo0irOS2oeZta/YZD75KpSgZnxj203r0p9/STObbO', 'admin'),
(UUID(), 'Test', 'Employer', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer'),
(UUID(), 'Test', 'User', 'testuser@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert sample company
INSERT INTO companies (id, name, description, location, website, user_id) VALUES
(UUID(), 'Tech Corp', 'Leading technology company', 'Amsterdam', 'https://techcorp.example.com', (SELECT id FROM users WHERE email = 'admin@example.com'));

-- Insert sample job
INSERT INTO jobs (id, title, description, requirements, salary_range, location, company_id, category_id) VALUES
(UUID(), 'Senior PHP Developer', 'We are looking for an experienced PHP developer to join our team.', 'Min 5 years experience with PHP\nKnowledge of MySQL\nExperience with MVC frameworks', '€4000-€5500', 'Amsterdam', (SELECT id FROM companies WHERE name = 'Tech Corp'), (SELECT id FROM categories WHERE name = 'Development'));
