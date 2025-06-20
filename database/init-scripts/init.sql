BEGIN;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
	is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE books (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(255),
    description TEXT,
    pages INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    book_id INTEGER NOT NULL REFERENCES books(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(book_id, user_id)
);

CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    creator_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE group_members (
    id SERIAL PRIMARY KEY,
    group_id INTEGER NOT NULL REFERENCES groups(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(group_id, user_id)
);

CREATE TABLE group_books (
    id SERIAL PRIMARY KEY,
    group_id INTEGER NOT NULL REFERENCES groups(id) ON DELETE CASCADE,
    book_id INTEGER NOT NULL REFERENCES books(id) ON DELETE CASCADE,
    added_by_user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(group_id, book_id)
);

CREATE TABLE group_discussions (
    id SERIAL PRIMARY KEY,
    group_id INTEGER NOT NULL REFERENCES groups(id) ON DELETE CASCADE,
    book_id INTEGER NOT NULL REFERENCES books(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_book_progress (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    book_id INTEGER NOT NULL REFERENCES books(id) ON DELETE CASCADE,
    current_page INTEGER NOT NULL CHECK (current_page >= 0),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, book_id)
);

INSERT INTO users (username, email, password, is_admin) VALUES
('admin', 'admin@admin.com', '$2y$10$SvCfPHzNz20vR2pySTm5quZ./Pn.TxdMSfxYfjjWdPOcR2AURqUHa', TRUE);

INSERT INTO books (title, author, genre, pages) VALUES
('The Midnight Library', 'Matt Haig', 'Contemporary Fiction, Magical Realism', 304),
('1984', 'George Orwell', 'Dystopian, Political Fiction, Science Fiction', 328),
('The Name of the Wind', 'Patrick Rothfuss', 'Epic Fantasy, Adventure', 662),
('To Kill a Mockingbird', 'Harper Lee', 'Southern Gothic, Bildungsroman', 324),
('The Great Gatsby', 'F. Scott Fitzgerald', 'Tragedy, Modernist Novel', 180),
('Pride and Prejudice', 'Jane Austen', 'Romance, Satire', 279),
('The Hobbit', 'J.R.R. Tolkien', 'High Fantasy, Children''s Literature', 310),
('Brave New World', 'Aldous Huxley', 'Dystopian, Science Fiction', 311),
('The Catcher in the Rye', 'J.D. Salinger', 'Coming-of-Age Fiction, Realist Novel', 224),
('Fahrenheit 451', 'Ray Bradbury', 'Dystopian, Science Fiction', 159),
('Moby Dick', 'Herman Melville', 'Adventure, Nautical Fiction', 635),
('War and Peace', 'Leo Tolstoy', 'Historical Novel, Realist Novel', 1225),
('Crime and Punishment', 'Fyodor Dostoevsky', 'Philosophical Fiction, Psychological Fiction', 430),
('The Lord of the Rings', 'J.R.R. Tolkien', 'High Fantasy, Adventure', 1178),
('Jane Eyre', 'Charlotte Brontë', 'Gothic Fiction, Romance', 500),
('Wuthering Heights', 'Emily Brontë', 'Gothic Fiction, Tragedy', 342),
('The Adventures of Sherlock Holmes', 'Arthur Conan Doyle', 'Detective Fiction, Mystery', 307),
('Dracula', 'Bram Stoker', 'Gothic Fiction, Horror', 418),
('Frankenstein', 'Mary Shelley', 'Gothic Fiction, Science Fiction', 280),
('Anna Karenina', 'Leo Tolstoy', 'Realist Novel, Tragedy', 864),
('The Picture of Dorian Gray', 'Oscar Wilde', 'Philosophical Fiction, Decadent Literature', 254),
('Don Quixote', 'Miguel de Cervantes', 'Satire, Picaresque Novel', 863),
('One Hundred Years of Solitude', 'Gabriel García Márquez', 'Magical Realism, Family Saga', 417);

COMMIT;