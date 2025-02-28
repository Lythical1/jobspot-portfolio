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

-- Create skills table to store skill details
CREATE TABLE skills (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- JOBS table with UUID for primary key and FKs
CREATE TABLE jobs (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    work_hours VARCHAR(50) DEFAULT 'Full-time',
    salary_range VARCHAR(50) DEFAULT 'Negotiable',
    location VARCHAR(100),
    company_id CHAR(36),
    category_id CHAR(36),
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- JOB SEARCHERS table with UUID for primary key and FKs
CREATE TABLE job_searchers (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(100) NOT NULL,
    user_id CHAR(36) NOT NULL,
    category_id CHAR(36) NOT NULL,
    work_hours VARCHAR(50),
    salary_range VARCHAR(50) DEFAULT 'Negotiable',
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Pivot table to associate jobs with multiple skills (many-to-many relationship)
CREATE TABLE job_skills (
    job_id CHAR(36) NOT NULL,
    skill_id CHAR(36) NOT NULL,
    PRIMARY KEY (job_id, skill_id),
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (skill_id) REFERENCES skills(id)
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

-- Create saved_jobs table for users to bookmark jobs
CREATE TABLE saved_jobs (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    user_id CHAR(36) NOT NULL,
    job_id CHAR(36) NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (job_id) REFERENCES jobs(id)
);

-- Create interviews table to manage interview scheduling
CREATE TABLE interviews (
    id CHAR(36) NOT NULL PRIMARY KEY DEFAULT (UUID()),
    job_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    scheduled_at DATETIME NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create a new pivot table to optionally link users with skills (not mandatory)
CREATE TABLE user_skills (
    user_id CHAR(36) NOT NULL,
    skill_id CHAR(36) NOT NULL,
    PRIMARY KEY (user_id, skill_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (skill_id) REFERENCES skills(id)
);

-- Insert sample categories
INSERT INTO
    categories (id, name, description)
VALUES
    (
        UUID(),
        'Development',
        'Software development and programming positions'
    ),
    (
        UUID(),
        'Design',
        'Graphic design and UX/UI positions'
    ),
    (
        UUID(),
        'Digital Marketing',
        'Digital marketing and social media positions'
    ),
    (
        UUID(),
        'Management',
        'Management and leadership positions'
    ),
    (
        UUID(),
        'Sales',
        'Sales and business development positions'
    ),
    (
        UUID(),
        'Customer Service',
        'Customer support and service positions'
    );

-- Insert sample users
INSERT INTO
    users (id, first_name, last_name, email, password, role)
VALUES
    (
        UUID(),
        'Admin',
        'User',
        'admin@example.com',
        '$2y$10$dEBv2tZho6cSDo0irOS2oeZta/YZD75KpSgZnxj203r0p9/STObbO',
        'admin'
    ),
    (
        UUID(),
        'Test',
        'Employer',
        'test@example.com',
        '$2y$10$dEBv2tZho6cSDo0irOS2oeZta/YZD75KpSgZnxj203r0p9/STObbO',
        'employer'
    ),
    (
        UUID(),
        'Test',
        'User',
        'testuser@example.com',
        '$2y$10$dEBv2tZho6cSDo0irOS2oeZta/YZD75KpSgZnxj203r0p9/STObbO',
        'user'
    );

-- Insert sample company
INSERT INTO
    companies (
        id,
        name,
        description,
        location,
        website,
        user_id
    )
VALUES
    (
        UUID(),
        'Tech Corp',
        'Leading technology company',
        'Amsterdam',
        'https://techcorp.example.com',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'admin@example.com'
        )
    ),
    (
        UUID(),
        'Digital Solutions',
        'Digital transformation consultancy',
        'Rotterdam',
        'https://digitalsolutions.example.com',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'test@example.com'
        )
    ),
    (
        UUID(),
        'Creative Agency',
        'Full-service creative agency',
        'Utrecht',
        'https://creative.example.com',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'test@example.com'
        )
    );

-- Insert sample job
INSERT INTO
    jobs (
        id,
        title,
        description,
        requirements,
        salary_range,
        location,
        company_id,
        category_id
    )
VALUES
    (
        UUID(),
        'Senior PHP Developer',
        'We are looking for an experienced PHP developer to join our team.',
        'Min 5 years experience with PHP\nKnowledge of MySQL\nExperience with MVC frameworks',
        '€4000-€5500',
        'Amsterdam',
        (
            SELECT
                id
            FROM
                companies
            WHERE
                name = 'Tech Corp'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Development'
        )
    ),
    (
        UUID(),
        'Frontend Developer',
        'Looking for a frontend developer with React experience',
        'Experience with React\nKnowledge of JavaScript\nCSS expertise',
        '€3500-€4500',
        'Rotterdam',
        (
            SELECT
                id
            FROM
                companies
            WHERE
                name = 'Digital Solutions'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Development'
        )
    ),
    (
        UUID(),
        'UX Designer',
        'Seeking creative UX designer for digital products',
        'Portfolio required\nFigma expertise\n3+ years experience',
        '€3000-€4000',
        'Utrecht',
        (
            SELECT
                id
            FROM
                companies
            WHERE
                name = 'Creative Agency'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Design'
        )
    ),
    (
        UUID(),
        'Data Analyst',
        'Analyze data and generate actionable insights.',
        'Proficiency in SQL, Excel, and data visualization tools.',
        '€3500-€4500',
        'Amsterdam',
        (
            SELECT
                id
            FROM
                companies
            WHERE
                name = 'Tech Corp'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Management'
        )
    ),
    (
        UUID(),
        'Product Manager',
        'Oversee product development and market strategy.',
        'Experience in managing product lifecycle and agile methodologies.',
        '€4500-€6000',
        'Rotterdam',
        (
            SELECT
                id
            FROM
                companies
            WHERE
                name = 'Digital Solutions'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Management'
        )
    );

-- Insert sample skills for "Senior PHP Developer"
INSERT INTO
    skills (id, name, description)
VALUES
    (UUID(), 'PHP', 'Hypertext Preprocessor'),
    (
        UUID(),
        'MySQL',
        'Open-source relational database management system'
    ),
    (
        UUID(),
        'Laravel',
        'PHP web application framework'
    ),
    (
        UUID(),
        'JavaScript',
        'Programming language for web development'
    ),
    (
        UUID(),
        'Python',
        'General-purpose programming language'
    ),
    (
        UUID(),
        'React',
        'JavaScript library for building user interfaces'
    ),
    (
        UUID(),
        'React',
        'JavaScript library for building user interfaces'
    ),
    (UUID(), 'Docker', 'Containerization platform'),
    (
        UUID(),
        'AWS',
        'Amazon Web Services cloud platform'
    );

-- Job skills sample data
INSERT INTO
    job_skills (job_id, skill_id)
VALUES
    (
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        ),
        (
            SELECT
                id
            FROM
                skills
            WHERE
                name = 'PHP'
        )
    ),
    (
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        ),
        (
            SELECT
                id
            FROM
                skills
            WHERE
                name = 'MySQL'
        )
    ),
    (
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        ),
        (
            SELECT
                id
            FROM
                skills
            WHERE
                name = 'Laravel'
        )
    );

