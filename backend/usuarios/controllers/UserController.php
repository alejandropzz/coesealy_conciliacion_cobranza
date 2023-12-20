<?php

/**
 * Description of UsuarioController
 *
 * @author memogarrido
 */
class UserController  extends DatabaseEntity {

    private $user;

    function __construct($user) {
        $this->user = $user;
        parent::__construct();
    }

        function getUser() {
        if ( !empty($this->user->getEmail()) && !empty($this->user->getPasswd())) {
            $sql = "SELECT `users`.`id`, " .
                    "`users`.`email`, " .
                    "`users`.`passwd`, " .
                    "`users`.`name`, " .
                    "`users`.`priv` " .
                    " FROM `coedb_conciliacion_cobranza`.`users` " .
                    " WHERE email=:email " .
                    "AND passwd=:passwd ";
            try {
                $stmt = parent::getConnection()->prepare($sql);
                $stmt->bindParam(':email', $this->user->getEmail(), PDO::PARAM_STR);
                $stmt->bindParam(':passwd', sha1($this->user->getPasswd()), PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $user = null;
                    if ($row = $stmt->fetch()) {
                        $user = new User();
                        $user->setId($row["id"]);
                        $user->setName($row["name"]);
                        $user->setEmail($row["email"]);
                        $user->setPriv($row["priv"]);
                    }
                    return $user;
                } else {
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }
        } else {
            return null;
        }
    }
    

}

class ResponseUser extends ResponseStatus {

    private $user;
    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }



}

