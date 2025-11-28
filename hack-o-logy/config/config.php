<?php
return [
 'db_host' => getenv('DB_HOST') ?: '127.0.0.1',
 'db_port' => getenv('DB_PORT') ?: '3306',
 'db_name' => getenv('DB_NAME') ?: 'smart_library',
 'db_user' => getenv('DB_USER') ?: 'root',
 'db_pass' => getenv('DB_PASS') ?: '',
 'gemini_api_key' => getenv('GEMINI_API_KEY') ?: '',
 'gemini_model' => getenv('GEMINI_MODEL') ?: 'gemini-1.5-flash',
 'openai_api_key' => getenv('OPENAI_API_KEY') ?: '',
 'openai_model' => getenv('OPENAI_MODEL') ?: 'gpt-4o-mini',
 'ai_provider' => getenv('AI_PROVIDER') ?: '',
];
