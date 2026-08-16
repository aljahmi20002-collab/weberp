    <!-- Responsive Dashboard Table -->
    <div class="table-responsive table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="border-bottom">
                    <th>{{ __('log_name') }}</th>
                    <th>{{ __('new') }}</th>
                    @if (@$logDetails->properties['old'])
                        <th>{{ __('old') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>

                @foreach ($logDetails->properties['attributes'] as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{!! $value !!}</td>
                        @if (@$logDetails->properties['old'])
                            <td>{!! @oldLogDetails($logDetails->properties['old'], $key) !!}</td>
                        @endif
                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
    <!-- Responsive Dashboard Table -->
