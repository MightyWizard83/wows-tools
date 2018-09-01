@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <!-- Current Players -->
    @if (count($players) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Players
            </div>

            <div class="panel-body">
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Players</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($players as $player)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $player->nickname }}</div>
                                </td>

                                <td>
                                    <!-- TODO: Delete Button -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection