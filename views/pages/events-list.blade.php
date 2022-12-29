@extends('layout')

@section('title', $title)

@section('content')
    <a href="" class="btn btn-success float-end">
        <i icon-name="calendar-plus"></i>
        Create new event
    </a>
    <small>Your personal</small>
    <h2>Events</h2>
    <div class="parent grid">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event name</th>
                        <th>Date</th>
                        <th>Invitees</th>
                        <th class="nowrap">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>Epic party {{ rand() }}</td>
                            <td>2023.05.12</td>
                            <td>{{ rand(5, 30) }}</td>
                            <td class="nowrap">
                                <a href="#" class="btn btn-warning" role="button">
                                    <i icon-name="eye"></i> Details
                                </a>
                                <a href="#" class="btn btn-secondary" role="button">
                                    <i icon-name="printer"></i> PDF
                                </a>
                                <a href="#" class="btn btn-light" role="button">
                                    <i icon-name="edit"></i> Edit
                                </a>
                                <a href="#" class="btn btn-danger" role="button">
                                    <i icon-name="trash-2"></i>
                                </a>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <h2>Archive</h2>
    <div class="parent grid mt-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event name</th>
                        <th>Date</th>
                        <th>Invitees</th>
                        <th class="nowrap">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>Epic party {{ rand() }}</td>
                            <td>2023.05.12</td>
                            <td>{{ rand(5, 30) }}</td>
                            <td class="nowrap">
                                <a href="#" class="btn btn-warning" role="button">
                                    <i icon-name="eye"></i> Details
                                </a>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection