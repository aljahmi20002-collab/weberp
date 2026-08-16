@if (!blank($assigned_leaves))
    @foreach ($assigned_leaves as $leave)
        <option value="{{ $leave->id }}">{{ $leave->leaveType->name }}</option>
    @endforeach
@endif
