<?php

namespace Com\Foundation;

use Exception;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Sessionable;
use Subvert\Framework\Contract\SessionProcesser as SessionProcesserContract;

abstract class SessionProcesser implements SessionProcesserContract
{

    protected $session;

    public function __construct(Sessionable $session)
    {
        $this->session = $session;
    }

    public function input($data)
    {
        return $this->operate($data, $this->getInputRegular());
    }

    public function output($data)
    {
        return $this->operate($data, $this->getOutputRegular());
    }

    protected function operate($data, $regulars)
    {
        if (!empty($regulars['r'])) {
            $data = $this->readBySession($data, $regulars['r']);
        }

        if (!empty($regulars['w'])) {
            $this->writeToSession($data, $regulars['w']);
        }

        if (!empty($regulars['d'])) {
            $this->deleteSession($data, $regulars['d']);
        }

        return $data;
    }

    protected function readBySession($data, $regulars)
    {

        foreach ($regulars as $regular) {
            $read = $this->session->get($regular);
            if (is_null($read)) {
                throw new Exception(ResponseData::set(SystemCode::SYSTEM_READ_SESSION_ERROR,false));
            }

            $data[$regular] = $read;
        }

        return $data;

    }

    protected function writeToSession($data, $regulars)
    {
        foreach ($regulars as $regular) {
            if (!isset($data[$regular])) {
                throw new Exception(ResponseData::set(SystemCode::SYSTEM_WRITE_SESSION_ERROR,false));
            }

            $this->session->set($regular, $data[$regular]);
        }
    }

    protected function deleteSession($data, $regulars)
    {
        foreach ($regulars as $regular) {
            if ($regular == '*') {
                $this->session->destory();
                return;
            }

            $this->session->delete($regular);
        }

    }
    
    /**
     * return example
     * [
     *     'r'  => ['key'], // session读取到request
     *     'w'  => ['key'], // request写入到session
     *     'd'  => ['key'], // 删除session key值。 * 表示全部删除
     * ]
     * @return [type] [description]
     */
    abstract public function getInputRegular();

    /**
     * return example
     * [
     *     'r'  => ['key'], // session读取到response
     *     'w'  => ['key'], // response写入到session
     *     'd'  => ['key'], // 删除session key值。 * 表示全部删除
     * ]
     * @return [type] [description]
     */
    abstract public function getOutputRegular();

}