-- User skills sample data
INSERT INTO
    user_skills (user_id, skill_id)
VALUES
    (
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                skills
            WHERE
                name = 'PHP'
        )
    );

-- Insert sample saved_jobs
INSERT INTO
    saved_jobs (id, user_id, job_id)
VALUES
    (
        UUID(),
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        )
    );

-- Insert sample interview
INSERT INTO
    interviews (id, job_id, user_id, scheduled_at)
VALUES
    (
        UUID(),
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        ),
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        DATE_ADD(NOW(), INTERVAL 2 DAY)
    );

-- Insert sample job searchers
INSERT INTO
    job_searchers (
        id,
        title,
        user_id,
        category_id,
        work_hours,
        salary_range,
        location
    )
VALUES
    (
        UUID(),
        'Job Seeker Title',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Development'
        ),
        '40 hours/week',
        '€3500-€5000',
        'Amsterdam'
    ),
    (
        UUID(),
        'Job Seeker Title 2',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        -- changed from 'newuser@example.com'
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Design'
        ),
        'Part-time',
        '€2500-€3500',
        'Rotterdam'
    ),
    (
        UUID(),
        'Job Seeker Title 3',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Digital Marketing'
        ),
        'Full-time',
        '€3000-€4000',
        'Utrecht'
    ),
    (
        UUID(),
        'Job Seeker Title 4',
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                categories
            WHERE
                name = 'Sales'
        ),
        'Part-time',
        '€2500-€3500',
        'Amsterdam'
    );

-- Insert sample applications
INSERT INTO
    applications (
        id,
        user_id,
        job_id,
        cover_letter,
        resume_url,
        status
    )
VALUES
    (
        UUID(),
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        ),
        'I am very interested in this position and believe my skills match your requirements.',
        'https://example.com/resumes/testuser_resume.pdf',
        'pending'
    ),
    (
        UUID(),
        (
            SELECT
                id
            FROM
                users
            WHERE
                email = 'testuser@example.com'
        ),
        (
            SELECT
                id
            FROM
                jobs
            WHERE
                title = 'Senior PHP Developer'
        ),
        'I have extensive experience with PHP and would love to join your team.',
        'https://example.com/resumes/testuser_resume2.pdf',
        'reviewed'
    );