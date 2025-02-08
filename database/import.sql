-- JobSpot tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'employer', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    website VARCHAR(255),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    salary_range VARCHAR(50),
    location VARCHAR(100),
    company_id INT,
    category_id INT,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    job_id INT,
    cover_letter TEXT,
    resume_url VARCHAR(255),
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (job_id) REFERENCES jobs(id)
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Development', 'Software development and programming positions'),
('Design', 'Graphic design and UX/UI positions'),
('Marketing', 'Digital marketing and social media positions');

-- Insert sample user (password: test123)
INSERT INTO users (username, email, password, role) VALUES
('testemployer', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer'),
('admin', 'admin@example.com', '$2y$10$c7W8MQIvF95ErrIhNVjREODQEDY1OwlYQVRiEsnrgH1574yUTDujm', 'admin');

-- Insert sample company
INSERT INTO companies (name, description, location, website, user_id) VALUES
('Tech Corp', 'Leading technology company', 'Amsterdam', 'https://techcorp.example.com', 1);

-- Insert sample job
INSERT INTO jobs (title, description, requirements, salary_range, location, company_id, category_id) VALUES
('Senior PHP Developer', 'We are looking for an experienced PHP developer to join our team.', 'Min 5 years experience with PHP\nKnowledge of MySQL\nExperience with MVC frameworks', '€4000-€5500', 'Amsterdam', 1, 1);
