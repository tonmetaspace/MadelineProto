<?php
/**
 * MTProto Auth key
 *
 * This file is part of MadelineProto.
 * MadelineProto is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * MadelineProto is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU General Public License along with MadelineProto.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Daniil Gentili <daniil@daniil.it>
 * @copyright 2016-2019 Daniil Gentili <daniil@daniil.it>
 * @license   https://opensource.org/licenses/AGPL-3.0 AGPLv3
 *
 * @link      https://docs.madelineproto.xyz MadelineProto documentation
 */

namespace danog\MadelineProto\AuthKey;

use JsonSerializable;

/**
 * MTProto auth key
 */
class AuthKey implements JsonSerializable
{
    /**
     * Auth key
     *
     * @var string
     */
    private $authKey;
    /**
     * Auth key ID
     *
     * @var string
     */
    private $id;
    /**
     * Server salt
     *
     * @var string
     */
    private $serverSalt;
    /**
     * Whether the auth key is bound
     *
     * @var boolean
     */
    private $bound = false;
    /**
     * Whether the connection is inited for this auth key
     *
     * @var boolean
     */
    private $inited = false;

    /**
     * Constructor function 
     *
     * @param array $old Old auth key array
     */
    public function __construct(array $old = [])
    {
        if (isset($old['auth_key'])) {
            if (strlen($old['auth_key']) !== 2048/8 && strpos($old['authkey'], 'pony') === 0) {
                $old['auth_key'] = base64_decode(substr($old['auth_key'], 4));
            }
            $this->setAuthKey($old['auth_key']);
        }
        if (isset($old['server_salt'])) {
            $this->setServerSalt($old['server_salt']);
        }
        if (isset($old['bound'])) {
            $this->bind($old['bound']);
        }
        if (isset($old['connection_inited'])) {
            $this->init($old['connection_inited']);
        }
    }


    /**
     * Set auth key
     *
     * @param string $authKey Authorization key
     * 
     * @return void
     */
    public function setAuthKey(string $authKey)
    {
        $this->authKey = $authKey;
        $this->id = substr(sha1($authKey, true), -8);
    }

    /**
     * Check if auth key is present
     *
     * @return boolean
     */
    public function hasAuthKey(): bool
    {
        return $this->authKey !== null;
    }

    /**
     * Get auth key
     *
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    /**
     * Get auth key ID
     *
     * @return string
     */
    public function getID(): string
    {
        return $this->id;
    }

    /**
     * Set server salt
     *
     * @param string $salt Server salt
     * 
     * @return void
     */
    public function setServerSalt(string $salt)
    {
        $this->serverSalt = $salt;
    }

    /**
     * Get server salt
     *
     * @return string
     */
    public function getServerSalt(): string
    {
        return $this->serverSalt;
    }

    /**
     * Check if has server salt
     *
     * @return boolean
     */
    public function hasServerSalt(): bool
    {
        return $this->serverSalt !== null;
    }

    /**
     * Bind auth key
     *
     * @param boolean $bound Bind or unbind
     * 
     * @return void
     */
    public function bind(bool $bound = true)
    {
        $this->bound = $bound;
    }

    /**
     * Check if auth key is bound
     *
     * @return boolean
     */
    public function isBound(): bool
    {
        return $this->bound;
    }
    
    /**
     * Init or deinit connection for auth key
     *
     * @param boolean $init Init or deinit
     * 
     * @return void
     */
    public function init(bool $init = true)
    {
        $this->inited = $init;
    }
    /**
     * Check if connection is inited for auth key
     *
     * @return boolean
     */
    public function isInited(): bool
    {
        return $this->inited;
    }


    /**
     * JSON serialization function
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'auth_key' => 'pony'.base64_encode($this->authKey),
            'server_salt' => $this->serverSalt,
            'bound' => $this->bound,
            'connection_inited' => $this->inited
        ];
    }
}