<?php

namespace Com\Foundation;

use Exception;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Sessionable;
use Subvert\Framework\Contract\SessionProcesser as SessionProcesserContract;

class SessionProcesser implements SessionProcesserContract
{

    protected $session;
    protected $regulars;

    public function __construct(Sessionable $session, array $regulars)
    {
        $this->session  = $session;
        $this->regulars = $regulars;
    }

    public function input($data)
    {
        $regulars = empty($this->regulars['session']) || empty($this->regulars['session']['in']) ? [] : $this->regulars['session']['in'];
        $r = isset($regulars['r']) ? $regulars['r'] : [];
        return $this->operate($data, $r);
    }

    public function output($data)
    {
        $regulars = empty($this->regulars['session']) || empty($this->regulars['session']['out']) ? [] : $this->regulars['session']['out'];
        $r = isset($regulars['r']) ? $regulars['r'] : [];
        $d = isset($regulars['d']) ? $regulars['d'] : [];
        $w = isset($regulars['w']) ? $regulars['w'] : [];
        $s = isset($regulars['s']) ? $regulars['s'] : [];
        return $this->operate($data, $r, $w, $d, $s);
    }

    public function token()
    {
        if (isset($this->regulars['token']) && $this->regulars['token']) {
            return $this->session->sessionId();
        }

        return '';
    }

    protected function operate($data, $r = [], $w = [], $d = [], $s = [])
    {

        if (!empty($r)) {
            $data = $this->readBySession($data, $r);
        }

        if (!empty($w)) {
            $this->writeToSession($data, $w);
        }

        if (!empty($d)) {
            $this->deleteSession($data, $d);
        }

        if (!empty($s)) {
            $data = $this->saveSession($data, $s);
        }

        return $data;
    }

    protected function saveSession($data, $regulars)
    {

        foreach ($regulars as $regular) {
            
            if ($regular == '*') {
                foreach ($data as $k => $v) {
                    $this->session->set($k, $v);
                }
                return [];
            }

            if (!isset($data[$regular])) {
                throw new Exception(ResponseData::set(SystemCode::SYSTEM_SAVE_SESSION_ERROR,false));
            }

            $this->session->set($regular, $data[$regular]);

            unset($data[$regular]);
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

}
