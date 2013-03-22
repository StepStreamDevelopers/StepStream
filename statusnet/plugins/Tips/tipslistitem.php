<?php


if (!defined('STATUSNET')) {
    // This check helps protect against security problems;
    // your code file can't be executed directly from the web.
    exit(1);
}

/**
 * Notice-list representation of a tip
 *
 *
 * @category  Tips
 * @package   StatusNet
 * @author    Gayathri Premachandran 
 */
class TipsListItem extends NoticeListItemAdapter
{
    function showNotice()
    {
        $this->nli->out->elementStart('div', 'entry-title');
        $this->nli->showAuthor();
        $this->showContent();
        $this->nli->out->elementEnd('div');
    }

    function showContent()
    {
        $notice = $this->nli->notice;
        $out    = $this->nli->out;

        $profile = $notice->getProfile();
        $tip   = Tips::fromNotice($notice);

        // FIXME: URL, image, video, audio
        $this->nli->out->elementStart('p', array('class' => 'entry-content'));
        if ($this->nli->notice->rendered) {
            $this->nli->out->raw($this->nli->notice->rendered);
        } else {
            // XXX: may be some uncooked notices in the DB,
            // we cook them right now. This should probably disappear in future
            // versions (>> 0.4.x)
            $this->nli->out->raw(common_render_content($this->nli->notice->content, $this->nli->notice));
        }
        $this->nli->out->elementEnd('p');
    }

    function showNoticeAttachments() {
        if (common_config('attachments', 'show_thumbs')) {
            $al = new InlineAttachmentList($this->notice, $this->out);
            $al->show();
        }

/*
        if (empty($tip)) {
            // TRANS: Content for a deleted tip list item 
            $out->element('p', null, _m('Deleted.'));
            return;
        }
       
        $out->elementStart('div', 'vevent event'); // VEVENT IN

        if (!empty($tip->description)) {
            $out->elementStart('div', 'tip-description');
            // TRANS: Field label for tip description.
            $out->element('strong', null, _m('Tip:'));
            $out->element('span', 'description', $tip->description);
            $out->elementEnd('div');
        }

        $subscribes = $tip->getSubcribes();

        $out->elementStart('div', 'tip-subscribes');
        // TRANS: Field label for tip description.
        //$out->element('strong', null, _m('Using:'));
        $out->element('span', 'tip-subscribes',
                      // TRANS: RSVP counts.
                      // TRANS: %1$d, %2$d and %3$d are numbers of RSVPs.
                      sprintf(_m('Used by: %1$d  To-do: %3$d'),
                              count($subscribes[Subscribe::POSITIVE]),
                              count($subscribes[Subscribe::NEGATIVE]),
                              count($subscribes[Subscribe::POSSIBLE])));
        $out->elementEnd('div');

        $user = common_current_user();

        if (!empty($user)) {
            $subscribe = $tip->getSubscribe($user->getProfile());

            if (empty($subscribe)) {
                $form = new SubscribeTipForm($tip, $out);
            } else {
                $form = new CancelSubscribeForm($subscribe, $out);
            }

            $form->show();
        }

        $out->elementEnd('div'); // vevent out
*/  
}
}
