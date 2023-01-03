<?php

class EventController {
    public static function list() {
        view('pages/events-list', [
            'title' => 'Events',
            'active_page' => 'events',
        ]);
    }

    public static function details() {
        view('pages/event-details', [
            'title' => 'Details',
            'active_page' => 'details',
        ]);
    }
}