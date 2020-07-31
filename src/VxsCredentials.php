<?php


namespace VxsBill;


use VxsBill\Exceptions\CouldNotLoadCredentials;

class VxsCredentials
{
    protected $site;
    protected $package;
    protected $username;
    protected $password;
    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site): void
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param mixed $package
     */
    public function setPackage($package): void
    {
        $this->package = $package;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function __construct(array $params)
    {

        if ($this->validateCredentials($params)) {
            $this->loadCredentials($params);
        }
    }

    public function toArray(): array
    {
        return [
            "username" => $this->getUsername(),
            "password" => $this->getPassword(),
            "package" => $this->getPackage(),
            "site" => $this->getSite()
        ];
    }
    protected function validateCredentials($params): bool
    {
        if (!isset($params['username'])) {
            throw CouldNotLoadCredentials::usernameNotSet();
        }

        if (!isset($params['password'])) {
            throw CouldNotLoadCredentials::passwordNotSet();
        }

        if (!isset($params['site'])) {
            throw CouldNotLoadCredentials::siteNotSet();
        }

        if (!isset($params['package'])) {
            throw CouldNotLoadCredentials::packageNotSet();
        }

        return true;
    }

    protected function loadCredentials($params): bool
    {
        $this->setUsername($params['username']);
        $this->setPassword($params['password']);
        $this->setPackage($params['package']);
        $this->setSite($params['site']);

        return true;
    }
}
