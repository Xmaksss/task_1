<?php

class CreateJobsTable implements MigrationInterface
{
    public function up()
    {
        return 'CREATE TABLE IF NOT EXISTS jobs (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        url VARCHAR(128) NOT NULL,
                        status VARCHAR(32) NOT NULL,
                        http_code VARCHAR(32)
                      )';
    }
}