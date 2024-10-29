<?php

// Note: namespace was changed for example
namespace PHPInterviewTasks\realProjects\bitrix24Edna\classes;

use Exception;

class SendMailSmtpClass
{
    private string $smtp_username;
    private string $smtp_password;
    private string $smtp_host;
    private string $smtp_from;
    private int $smtp_port;
    private string $smtp_charset;

    /**
     * SendMailSmtpClass constructor.
     *
     * @param string $smtp_username
     * @param string $smtp_password
     * @param string $smtp_host
     * @param int $smtp_port
     * @param string $smtp_from
     * @param string $smtp_charset
     */
    public function __construct(
        string $smtp_username,
        string $smtp_password,
        string $smtp_host,
        int $smtp_port,
        string $smtp_from,
        string $smtp_charset = 'utf-8'
    ) {
        $this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host = $smtp_host;
        $this->smtp_from = $smtp_from;
        $this->smtp_port = $smtp_port;
        $this->smtp_charset = $smtp_charset;
    }

    /**
     * Отправка письма
     *
     * @param string $mailTo
     * @param string $subject
     * @param string $message
     * @param string $headers
     * @return bool|string
     */
    public function send(string $mailTo, string $subject, string $message, string $headers)
    {
        $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?' . base64_encode($subject) . "=?=\r\n";
        $contentMail .= $headers . "\r\n";
        $contentMail .= $message . "\r\n";

        try {
            $socket = fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 7);
            if (!$socket) {
                error_log($errorNumber . ": " . $errorDescription);
                throw new Exception($errorNumber . "." . $errorDescription);
            }
            if (!$this->parseServer($socket, "220")) {
                error_log('Connection error');
                throw new Exception('Connection error');
            }

            $server_name = $_SERVER["SERVER_NAME"];
            fwrite($socket, "HELO $server_name\r\n");
            if (!$this->parseServer($socket, "250")) {
                error_log('Error of command sending: HELO');
                fclose($socket);
                throw new Exception('Error of command sending: HELO');
            }

            fwrite($socket, "AUTH LOGIN\r\n");
            if (!$this->parseServer($socket, "334")) {
                error_log('Authorization error');
                fclose($socket);
                throw new Exception('Authorization error');
            }

            fwrite($socket, base64_encode($this->smtp_username) . "\r\n");
            if (!$this->parseServer($socket, "334")) {
                error_log('Authorization error');
                fclose($socket);
                throw new Exception('Authorization error');
            }

            fwrite($socket, base64_encode($this->smtp_password) . "\r\n");
            if (!$this->parseServer($socket, "235")) {
                error_log('Authorization error');
                fclose($socket);
                throw new Exception('Authorization error');
            }

            fwrite($socket, "MAIL FROM: <" . $this->smtp_username . ">\r\n");
            if (!$this->parseServer($socket, "250")) {
                error_log('Error of command sending: MAIL FROM');
                fclose($socket);
                throw new Exception('Error of command sending: MAIL FROM');
            }

            $mailTo = ltrim($mailTo, '<');
            $mailTo = rtrim($mailTo, '>');
            fwrite($socket, "RCPT TO: <" . $mailTo . ">\r\n");
            if (!$this->parseServer($socket, "250")) {
                error_log('Error of command sending: RCPT TO');
                fclose($socket);
                throw new Exception('Error of command sending: RCPT TO');
            }

            fwrite($socket, "DATA\r\n");
            if (!$this->parseServer($socket, "354")) {
                error_log('Error of command sending: DATA');
                fclose($socket);
                throw new Exception('Error of command sending: DATA');
            }

            fwrite($socket, $contentMail . "\r\n.\r\n");
            if (!$this->parseServer($socket, "250")) {
                error_log("E-mail didn't send");
                fclose($socket);
                throw new Exception("E-mail didn't send");
            }

            fwrite($socket, "QUIT\r\n");
            fclose($socket);
        } catch (Exception $e) {
            error_log("Message sent Exception: " . $e->getMessage());
            return $e->getMessage();
        }
        return true;
    }

    /**
     * Parse server response
     *
     * @param resource $socket
     * @param string $response
     * @return bool
     */
    private function parseServer($socket, string $response): bool
    {
        $responseServer = ''; // Инициализация переменной
        while (@substr($responseServer, 3, 1) != ' ') {
            if (!($responseServer = fgets($socket, 256))) {
                return false;
            }
        }
        return substr($responseServer, 0, 3) == $response;
    }
}