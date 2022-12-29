<?php

class EventController {
    public static function list() {
        view('pages/events-list', [
            'title' => 'Events',
            'active_page' => 'events',
        ]);
    }
}