@props(['status' => null])

@if ($status)
    <p role="status" class="mb-4 rounded border-l-2 border-[var(--eligible)] bg-[var(--green-wash)] px-3 py-2 text-sm">
        {{ $status }}
    </p>
@endif

@if ($errors->any())
    {{-- Errors are announced, listed and linked. A colour change alone fails
         WCAG 1.4.1, and a form a screen reader cannot navigate to the failure
         is a form that cannot be corrected. --}}
    <div role="alert" class="mb-4 rounded border-l-2 border-[var(--rejected)] bg-white px-3 py-2 text-sm dark:bg-[var(--paper-raised)]">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
