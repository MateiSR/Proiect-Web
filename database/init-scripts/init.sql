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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password, is_admin) VALUES
('admin', 'admin@admin.com', '$2y$10$SvCfPHzNz20vR2pySTm5quZ./Pn.TxdMSfxYfjjWdPOcR2AURqUHa', TRUE);

INSERT INTO books (title, author, genre) VALUES
('The Midnight Library', 'Matt Haig', 'Contemporary Fiction, Magical Realism'),
('1984', 'George Orwell', 'Dystopian, Political Fiction, Science Fiction'),
('The Name of the Wind', 'Patrick Rothfuss', 'Epic Fantasy, Adventure'),
('To Kill a Mockingbird', 'Harper Lee', 'Southern Gothic, Bildungsroman'),
('The Great Gatsby', 'F. Scott Fitzgerald', 'Tragedy, Modernist Novel'),
('Pride and Prejudice', 'Jane Austen', 'Romance, Satire'),
('The Hobbit', 'J.R.R. Tolkien', 'High Fantasy, Children''s Literature'),
('Brave New World', 'Aldous Huxley', 'Dystopian, Science Fiction'),
('The Catcher in the Rye', 'J.D. Salinger', 'Coming-of-Age Fiction, Realist Novel'),
('Fahrenheit 451', 'Ray Bradbury', 'Dystopian, Science Fiction'),
('Moby Dick', 'Herman Melville', 'Adventure, Nautical Fiction'),
('War and Peace', 'Leo Tolstoy', 'Historical Novel, Realist Novel'),
('Crime and Punishment', 'Fyodor Dostoevsky', 'Philosophical Fiction, Psychological Fiction'),
('The Lord of the Rings', 'J.R.R. Tolkien', 'High Fantasy, Adventure'),
('Jane Eyre', 'Charlotte Brontë', 'Gothic Fiction, Romance'),
('Wuthering Heights', 'Emily Brontë', 'Gothic Fiction, Tragedy'),
('The Adventures of Sherlock Holmes', 'Arthur Conan Doyle', 'Detective Fiction, Mystery'),
('Dracula', 'Bram Stoker', 'Gothic Fiction, Horror'),
('Frankenstein', 'Mary Shelley', 'Gothic Fiction, Science Fiction'),
('Anna Karenina', 'Leo Tolstoy', 'Realist Novel, Tragedy'),
('The Picture of Dorian Gray', 'Oscar Wilde', 'Philosophical Fiction, Decadent Literature'),
('Don Quixote', 'Miguel de Cervantes', 'Satire, Picaresque Novel'),
('One Hundred Years of Solitude', 'Gabriel García Márquez', 'Magical Realism, Family Saga');

COMMIT;