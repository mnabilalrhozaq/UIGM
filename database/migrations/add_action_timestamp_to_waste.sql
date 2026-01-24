-- Add action_timestamp column to track when admin approves/rejects data
-- Data will be hidden from dashboard after 5 minutes

ALTER TABLE waste_management 
ADD COLUMN action_timestamp TIMESTAMP NULL DEFAULT NULL 
AFTER updated_at;

-- Add comment
ALTER TABLE waste_management 
MODIFY COLUMN action_timestamp TIMESTAMP NULL DEFAULT NULL 
COMMENT 'Timestamp when admin approves/rejects data. Used to hide data after 5 minutes';
