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

		if($action && S('Application')->isActionBrand($action) ) {
			$this->tpl->setGlob('baseurl', "/{$action}");
            $this->brand = $action;
		} else {
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
        if(isset($this->brand)) {
            $action = S('Request')->getDir(1);
        } else {
            $action = S('Request')->getDir(0);
        }
		if( !$this->_isSubscribed() && ($action != 'subscribe') )
		{
			Response::redirect('/subscribe');
		}
	}

    /**
     * Generates session parameter key string
     * @return string
     */
	private function getSessionKey()
    {
        return isset($this->brand) ? "subs-{$this->brand}" : 'subs';
    }

}
