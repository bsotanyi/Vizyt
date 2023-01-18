<?php

class CronController {
    public static function sendEmails() {
        set_time_limit(0);
        ini_set('memory_limit', '500M');

        // send event reminders
        $invites = DB::query("SELECT i.receiver_email, i.token, e.name as event_name, e.id as event_id, e.invites, e.datetime, u.email as admin_email FROM events e INNER JOIN invites i ON i.event_id = e.id INNER JOIN users u ON u.id = e.user_id WHERE DATE(e.reminder) = CURDATE()");

        $admin_emails_sent = [];

        foreach ($invites as $invite) {
            $invite['invites'] = json_decode($invite['invites'] ?? '[]', true);
            $invite['invites'] = array_combine(array_column($invite['invites'], 'email'), $invite['invites']);

            $email = $invite['receiver_email'];
            $name = !empty($invite['invites'][$email]) ? $invite['invites'][$email]['name'] : 'Guest';

            sendMail(
                $email,
                $name,
                'Vizyt - Event reminder',
                'emails/email-reminder',
                [
                    'token' => $invite['token'],
                    'invited_name' => $name,
                    'event_name' => $invite['event_name'],
                    'datetime' => $invite['datetime'],
                ],
            );

            if (empty($admin_emails_sent[$invite['event_id']])) {
                sendMail(
                    $invite['admin_email'],
                    'Event organizer',
                    'Vizyt - Reminders successfully sent',
                    'emails/email-reminder-admin',
                    [
                        'event_name' => $invite['event_name'],
                        'event_id' => $invite['event_id'],
                    ],
                );
                $admin_emails_sent[$invite['event_id']] = true;
            }
        }

        // send comment reminders
        $comment_emails = DB::query("SELECT i.receiver_email, e.name as event_name, e.id as event_id, e.invites, u.email as admin_email FROM events e INNER JOIN invites i ON i.event_id = e.id INNER JOIN users u ON u.id = e.user_id WHERE DATE(e.datetime) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND e.is_commentable = 1");

        $admin_emails_sent = [];

        foreach ($invites as $invite) {
            $invite['invites'] = json_decode($invite['invites'] ?? '[]', true);
            $invite['invites'] = array_combine(array_column($invite['invites'], 'email'), $invite['invites']);

            $email = $invite['receiver_email'];
            $name = !empty($invite['invites'][$email]) ? $invite['invites'][$email]['name'] : 'Guest';

            sendMail(
                $email,
                $name,
                'Vizyt - Comment reminder',
                'emails/email-comment-reminder',
                [
                    'invited_name' => $name,
                    'event_name' => $invite['event_name'],
                    'event_id' => $invite['event_id'],
                ],
            );

            if (empty($admin_emails_sent[$invite['event_id']])) {
                sendMail(
                    $invite['admin_email'],
                    'Event organizer',
                    'Vizyt - Comment section now open',
                    'emails/email-comment-reminder-admin',
                    [
                        'event_name' => $invite['event_name'],
                        'event_id' => $invite['event_id'],
                    ],
                );
                $admin_emails_sent[$invite['event_id']] = true;
            }
        }
    }
}