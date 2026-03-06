-- SQL schema for chat messages, chat sessions, and AI responses

-- Create table for chat messages
CREATE TABLE chat_messages (
    id SERIAL PRIMARY KEY,
    session_id INT NOT NULL,
    message TEXT NOT NULL,
    sender VARCHAR(50) NOT NULL,
    timestamp TIMESTAMPTZ DEFAULT NOW(),
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);

-- Create table for chat sessions
CREATE TABLE chat_sessions (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    start_time TIMESTAMPTZ DEFAULT NOW(),
    end_time TIMESTAMPTZ,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create table for AI responses
CREATE TABLE ai_responses (
    id SERIAL PRIMARY KEY,
    session_id INT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMPTZ DEFAULT NOW(),
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);
