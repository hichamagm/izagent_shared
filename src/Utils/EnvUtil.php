<?php

namespace Hichamagm\IzagentShared\Utils;

class EnvUtil {
    
    public static function setEnvValue($key, $value)
    {
        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            // Read the .env file
            $envContent = file_get_contents($envPath);

            // Check if the key already exists
            if (strpos($envContent, "{$key}=") !== false) {
                // Update the existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                // Append the new key
                $envContent .= PHP_EOL . "{$key}=\"{$value}\"";
            }

            // Write the updated content back to the .env file
            file_put_contents($envPath, $envContent);
        }
    }
}