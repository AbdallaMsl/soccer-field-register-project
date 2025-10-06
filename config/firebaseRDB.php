<?php
/*
 * Firebase Realtime Database PHP Wrapper
 * Supports auth token for secured databases
 * Author: Devisty (modified with explanations)
 */

class firebaseRDB {
    private $url;       // Base URL of your Firebase database (no trailing slash)
    private $authToken; // Authentication token (database secret or Firebase ID token)

    /**
     * Constructor
     * @param string $url       Firebase Realtime Database URL (e.g. https://your-project.firebaseio.com)
     * @param string|null $authToken Firebase auth token or database secret (optional)
     */
    function __construct($url = null, $authToken = null) {
        if (isset($url)) {
            $this->url = rtrim($url, '/');  // Remove trailing slash to prevent // in URLs
            $this->authToken = $authToken;  // Save auth token if provided
        } else {
            throw new Exception("Database URL must be specified");
        }
    }

    /**
     * Builds the full URL for a request, appending the auth token if available
     * @param string $path Path inside the database (e.g. "user")
     * @param array|null $queryParams Additional query parameters (optional)
     * @return string Full URL with .json extension and auth token query param
     */
    private function getFullUrl($path, $queryParams = null) {
        $fullUrl = $this->url . "/$path.json";

        // Prepare query string array
        $query = [];

        // Add auth token if set
        if ($this->authToken) {
            $query['auth'] = $this->authToken;
        }

        // Merge with any extra query parameters
        if (is_array($queryParams)) {
            $query = array_merge($query, $queryParams);
        }

        // If there are any query parameters, append them
        if (!empty($query)) {
            $fullUrl .= '?' . http_build_query($query);
        }

        return $fullUrl;
    }

    /**
     * Perform HTTP request with cURL
     * @param string $url Complete URL
     * @param string $method HTTP method ("GET", "POST", "PATCH", "DELETE")
     * @param string|null $payload JSON encoded data (for POST/PATCH)
     * @return string Response body (JSON)
     */
    private function grab($url, $method, $payload = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Insert new data (push)
     * @param string $table Path in Firebase (e.g. "user")
     * @param array $data Data to insert
     * @return string JSON response from Firebase
     */
    public function insert($table, $data) {
        $url = $this->getFullUrl($table);
        return $this->grab($url, "POST", json_encode($data));
    }

    /**
     * Update data at a specific key (PATCH)
     * @param string $table Path in Firebase
     * @param string $uniqueID Key to update
     * @param array $data Data to update
     * @return string JSON response from Firebase
     */
    public function update($table, $uniqueID, $data) {
        $url = $this->getFullUrl("$table/$uniqueID");
        return $this->grab($url, "PATCH", json_encode($data));
    }

    /**
     * Delete data
     * @param string $table Path in Firebase
     * @param string|null $uniqueID Key to delete (null to delete whole table)
     * @return bool True on success, false on failure
     */
    public function delete($table, $uniqueID = null) {
        $path = $uniqueID === null ? $table : "$table/$uniqueID";
        $url = $this->getFullUrl($path);
        $response = $this->grab($url, "DELETE");

        return !empty($response); // Firebase returns "{}" on success
    }

    /**
     * Retrieve data with optional queries (EQUAL, LIKE)
     * @param string $dbPath Path in Firebase
     * @param string|null $queryKey Field name to query on
     * @param string|null $queryType "EQUAL" or "LIKE"
     * @param string|null $queryVal Value to match
     * @return string JSON response
     */
    public function retrieve($dbPath, $queryKey = null, $queryType = null, $queryVal = null) {
        $queryParams = null;
        if (isset($queryType, $queryKey, $queryVal)) {
            if ($queryType == "EQUAL") {
                $queryParams = [
                    'orderBy' => '"' . $queryKey . '"',
                    'equalTo' => '"' . $queryVal . '"'
                ];
            } elseif ($queryType == "LIKE") {
                $queryParams = [
                    'orderBy' => '"' . $queryKey . '"',
                    'startAt' => '"' . $queryVal . '"'
                ];
            }
        }

        $url = $this->getFullUrl($dbPath, $queryParams);
        return $this->grab($url, "GET");
    }
}
