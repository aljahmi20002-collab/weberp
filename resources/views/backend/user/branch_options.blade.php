@if (!blank($branches))
    @foreach ($branches as $branch)
        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
    @endforeach
@endif
