@php
    $fieldType = $field->field_type ?? null;
    $isEmpty = $value === null || $value === '' || (is_array($value) && count($value) === 0);
    $values = is_array($value) ? $value : [$value];
@endphp

@if($isEmpty)
    <span class="text-muted">-</span>
@elseif($fieldType === 'file')
    @foreach($values as $filePath)
        @if(is_string($filePath) && $filePath !== '')
            @php
                $fileUrl = \Illuminate\Support\Str::startsWith($filePath, ['http://', 'https://'])
                    ? $filePath
                    : asset('storage/' . $filePath);
            @endphp
            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-info btn-sm mb-1">
                <i class="fas fa-paperclip"></i> {{ basename($filePath) }}
            </a>
        @endif
    @endforeach
@elseif(is_array($value))
    @foreach($value as $option)
        <span class="badge badge-light border mb-1">{{ $option }}</span>
    @endforeach
@else
    {!! nl2br(e($value)) !!}
@endif
