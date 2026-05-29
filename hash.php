<?php
// hash.php - Generate bcrypt hash for admin123

echo password_hash('admin123', PASSWORD_BCRYPT);
