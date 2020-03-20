<?php


namespace App\Models;


class AccountModel extends Model
{
    /**
     * Log in
     *
     * @param string $username
     * @param string $password
     * @param bool $remember
     *
     * @return bool
     */
    public function login(string $username, string $password, bool $remember)
    {
        $sql = "SELECT ID FROM cms_admins WHERE username=:username AND password=:password AND status='1' LIMIT 1";
        $query = $this->get(
            $sql,
            [
                'username' => $username,
                'password' => $password
            ]
        );

        // Check query
        if ($query != null) {
            // Check query count
            if (count($query) == 1) {
                // Get admin ID
                $ID = $query[0]['ID'];

                // Generate session hash
                $sessionHash = createSessionHash();

                // Update session hash
                $result = $this->updateSessionHash($ID, $sessionHash);

                // Check if session has been updated
                if ($result == 1) {
                    // Check remember
                    if ($remember) {
                        // Set cookies
                        setCookie('ID',       $ID, 30);
                        setCookie('username', $username, 30);
                        setCookie('password', $password, 30);
                        setCookie('hash',     $sessionHash, 30);
                    } else {
                        // Set sessions
                        setSession('ID',       $ID);
                        setSession('username', $username);
                        setSession('password', $password);
                        setSession('hash',     $sessionHash);
                    }

                    return true;
                } else {
                    // Failed
                    return false;
                }
            } else {
                // Not found
                return false;
            }
        } else {
            // Failed
            return false;
        }
    }

    /**
     * Update session hash
     *
     * @param int $ID
     * @param string $sessionHash
     *
     * @return int
     */
    public function updateSessionHash(int $ID, string $sessionHash)
    {
        // Update user session hash
        $result = $this->update(
            'cms_admins',
            [
                'sessionHash' => $sessionHash
            ],
            [
                'ID' => $ID
            ],
            'LIMIT 1'
        );

        return $result;
    }

    /**
     * Validate session
     *
     * @param array $session
     *
     * @return bool
     */
    public function validateSession($session = array())
    {
        $sql = "
            SELECT ID 
            FROM cms_admins 
            WHERE 
                ID=:ID 
                AND username=:username 
                AND password=:password 
                AND sessionHash=:sessionHash 
                AND status='1' 
                LIMIT 1
        ";
        $query = $this->get(
            $sql,
            [
                'ID'          => $session['ID'],
                'username'    => $session['username'],
                'password'    => $session['password'],
                'sessionHash' => $session['sessionHash'],
            ]
        );
        // Check query
        if ($query != null) {
            // Check count
            if (count($query) == 1) {
                // Valid
                return true;
            } else {
                // Failed
                return false;
            }
        } else {
            // Failed
            return false;
        }
    }
}