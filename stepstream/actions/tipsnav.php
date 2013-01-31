/*
 			$this->elementStart('ul', array('class' => 'subnav',
                                            'id' => 'tips_nav'));

			$this->elementStart('li', array('id' => 'nav_left'));
            $this->element('a',
                               array('href' => common_local_url('alltips', array('nickname' => $nickname))),
                               "Everyone's tips");
            $this->elementEnd('li');
			$this->elementStart('li', array('id' => 'nav_middle'));
            $this->element('a',
                               array('href' => common_local_url('mytips', array('nickname' =>$nickname))),
                               "My tips");
            $this->elementEnd('li');

			$this->elementStart('li', array('id' => 'nav_middle'));
            $this->element('a',
                               array('href' => common_local_url('todotips', array('nickname' => $nickname))),
                               "To-do");
            $this->elementEnd('li');

			$this->elementStart('li', array('id'=> 'nav_right'));

        	$this->element('a',
                               array('href' => common_local_url('usedtips', array('nickname' => $nickname))),
                               "Tips I have used");
            $this->elementEnd('li');
            $this->elementEnd('ul');
*/