<?php

// Note: namespace was changed for example
namespace PHPInterviewTasks\realProjects\bitrix24Edna\classes;

use mysqli;
use mysqli_sql_exception;
use RuntimeException;

mysqli_report(MYSQLI_REPORT_STRICT);

class DbHelper
{
    private ?mysqli $dbh_link = null;
    private string $error = '';

    /**
     * DATABASE: CONNECT
     *
     * @param string $p_mysql_host
     * @param string $p_mysql_user
     * @param string $p_mysql_pass
     * @param string $p_mysql_db
     * @param string $p_mysql_charset
     */
    public function __construct(
        string $p_mysql_host,
        string $p_mysql_user,
        string $p_mysql_pass,
        string $p_mysql_db,
        string $p_mysql_charset = 'UTF8'
    ) {
        date_default_timezone_set('Europe/Minsk');

        try {
            $this->dbh_link = new mysqli($p_mysql_host, $p_mysql_user, $p_mysql_pass, $p_mysql_db);
            $this->dbh_link->set_charset($p_mysql_charset);
        } catch (mysqli_sql_exception $ex) {
            error_log("Database Connection error: " . $ex->getMessage());
            $this->error = $ex->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * DATABASE: CONNECTION CHECK
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return !empty($this->dbh_link);
    }

    /**
     * DATABASE: PREPARE of the QUERY
     * @param string $query
     * @return false|mysqli_stmt
     */
    public function prepare(string $query)
    {
        return $this->dbh_link->prepare($query);
    }

    /**
     * DATABASE: QUERY
     *
     * @param string $qr
     * @return bool|mysqli_result|int
     */
    public function query(string $qr)
    {
        if (!$this->isConnected()) {
            return false;
        }
        $result = $this->dbh_link->query($qr);
        if ($result === false) {
            return false;
        }

        if ($result === true) { // Non-select queries
            return $this->dbh_link->affected_rows;
        }

        return $result; // For select queries
    }

    /**
     * DATABASE: LOOKUP
     *
     * @param string $table
     * @param string $field
     * @param string $value
     * @param string $result_field
     * @return false|mixed
     */
    public function lookup(
        string $table,
        string $field,
        string $value,
        string $result_field
    ) {
        $res = false;
        if ($this->isConnected()) {
            $table = $this->dbh_link->real_escape_string($table);
            $field = $this->dbh_link->real_escape_string($field);
            $result_field = $this->dbh_link->real_escape_string($result_field);

            $stmt = $this->prepare("SELECT $result_field FROM $table WHERE $field = ?");
            if ($stmt === false) {
                error_log("Failed to prepare statement: " . $this->dbh_link->error);
                $this->error = $this->dbh_link->error;
            } else {
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $res = $row[$result_field];
                }

                $stmt->close();
            }
        }

        return $res;
    }

    /**
     * DATABASE: SET SYSTEM VARIABLE
     *
     * @param int $user
     * @param string $var
     * @param string $value
     * @return bool
     */
    public function setSysVar(int $user, string $var, string $value): bool
    {
        if (!$this->isConnected()) {
            throw new RuntimeException("Database not connected");
        }

        $var = trim($var);
        $stmt = $this->prepare("REPLACE INTO sysvars(userid, varname, varvalue) values(?, ?, ?)");
        if ($stmt === false) {
            error_log("Query error");
            $this->error = $this->dbh_link->error;
            throw new RuntimeException("Query error: " . $this->error);
        }

        $stmt->bind_param("iss", $user, $var, $value);
        try {
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (mysqli_sql_exception $ex) {
            error_log("Database error: " . $ex->getMessage());
            $stmt->close();
            $this->error = "Database error: " . $ex->getMessage();
            throw new RuntimeException("Query error: " . $this->error);
        }
    }

    /**
     * DATABASE: GET SYSTEM VARIABLE
     *
     * @param int $user
     * @param string $var
     * @return false|mixed|string
     */
    public function getSysVar(int $user, string $var): ?string
    {
        $res = null;

        if ($this->isConnected()) {
            $var = trim($var);
            $stmt = $this->prepare("SELECT * FROM sysvars WHERE varname = ? AND userid = ?");
            if ($stmt === false) {
                error_log("Query error");
                $this->error = $this->dbh_link->error;
                throw new RuntimeException("Query error: " . $this->error);
            }

            $stmt->bind_param("si", $var, $user);
            try {
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result !== false && $result->num_rows !== 0) {
                    $val = $result->fetch_assoc();
                    if (!empty($val) && is_array($val)) {
                        $res = $val['varvalue'];
                    }
                }
            } catch (mysqli_sql_exception $ex) {
                error_log("Database error: " . $ex->getMessage());
                $stmt->close();
                $this->error = "Database error: " . $ex->getMessage();
            }
        } else {
            throw new RuntimeException("Database not connected");
        }

        return $res;
    }

    /**
     * DATABASE: CHECKS IF COLUMN NOT EXISTS ADD COLUMN
     * @param string $tableName
     * @param string $columnName
     * @param string $columnDefinition
     * @return bool
     */
    public function ensureColumnExists(string $tableName, string $columnName, string $columnDefinition): bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        $query = "SELECT COLUMN_NAME 
              FROM INFORMATION_SCHEMA.COLUMNS 
              WHERE TABLE_SCHEMA = '{$this->dbh_link->real_escape_string($_SESSION['CFG']['DB_NAME'])}' 
              AND TABLE_NAME = '{$this->dbh_link->real_escape_string($tableName)}' 
              AND COLUMN_NAME = '{$this->dbh_link->real_escape_string($columnName)}'";

        $result = $this->query($query);

        if ($result->num_rows === 0) {
            $alterQuery = "ALTER TABLE {$tableName} ADD COLUMN {$columnName} {$columnDefinition}";
            $this->query($alterQuery);
            return true;
        }

        return false;
    }

}