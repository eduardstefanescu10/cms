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
                        setCook('ID',       $ID, 30);
                        setCook('username', $username, 30);
                        setCook('password', $password, 30);
                        setCook('hash',     $sessionHash, 30);
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

    /**
     * Update admin's temporarily password
     *
     * @param string $email
     * @param string $tempPass
     *
     * @return bool
     */
    public function resetPassword(string $email, string $tempPass)
    {
        $query = $this->update(
            'cms_admins',
            [
                'passwordRecovery' => $tempPass
            ],
            [
                'email' => $email
            ]
        );

        // Check results
        if ($query == 1) {
            // Success
            return true;
        } else {
            // Failed
            return false;
        }
    }

    /**
     * Check if email exists and update password
     *
     * @param string $email
     * @param string $tempPass
     *
     * @return int
     */
    public function forgot(string $email, string $tempPass)
    {
        $sql = "SELECT ID FROM cms_admins WHERE email=:email AND status='1' LIMIT 1";
        $query = $this->get(
            $sql,
            [
                'email' => $email
            ]
        );

        // Check result
        if ($query !== null) {
            // Check query count
            if (count($query) == 1) {
                // Update password
                $result = $this->resetPassword($email, $tempPass);

                // Check result
                if ($result) {
                    // Success
                    return $query[0]['ID'];
                } else {
                    // Failed
                    return -1;
                }
            } else {
                // Not found
                return 0;
            }
        } else {
            // Failed
            return -1;
        }
    }

    /**
     * Update temporarily password
     *
     * @param int $ID
     * @param string $tempPass
     *
     * @return bool
     */
    public function updateTempPass(int $ID, string $tempPass)
    {
        $sql = "SELECT ID FROM cms_admins WHERE ID=:ID AND passwordRecovery=:tempPass AND status='1' LIMIT 1";
        $query = $this->get(
            $sql,
            [
                'ID'       => $ID,
                'tempPass' => $tempPass
            ]
        );

        // Check query
        if ($query != null) {
            // Check query count
            if (count($query) == 1) {
                // Update password
                $query2 = $this->update(
                    'cms_admins',
                    [
                        'passwordRecovery' => '',
                        'password'         => $tempPass
                    ],
                    [
                        'ID' => $ID
                    ],
                    'LIMIT 1'
                );

                // Check query
                if ($query2 == 1) {
                    // Success
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
}