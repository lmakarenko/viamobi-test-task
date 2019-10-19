<?php

namespace test;

class Controller
{
	protected $tpl;
	protected $req;
	protected $brand;

	public function __construct()
	{
		$this->tpl = S('Template');
		$this->req = S('Request');
		$action = $this->req->getDir(0);

		if( ($action == 'brand1') || ($action == 'brand2') )
		{
			$this->tpl->setGlob('baseurl', "/{$action}");
            $this->brand = $action;
		}
		else
		{
			$this->tpl->setGlob('baseurl', '');
		}

		session_start();

		$this->_performChecks();
	}

	protected function _subscribe()
	{
        $key = $this->getSessionKey();
		$_SESSION[$key] = 1;
	}

	protected function _unsubscribe()
	{
	    $key = $this->getSessionKey();
		$_SESSION[$key] = 0;
	}

	private function _isSubscribed()
	{
	    $key = $this->getSessionKey();
		return isset($_SESSION[$key]) ? $_SESSION[$key] : 0;
	}

	private function _performChecks()
	{
	    $action = $this->req->getDir(0);
	    switch($this->brand) {
	        case 'brand1':
            case 'brand2':
                $action = S('Request')->getDir(1);
                break;
        }
		if( !$this->_isSubscribed() && ($action != 'subscribe') )
		{
			Response::redirect('/subscribe');
		}
	}
	private function getSessionKey()
    {
        return isset($this->brand) ? "subs-{$this->brand}" : 'subs';
    }
}
